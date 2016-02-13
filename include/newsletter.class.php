<?php
/**
 * Diese Datei enthaelt die Klasse <i>Newsletter</i>.
 * @author Sebastian de Vries und Stefan Rank-Kunitz at Open-Letters anno 2009
 * @author Bernd Krüger-Knauber anno 2012
 * @package Newslettersystem
 * @subpackage System
 */

/** Elternklasse laden */
require_once( ROOT_PATH . "framework/parentclass.class.php");

/** Templateklasse laden */
require_once( ROOT_PATH . "include/newsletter_template.class.php");

/** for backwards compatibility */
if (!defined("SMTP_PASSWORD") && defined("SMPT_PASSWORD")) {
    define("SMTP_PASSWORD", SMPT_PASSWORD);
}

/**
 * Ein Objekt dieser Klasse ist ein Newsletter. Er kann erstellt, bearbeitet
 * und geloescht werden.
 * @author Sebastian de Vries und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */
class Newsletter extends Parentclass {

    /** Objekt der Klasse NewsletterTemplate */
    protected $template;

    /** das fast fertige HTML-Template aus der Template-Klasse */
    protected $rendered_html_template;

    /** das fast fertige Text-Template aus der Template-Klasse */
    protected $rendered_text_template;

    /** Liste der Eintraege dieses Newsletters aus der DB */
    protected $entries;

    /**
     * die ID dieses Newsletters in der DB
     * @var int
     */
    protected $newsletter_id;

    /** der Zeitpunkt dieses Newsletters */
    protected $timestamp;

    /**
     * Der  Konstruktor uebernimmt die uebergebene Newsletter-ID nur, wenn es diese
     * wirklich in der Datenbank gibt. Anderenfalls bricht das Programm mit einem
     * <i>die( Fehlermeldung)</i> ab.
     * @param int $newsletter_id die ID dieses Newsletters in der Datenbank
     */
    public function Newsletter($newsletter_id) {
        parent::__construct();

        // Daten dieses Newsletters auslesen
        $sql = "SELECT * FROM " . PREFIX . "newsletterCont WHERE id=:newsletter_id;";
        $erg = $this->db->prepare_and_execute($sql, array("newsletter_id"=>$newsletter_id));

        if($erg && count($erg) == 1) {
            // Werte uebernehmen
            $this->newsletter_id = $newsletter_id;
            $this->timestamp = $erg[0]['date'];

            // Template-Klasse instanzieren
//			$this->template = new NewsletterTemplate( "template_01", $erg[0]['date']);
            $this->template = new NewsletterTemplate($erg[0]['templatefile'], $erg[0]['date']);

            // Eintraege des Newsletters lesen
            $sql = "SELECT id, headline, content FROM " . PREFIX . "newsletterEntries "
                . "WHERE newsletterContId=:newsletter_id ORDER BY ordering ASC;";
            $this->entries = $this->db->prepare_and_execute($sql, array("newsletter_id"=>$newsletter_id));

        } else {
            // ID existiert nicht: Programm vollstaendig abbrechen
            $message = $this->text->get_error("newsletter_dosnt_exists") . " (ID:" . $newsletter_id . ")";
            $this->logmessage($message);
            die($message);
        }
    }

    /**
     * Diese Methode fuegt dem aktuellen Newsletter einen neuen Eintrag in der DB hinzu.
     * @return int gibt die ID des neuen Eintrages zurueck
     */
    public function add_entry() {

        $size = $this->db->prepare_and_execute("SELECT id FROM " . PREFIX . "newsletterEntries "
            . "WHERE newsletterContId=:newsletterContId;", array("newsletterContId"=>$this->newsletter_id));

        $this->db->prepare_and_execute("INSERT INTO " . PREFIX . "newsletterEntries "
            . "SET newsletterContId=:newsletterContId, headline='Neuer Eintrag', content='<p></p>', ordering=:ordering",
            array( "newsletterContId" => $this->newsletter_id, "ordering" => count( $size)));
        $rueckgabe = $this->db->get_last_insert_id();

        return( $rueckgabe);
    }

    /**
     * Diese Methode erstellt einen neuen Newsletter in der Datenbank.
     * @return int den Primaerschluessel (die ID) des neuen Newsletters in der
     * Datenbank
     */
    public static function create() {

        // existierende Templates holen
        $tpls = NewsletterTemplate::read_existing_templates();

        // Wahl des Users auslesen
        $postman = new Postman();
        $candidate = $postman->get_plaintext("template");
        if( !is_null($candidate) 
            && in_array($candidate, $tpls))
            $value = $candidate;
        else
            $value = $tpls[0][0];

        $db = new DbPdo();
        $db->prepare_and_execute( "INSERT INTO " . PREFIX . "newsletterCont SET date=:date, templatefile=:templatefile;",
            array( "date" => time(), "templatefile" => $value));
        $rueckgabe = $db->get_last_insert_id();

        return( $rueckgabe);
    }

    /**
     * Diese Methode loescht diesen Newsletter in der Datenbank. Alle Eintraege dieses
     * Newsletters werden ebenfalls geloescht
     * @return int gibt 1 zurueck
     */
    public function delete() {

        $this->db->prepare_and_execute("DELETE FROM " . PREFIX . "newsletterCont WHERE id=:newsletter_id;",
            array("newsletter_id" => $this->newsletter_id));
        $this->db->prepare_and_execute("DELETE FROM " . PREFIX . "newsletterEntries WHERE newsletterContId=:newsletterContId",
            array("newsletterContId" => $this->newsletter_id));

        return( 1);
    }

    /**
     * Diese Methode loescht den Newsletter-Eintrag der uebergebenen ID nur dann, wenn
     * er dem aktuellen Newsletter gehoert. Anschliessend stellt sie die saubere
     * Sortierung der Eintraege wieder her.
     * @param int $entry_id die ID des zu loeschenden Eintrages
     * @return int gibt die verbliebene Anzahl der Newsletter-Eintraege zurueck
     */
    public function delete_entry($entry_id) {

        // Loeschen durchfuehren
        $this->db->prepare_and_execute("DELETE FROM " . PREFIX . "newsletterEntries "
            . "WHERE id=:entry_id AND newsletterContId=:newsletter_id;",
            array("entry_id" => $entry_id, "newsletter_id" => $this->newsletter_id));

        // verbliebene Eintraege neu sortieren
        return( $this->sort_entries());
    }

    /**
     * Diese Methode verschiebt den NewsletterEntry mit der uebergebenen ID um eins
     * nach unten (in der Reihenfolge seines Auftretens in seinem Newsletter). Dabei
     * wird Ruecksicht darauf genommen, dass es danach ggf. keinen Eintrag mehr gibt.
     * @param int $entry_id die ID des Newslettereintrages
     * @return int gibt 1 bei Erfolg zurueck, sonst 0
     */
    public function entry_down($entry_id) {
        $rueckgabe = 0;

        $sql = "SELECT id, ordering FROM " . PREFIX . "newsletterEntries WHERE id=:entry_id;";
        $entry = $this->db->prepare_and_execute($sql, array("entry_id"=>$entry_id));

        $sql = "SELECT count(id) FROM " . PREFIX . "newsletterEntries WHERE newsletterContId=:newsletter_id;";
        $anzahl = $this->db->prepare_and_execute($sql, array("newsletter_id"=>$this->newsletter_id));
        $anzahl = $anzahl[0]['count(id)'];

        if (is_array($entry) && sizeof($entry) == 1 && $entry[0]['ordering'] < $anzahl) {

            $sql = "UPDATE " . PREFIX . "newsletterEntries SET ordering=:ordering_new "
                . "WHERE newsletterContId=:newsletter_id AND ordering=:ordering_old;";
            $this->db->prepare_and_execute($sql, array(
                "ordering_new" => $entry[0]['ordering'],
                "newsletter_id" => $this->newsletter_id,
                "ordering_old" => ($entry[0]['ordering'] + 1)
            ));

            $sql = "UPDATE " . PREFIX . "newsletterEntries SET ordering=:ordering "
                . "WHERE id=:entry_id;";
            $this->db->prepare_and_execute($sql, array(
                "ordering" => ($entry[0]['ordering'] + 1),
                "entry_id" => $entry[0]['id']
            ));

            $rueckgabe = 1;
        }

        return( $rueckgabe);
    }

    /**
     * Diese Methode verschiebt den NewsletterEntry mit der uebergebenen ID um eins
     * nach oben (in der Reihenfolge seines Auftretens in seinem Newsletter). Dabei
     * wird Ruecksicht darauf genommen, dass es davor ggf. keinen Eintrag mehr gibt.
     * @param int $entry_id die ID des Newslettereintrages
     * @return int gibt 1 bei Erfolg zurueck, sonst 0
     */
    public function entry_up($entry_id) {
        $rueckgabe = 0;

        $sql = "SELECT id, ordering FROM " . PREFIX . "newsletterEntries WHERE id=:entry_id;";
        $entry = $this->db->prepare_and_execute($sql, array("entry_id" => $entry_id));

        if (is_array($entry) && sizeof($entry) == 1 && $entry[0]['ordering'] > 0) {

            $sql = "UPDATE " . PREFIX . "newsletterEntries SET ordering=:ordering_new "
                . "WHERE newsletterContId=:newsletter_id AND ordering=:ordering_old;";
            $this->db->prepare_and_execute($sql, array(
                "ordering_new" => $entry[0]['ordering'],
                "newsletter_id" => $this->newsletter_id,
                "ordering_old" => ($entry[0]['ordering'] - 1)
            ));

            $sql = "UPDATE " . PREFIX . "newsletterEntries SET ordering=:ordering_new"
                . " WHERE id=:entry_id;";
            $this->db->prepare_and_execute($sql, array(
                "ordering_new" => ($entry[0]['ordering'] - 1),
                "entry_id" => $entry[0]['id']
            ));

            $rueckgabe = 1;
        }

        return( $rueckgabe);
    }

    /**
     * Diese Methode fuehrt den Versand der Newsletter-E-Mails aus.
     * @param array $receivers die Empfaenger dieses Versandprozesses
     * @return int die ID des letzten Empfaengers, dem der Newsletter
     * zugestellt wurde
     */
    protected function execute_sending($receivers) {

        // Objekt von PhpMailer (einmalig) konfigurieren
        try{
            ob_start();
            $mail = Newsletter::initMailer();

            // Emailadressen und Betreff
            $mail->addReplyTo(SENDER_ADDRESS, SENDER_NAME);
            $mail->setFrom(SENDER_ADDRESS, SENDER_NAME);
            $subject = SUBJECT;

            if (SUBJECT_DATE){
                $subject.= " " . date("d.m.Y", $this->timestamp);
            }
            $mail->Subject = $subject;
            
            $error = ob_get_contents();
            ob_end_clean();
            if( strlen( trim($error))>0){
                throw new Exception( $error);
            }

        } catch (Exception $e) {
            $this->logmessage($e->getMessage());
            return false;
        }

        // Schleife ueber all diese Empfaenger
        $anzahl = count($receivers);
        $last_receiver = 0;
        for ($i = 0; $i < $anzahl; $i++) {

            try{
                ob_start();

                // E-Mail-Adresse pruefen und (wenn keine E-Mail-Adresse) ueberspringen
                if (!filter_var($receivers[$i]['email'], FILTER_VALIDATE_EMAIL)) {
                    $last_receiver = $receivers[$i]['email_id'];
                    continue;
                }

                // Adresse dieses Empfaengers in den Mailer setzen
                $mail->clearAddresses();
                $mail->addAddress($receivers[$i]['email'], $receivers[$i]['name']);

                // Adresse dieses Empfaengers in den Mail-Text setzen
                $html_text = $this->getHtml($receivers[$i]['email']);
                $plain_text = $this->getTxt($receivers[$i]['email']);

                // Inhalte setzen
                $mail->AltBody = $plain_text;
                $mail->msgHTML($html_text);

                // Im Debug-Modus simulieren wir das Versenden nur
                if (DEBUG_MODUS > 0) {
                    $this->logmessage("Der Newsletter vom " . date("d.m.Y") . " wurde an " . $receivers[$i]['name'] . " (" . $receivers[$i]['email'] . ", UserID " . $receivers[$i]['email_id'] . ") simuliert.");
                } else {
                    // kein Debug-Modus: Versenden versuchen
                    if( !$mail->send()){
                        $this->logmessage("Der Newsletter vom " . date("d.m.Y") . " konnte nicht an " 
                            . $receivers[$i]['name'] . " (" . $receivers[$i]['email'] . ") versendet werden. Fehlermeldung: " 
                            . $mail->ErrorInfo);
                    }
                }

                $error = ob_get_contents();
                ob_end_clean();
                if( strlen( trim($error))>0){
                    throw new Exception( $error);
                }

            }catch( Exception $e){
                $this->logmessage( "Fehler beim Versand der Newsletter-eMail: ".$e->getMessage());
            }
            
            $last_receiver = $receivers[$i]['email_id'];
        }

        return( $last_receiver);
    }

    /**
     * Diese statische Methode bestimmt, in welchem Newsletter der uebergebene
     * Newsletter-Entry ist.
     * @param int $entry_id die ID des Newsletter-Eintrags
     * @return int die ID des Newsletters oder <i>null</i>
     */
    public static function get_newsletter_id_for_entry($entry_id) {
        $rueckgabe = null;

        $db = new DbPdo();
        $sql = "SELECT newsletterContId FROM " . PREFIX . "newsletterEntries WHERE id=:entry_id;";
        $erg = $db->prepare_and_execute($sql, array( "entry_id" => $entry_id));
        if (is_array($erg) && count($erg) == 1)
            $rueckgabe = $erg[0]['newsletterContId'];

        return( $rueckgabe);
    }

    /**
     * Diese Methode liefert den Newsletter als HTML-Seite aus. Dieser Newsletter ist
     * fertig, lediglich der/die Platzhalter fuer die E-Mail-Adresse des Empfaengers
     * ist noch nicht ersetzt oder entfernt.
     * @param string $receiver_mailaddress die Emailadresse des Empfaengers dieses Newsletters
     * @return string der Newsletter als HTML
     */
    public function getHtml($receiver_mailaddress = "") {
        // fast fertiges Template wieder aufnehmen oder Template erstmal aus der TemplateKlasse holen
        if (is_null($this->rendered_html_template)) {
            $template = $this->template->getHtml($this->newsletter_id, $this->entries, $this->timestamp);
            $this->rendered_html_template = $template;
        } else
            $template = $this->rendered_html_template;

        // Emailadresse einsetzen
        $template = str_replace("#####receiver_email#####", $receiver_mailaddress, $template);
        return( $template);
    }

    /**
     * Diese Methode liefert den Newsletter als Text aus. Dieser Newsletter ist
     * fertig, lediglich der/die Platzhalter fuer die E-Mail-Adresse des Empfaengers
     * ist noch nicht ersetzt oder entfernt.
     * @param string $receiver_mailaddress die Emailadresse des Empfaengers dieses Newsletters
     * @return string der Newsletter als HTML
     */
    public function getTxt($receiver_mailaddress = "") {
        // fast fertiges Template wieder aufnehmen oder Template erstmal aus der TemplateKlasse holen
        if (is_null($this->rendered_text_template)) {
            $template = $this->template->getTxt($this->newsletter_id, $this->entries, $this->timestamp);
            $this->rendered_text_template = $template;
        } else
            $template = $this->rendered_text_template;

        // Emailadresse einsetzen
        $template = str_replace("#####receiver_email#####", $receiver_mailaddress, $template);
        return( $template);
    }

    /**
     * Diese Methode initiiert die Progressbar (Fortschrittsbalken), die waehrend
     * des Versandprozesses der Newsletter angezeigt wird. Sie gibt Script-Tags
     * zurueck, die in den HTML-Head integriert werden muessen.
     * @param int $percentage der zu Beginn anzuzeigende Prozentsatz des Fortschritts
     * @return string
     */
    protected function init_progressbar($percentage) {
        return( $this->execute_view(ROOT_PATH . "include/newsletter_progressbar.view.php", array("percentage" => $percentage)));
    }

    /**
     * Diese Methode liest die aus einem Eingabeformular uebergebenen Werte eines
     * Newsletter-Eintrages aus und speichert sie in den entsprechenden Eintrag ab.
     *
     */
    public function save_entry() {
        $rueckgabe = 0;

        // Formular-Eingaben holen und fuer DB-Query aufbereiten
        $headline = $this->postman->get_plaintext('headline');

        // eingegebenen Text holen, relativen Pfad der Bilder zu absolutem Pfad der Website machen
        $text = $this->postman->get_html("content");
        $text = str_replace(ROOT_PATH, rtrim(ROOT_DOMAIN, "/") . "/", $text);

        // nur weitermachen, wenn klar ist, welcher Entry bearbeitet wird
        $entry_id = $this->postman->get_plaintext("saveEntry");
        if (!is_null($entry_id) && strlen($entry_id) > 0) {

            $this->db->prepare_and_execute("UPDATE " . PREFIX . "newsletterEntries SET headline=:headline, content=:text "
                . "WHERE id=:entry_id AND newsletterContId=:newsletter_id;", array(
                "headline" => $headline,
                "text" => $text,
                "entry_id" => $entry_id,
                "newsletter_id" => $this->newsletter_id
            ));
            $rueckgabe = 1;
        }

        return( $rueckgabe);
    }

    /**
     * Diese Methode versendet diesen Newsletter mit Hilfe von PhpMailer.
     * @param int $return_ajax gibt an, ob diese Methode die Ajax-Oberflaeche zur
     * Anzeige des FOrtschritts des Versands anzeigen soll (keine Uebergabe oder 0)
     * oder ob ein JSON-Objekt zur Aktualisierung der AJAX-Oberflaeche zurueck-
     * geben soll (1)
     * @return string/JSON
     */
    public function send($return_ajax = 0) {
        $rueckgabe = "";
        $params = array();
        $params['newsletter_id'] = $this->newsletter_id;
        $params['newsletters_sent'] = 0;
        $params['finished'] = 0;
        $params['ajax_url'] = rtrim(ROOT_DOMAIN, "/") . "/admin/send_newsletter.ajax.php?newsletter_id=" . $params['newsletter_id'];
        $last_receiver = 0;

        // ID des Empfaengers nachlesen, der diesen Newsletter zuletzt bekam
        $sql = "SELECT sent FROM " . PREFIX . "newsletterCont WHERE id=:newsletter_id;";
        $erg = $this->db->prepare_and_execute($sql, array("newsletter_id" => $this->newsletter_id));

        // alle Empfaenger lesen, die diesen Newsletter in diesem Aufruf bekommen sollen
        $db_parameters = array();
        $sql = "SELECT * FROM " . PREFIX . "newsletter WHERE aktiv=:aktiv";
        $db_parameters['aktiv'] = 1;

        if (is_array($erg) && sizeof($erg) == 1) {
            $sql.= " AND email_id>:last_email_id";
            $last_receiver = $erg[0]['sent'];
            $db_parameters['last_email_id'] = $erg[0]['sent'];
        }

        $sql.= " ORDER BY email_id ASC LIMIT ".NEWSLETTER_RECEIVERS_AT_ONE_GO.";";
        $kunden = $this->db->prepare_and_execute($sql, $db_parameters);

        // Versand der E-Mails ausfuehren
        if (sizeof($kunden) > 0)
            $last_receiver = $this->execute_sending($kunden);
        
        $params['last_receiver'] = $last_receiver;
        $params['receiver_count'] = sizeof($kunden);

        // Absolute Anzahl der bereits zugestellten Newsletter bestimmen
        $sql = "SELECT count(email) FROM " . PREFIX . "newsletter WHERE aktiv='1' AND (email_id<" . $last_receiver . " OR email_id=" . $last_receiver . ")";
        $erg = $this->db->query($sql);
        if (is_array($erg))
            $params['newsletters_sent'] = $erg[0]['count(email)'];

        // Anzahl der Empfaenger merken
        $params['receiver_count'] = 0;
        $sql = "SELECT count(email) FROM " . PREFIX . "newsletter WHERE aktiv='1'";
        $empfaenger = $this->db->query($sql);
        if (is_array($empfaenger))
            $params['receiver_count'] = $empfaenger[0]['count(email)'];

        // relative Anzahl der Empfaenger ermitteln
        if ($params['receiver_count'] > 0) {
            if ($params['newsletters_sent'] >= $params['receiver_count']) {
                $params['percentage'] = 100;
                $params['finished'] = 1;
            } else {
                $params['percentage'] = $params['newsletters_sent'] * 100 / $params['receiver_count'];
                $params['percentage'] = (int) $params['percentage'];
            }
        } else {
            // keine Empfaenger eingetragen => fertig
            $params['percentage'] = 100;
            $params['finished'] = 1;
        }

        // ID des letzten Empfaengers merken
        if (isset($last_receiver) && $last_receiver > 0) {
            $newsletter_id = $this->db->prepare_for_db($this->newsletter_id);
            $sql = "UPDATE " . PREFIX . "newsletterCont SET sent='" . $last_receiver . "' WHERE id='" . $newsletter_id . "'";
            $this->db->query($sql);
        }

        // verbleibende Zeit abschaetzen
        $sending_count = ($params['receiver_count'] - $params['newsletters_sent']) / NEWSLETTER_RECEIVERS_AT_ONE_GO;
        $params['time_remaining'] = (int) ($sending_count * (NEWSLETTER_SENDING_TIMEOUT + NEWSLETTER_RECEIVERS_AT_ONE_GO * 0.36));
        if ($params['time_remaining'] > 60) {
            $rest = ((int) $params['time_remaining'] % 60);
            if (strlen($rest) < 2)
                $rest = "0" . $rest;
            $params['time_remaining'] = "" . ((int) ($params['time_remaining'] / 60)) . ":" . $rest . "min";
        } else
            $params['time_remaining'].= "s";

        // Ausgaben produzieren
        if ($return_ajax != 1) {
            // jQuery laden
            $tags = array();
            $tags[] = "<script type=\"text/javascript\" src=\"" . ROOT_PATH . "external_scripts/jquery/jquery-1.3.2.min.js\"></script>";
            $tags[] = "<script type=\"text/javascript\" src=\"" . ROOT_PATH . "external_scripts/jqueryui/js/jquery-ui-1.7.2.custom.min.js\"></script>";
            $tags[] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . ROOT_PATH . "external_scripts/jqueryui/css/ui-lightness/jquery-ui-1.7.2.custom.css\" />";
            $tags[] = "<script type=\"text/javascript\" src=\"" . ROOT_PATH . "external_scripts/jquery.timeout_interval_idle-0.5.js\"></script>";
            $tags[] = $this->init_progressbar($params['percentage']);
            for ($i = 0; $i < sizeof($tags); $i++)
                $this->htmlhead->append_tag($tags[$i]);

            $rueckgabe = $this->execute_view(ROOT_PATH . "admin/send_newsletter.view.php", $params);
        } else {
            // Ausgabe als JSON formulieren
            $rueckgabe = json_encode($params);
        }

        // Sendepause nur im echten Fall machen
        if (defined("NEWSLETTER_SENDING_TIMEOUT") && DEBUG_MODUS == 0)
            sleep(NEWSLETTER_SENDING_TIMEOUT);
        else
            usleep(1000000);

        return( $rueckgabe);
    }

    /**
     * Diese Methode homogenisiert die Sortierung der Eintraege des aktuellen Newsletters.
     * @return int die Anzahl der Eintraege dieses Newsletters
     */
    protected function sort_entries() {
        $newsletter_id = $this->db->prepare_for_db($this->newsletter_id);
        $tmp = $this->db->query("SELECT id FROM " . PREFIX . "newsletterEntries WHERE newsletterContId='" . $newsletter_id . "' ORDER BY ordering ASC");
        for ($i = 0; $i < sizeof($tmp); $i++)
            $this->db->query("UPDATE " . PREFIX . "newsletterEntries SET ordering='" . $i . "' WHERE id='" . $tmp[$i]['id'] . "'");

        return( $i);
    }

    /**
     * Diese Methode setzt das Datum des Newsletters auf den (als UNIX-Timestamp) uebergebenen
     * Zeitpunkt.
     * @param int $timestamp ein gueltiger Unix-Timestamp, wird nichts uebergeben, so wird
     * <i>time()</i> verwendet
     * @return
     */
    public function update_timestamp($timestamp = null) {
        $rueckgabe = 0;
        if (is_null($timestamp))
            $timestamp = time();

        $newsletter_id = $this->db->prepare_for_db($this->newsletter_id);
        $timestamp = $this->db->prepare_for_db($timestamp);
        $sql = "UPDATE " . PREFIX . "newsletterCont SET date='" . $timestamp . "' WHERE id='" . $newsletter_id . "'";

        if (is_array($this->db->query($sql)))
            $rueckgabe = 1;

        return( $rueckgabe);
    }
    
    /**
     * Initialisiert ein Objekt der Klasse PhpMailer und setzt die
     * Authentifizierung des Systems ein.
     * @param int $port pass a port number here
     * @param string $security an empty string or 'tls' or 'ssl'
     * @return \PHPMailer
     * @throws Exception if PHPMailer throws an exception
     */
    public static function initMailer( $port=null, $security=null){

        /** PhpMailer einbinden */
        require_once( realpath(ROOT_PATH).'/external_scripts/phpmailer/class.phpmailer.php');
        require_once( realpath(ROOT_PATH)."/external_scripts/phpmailer/class.smtp.php");

        $mail = new PHPMailer();

        // Vorformatierungen
        $mail->setLanguage('de');
        $mail->CharSet = 'utf-8';
        $mail->clearAttachments();
        $mail->clearAddresses();

        if( defined('SMTP_HOST') && strlen( SMTP_HOST)>0){
            
            // SMTP-Autorisierung
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;

            if( defined('DEBUG_MODUS') && DEBUG_MODUS>0){
                $mail->SMTPDebug = 2;
            }

            if(!is_null( $port)){
                $mail->Port = $port;
            }elseif( defined('SMTP_PORT') && strlen(SMTP_PORT)>0){
                $mail->Port = SMTP_PORT;
            }

            if( defined('SMTP_USER') && strlen(SMTP_USER)>0
                && defined('SMTP_PASSWORD') && strlen(SMTP_PASSWORD) > 0) {

                $mail->SMTPAuth = true;
                $mail->Username = SMTP_USER;
                $mail->Password = SMTP_PASSWORD;

                if( !is_null($security)){
                    $mail->SMTPSecure = $security;
                }elseif( defined("SMTP_SECURITY") && strlen(SMTP_SECURITY)>0){
                    $mail->SMTPSecure = SMTP_SECURITY;
                }

            } else {
                $mail->SMTPAuth = false;
            }
        }else{
            $mail->isSendmail();
        }

        return $mail;
    }
}
