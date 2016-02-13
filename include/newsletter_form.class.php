<?php
/**
 * Diese Datei stellt die Klasse <i>NewsletterForm</i>.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters anno 2009
 * @author Bernd Krüger-Knauber anno 2012
 * @package Newslettersystem
 * @subpackage Frontend
 */

/** Elternklasse laden */
require_once( ROOT_PATH . "framework/parentclass.class.php");

/** for backwards compatibility */
if (!defined("SMTP_PASSWORD") && defined("SMPT_PASSWORD")) {
    define("SMTP_PASSWORD", SMPT_PASSWORD);
}

/**
 * Diese Klasse erstellt und verarbeitet ein Newsletter-Anmelde-Formular.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage Frontend
 */
class NewsletterForm extends Parentclass {

    const NEWSLETTER_GROUP_TYPE_NEW_USERS = 1;
    const NEWSLETTER_GROUP_TYPE_UNGROUPED = 2;

    /**
     * Der Konstruktor erzeugt eine neue Formularinstanz und initialisiert die
     * Datenbankverbindung.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Prueft die eingegebenen Daten auf Vollstaendigkeit und gibt eventuelle Fehler
     * als String zurueck. Es kann also davon ausgegangen werden, dass das
     * Formular fehlerfrei ausgefuellt wurde, wenn hier ein leerer String zurueck
     * gegeben wird.
     * @return string Fehler in den Daten oder den leeren String
     */
    public function checkInput() {
        $str = "";
        if (is_null($this->postman->get_plaintext("title")))
            $str.= $this->text->get_text("form_input_error_salutation") . "<br />";

        if (is_null($this->postman->get_plaintext("firstname")))
            $str.= $this->text->get_text("form_input_error_firstname") . "<br />";

        if (is_null($this->postman->get_plaintext("name")))
            $str.= $this->text->get_text("form_input_error_name") . "<br />";

        if (is_null($this->postman->get_email("newsemail")))
            $str.= $this->text->get_text("form_input_error_email") . "<br />";

        if (is_null($this->postman->get_plaintext("newsagreement")))
            $str.= $this->text->get_text("form_input_error_newsagreement") . "<br />";

        return( $str);
    }

    /**
     * Diese Methode prüft einen übergebenen Double-Opt-In Token (nach Klick des
     * Users auf den Link in seiner E-Mail) und vollzieht ggf. die endgültige
     * Anmeldung.
     * @param String $double_optin_token der per GET übergebene Double-Opt-In-Token
     * @return String die Ausgabe Erfolgs/Fehlermeldung
     */
    protected function execute_double_optin($double_optin_token) {

        $rueckgabe = "";

        $sql = "SELECT * FROM " . PREFIX . "newsletter WHERE double_optin_token=:token;";
        $erg = $this->db->prepare_and_execute($sql, array( "token" => $double_optin_token));

        if( $erg && is_array($erg) && sizeof($erg) > 0) {

            $sql = "Update " . PREFIX . "newsletter "
                . "SET double_optin_token=NULL, aktiv=1, "
                . "updatedAt=:updatedAt "
                . "WHERE email_id=:email_id;";

            if( FALSE !== $this->db->prepare_and_execute($sql,
                array("email_id" => $erg[0]['email_id'], "updatedAt" => date('Y-m-d H:i:s')))
            ){
                $rueckgabe = $this->execute_view(ROOT_PATH . "include/newsletter_doubleoptin_success.view.php");
            }
        }else{
            $rueckgabe = $this->execute_view(ROOT_PATH . "include/newsletter_doubleoptin_error.view.php");
        }

        return( $rueckgabe);
    }

    /**
     * Diese Methode bereitet das Objekt der Klasse <i>PhpMailer</i> darauf vor, die
     * Newsletter zu versenden. Beim ersten Aufruf dieser Methode wird der Mailer
     * tatsaechlich initiiert, spaeter wird nur auf das im statischen Attribut
     * abgelegt.
     * @return PhpMailer ein Objekt der Klasse PhpMailer, dem nur der Inhalt der
     * E-Mail (HTML und Text) und der Empfaenger fehlt
     */
    protected function send_double_optin_mail($user_input) {
        $rueckgabe = null;

        try{
            ob_start();

            require_once( ROOT_PATH . "include/newsletter.class.php");
            $mail = Newsletter::initMailer();

            // Emailadressen und Betreff
            $mail->addReplyTo(SENDER_ADDRESS, SENDER_NAME);
            $mail->setFrom(SENDER_ADDRESS, SENDER_NAME);
            $mail->addAddress($user_input['newsemail'], $user_input['firstname'] . " " . $user_input['name']);

            $mail->Subject = $this->text->get_text('newsletter_subscription_opt_in_email_subject') . $_SERVER["HTTP_HOST"];

            $text = $this->text->get_text('newsletter_subscription_opt_in_email_message');
            $text = str_replace("#####host#####", $_SERVER['HTTP_HOST'], $text);
            $url = rtrim(ROOT_DOMAIN, "/") . "/index.php?doubleoptin=" . $user_input['double_optin_token'];
            $mail->AltBody = str_replace("#####url#####", $url, $text);

            $url = "<br><a href='" . $url . "'>" . $url . "</a>";
            $text = str_replace("\n", "<br>", $text);
            $text = str_replace("#####url#####", $url, $text);
            $mail->msgHTML($text);
            if ($mail->send()) $rueckgabe = true;
            
            $error = ob_get_contents();
            ob_end_clean();
            if( strlen( trim($error))>0){
                throw new Exception( "Fehler beim Versand der Double-OptIn-Mail: ".$error);
            }

        } catch (Exception $e) {
            $this->logmessage($e->getMessage());
            $rueckgabe = false;
        }

        return( $rueckgabe);
    }

    /**
     * Prueft ob Daten eingegeben wurden und ruft dementsprechend
     * die jeweils passende Funktion auf
     * @return string die Ausgaben, die bei der Anzeige und Verarbeitung des
     * Formulars anfallen
     */
    public function show() {
        $rueckgabe = "";

        if (isset($_GET['unsubscribe'])) {

            if (strlen(trim($_GET['unsubscribe'])) > 0)
                $rueckgabe.= $this->unsubscribe();
            else
                $rueckgabe.= $this->unsubscribe_form();
        } else if (isset($_GET['sendit'])) {

            $error = $this->checkInput();
            if (strlen($error) == 0)
                $rueckgabe.= $this->subscribe();
            else
                $rueckgabe.= $this->writeForm($error);
        } else if (isset($_GET['doubleoptin'])) {

            $rueckgabe = $this->execute_double_optin(trim($_GET['doubleoptin']));
        } else
            $rueckgabe.= $this->writeForm();

        return( $rueckgabe);
    }

    /**
     * Fuegt, den Angaben aus dem Formular entsprechend, einen Empfaenger
     * zum Newsletter-Verteiler hinzu
     * @param array $params ist ein Array mit den Schluesseln <ul>
     * <li><i>title</i> ist "Herr" oder "Frau"</li>
     * <li><i>firstname</i> ist der Vorname</li>
     * <li><i>name</i> ist der Nachname</li>
     * <li><i>newsemail</i> ist die E-Mail-Adresse</li></ul>. Wird dieses Array uebergeben, so wertet die
     * Methode die uebergebenen Werte statt uebergebener POST-Daten aus.
     * @return string gibt die Ausgabe fuer den User zurueck, die diesen ueber
     * Erfolg oder Misserfolg informiert
     */
    public function subscribe($params = null) {

        $rueckgabe = "";

        if (is_null($params)) {
            $params = array();
            $params['newsemail'] = $this->postman->get_email("newsemail");
            $params['firstname'] = $this->postman->get_plaintext("firstname");
            $params['name'] = $this->postman->get_plaintext("name");
            $params['title'] = $this->postman->get_plaintext("title");
        }

        // Emailadresse suchen
        $params['newsemail'] = $this->db->prepare_for_db($params['newsemail']);
        $sql = "SELECT name FROM " . PREFIX . "newsletter WHERE email=:newsemail";
        $res = $this->db->prepare_and_execute( $sql, array( "newsemail" => $params['newsemail']));

        // wenn Adresse bereits bekannt: Fehlertext anzeigen
        if( $res && sizeof($res)>0){
            $rueckgabe = "<p>" . $this->text->get_text("newsletter_subscription_not_needed") . "</p>\n";
        } else {
            
            $params['double_optin_token'] = sha1(microtime(true));

            // Mailadresse existiert noch nicht: Werte vorbereiten
            $db_parameters = array(
                'name' => $params['firstname'] . " " . $params['name'],
                'title' => $params['title'],
                'double_optin_token' => $params['double_optin_token'],
                'aktiv' => 0,
                'newsemail' => $params['newsemail'],
                'createdAt' => date('Y-m-d H:i:s'),
                'updatedAt' => date('Y-m-d H:i:s'),
            );

            if( $this->send_double_optin_mail($params)){

                // add newsletter receiver to database
                $sql1 = "INSERT INTO " . PREFIX . "newsletter SET "
                    . "email=:newsemail, name=:name, anrede=:title, "
                    . "aktiv=:aktiv, double_optin_token=:double_optin_token, "
                    . "createdAt=:createdAt, updatedAt=:updatedAt;";
                $stmt = $this->db->prepare($sql1);
                $this->db->execute($stmt, $db_parameters);

                // find group for new users
                $newUsersGroup = self::getDefaultUserGroupForNewUsers();
                if( $newUsersGroup!==FALSE){

                    // add new user to default Group
                    $new_user_id = $this->db->get_last_insert_id( 
                        array( "table"=>"newsletter", "id_column"=>"email_id"));
                    $sql2 = "INSERT INTO ".PREFIX."newsletterUserInGroup "
                        . "SET user=:user, userGroup=:userGroup, "
                        . "createdAt=:createdAt;";

                    $db_parameters = array(
                        "user" => $new_user_id, 
                        "userGroup" => $newUsersGroup,
                        "createdAt" => date("Y-m-d H:i:s"));
                    $this->db->prepare_and_execute($sql2, $db_parameters);
                }

                $rueckgabe = $this->execute_view(ROOT_PATH . "include/newsletter_registerform_success.view.php");
            }else{
                $rueckgabe = $this->execute_view(ROOT_PATH . "include/newsletter_registerform_error.view.php");
            }
        }

        return( $rueckgabe);
    }

    /**
     * Entfernt die per GET uebergebene E-Mail Adresse aus dem Newsletter-Verteiler.
     * @return void
     */
    protected function unsubscribe() {

        $rueckgabe = "";
        $mail = filter_input(INPUT_GET, 'unsubscribe');
        
        $sql = "SELECT name FROM " . PREFIX . "newsletter WHERE email=:unsubscribe;";
        $res = $this->db->prepare_and_execute($sql, array( "unsubscribe" => $mail));
        if( $res && sizeof($res)>0) {

            // Mailadresse bekannt -> austragen
            $sql = "UPDATE " . PREFIX . "newsletter SET aktiv='0',updatedAt=:updatedAt WHERE  email=:unsubscribe";
            $this->db->prepare_and_execute( $sql, array( "unsubscribe" => $mail, "updatedAt" => date("Y-m-d H:i:s")));
            $rueckgabe = "<p>" . $this->text->get_text("newsletter_unsubscribe_success") . "</p>";

        } else {

            // Mailadresse unbekannt, Eingabeformular anzeigen
            $rueckgabe = "<p>" . $this->text->get_text("newsletter_unsubscribe_address_not_found") . "</p>";
            $rueckgabe.= $this->unsubscribe_form( $_GET['unsubscribe']);

        }

        return( $rueckgabe);
    }

    /**
     * Diese Methode zeigt ein Formular zum Loeschen einer E-Mailadresse
     * aus dem Newslettersystem an.
     * @param string $mailaddress die ggf. bei einem vorherigen Versuch eingegebene Adresse
     * @return string das fertige Formular
     */
    protected function unsubscribe_form($mailaddress = "") {
        $rueckgabe = "";

        $params = array();
        $params['unsubscribe'] = "";
        if (strlen($mailaddress) > 0)
            $params['unsubscribe'] = $this->text->prepare_as_attribute_value($mailaddress);

        $rueckgabe = $this->execute_view(ROOT_PATH . "include/unsubscribe_form.view.php");
        return( $rueckgabe);
    }

    /**
     * Gibt das Newsletter-Anmeldeformular aus
     * @param string $error Gibt eventuell einzufuegende Fehlermeldungen an
     * @return string das fertige HTML-Formular
     */
    protected function writeForm() {
        $feld = array();

        // ggf. Fehlermeldung aus der letzten Runde abholen
        $params = func_get_args();
        if (isset($params[0]))
            $feld['error'] = $params[0];

        // Daten fuer View vorbereiten (die ggf in einem vorherigen Versuch eingegeben wurden)
        $feld['title'] = "";
        $tmp = $this->postman->get_plaintext("title");
        if (!is_null($tmp))
            $feld['title'] = $tmp;

        $feld['firstname'] = "";
        $tmp = $this->postman->get_plaintext("firstname");
        if (!is_null($tmp))
            $feld['firstname'] = $this->text->prepare_as_attribute_value($tmp);

        $feld['name'] = "";
        $tmp = $this->postman->get_plaintext("name");
        if (!is_null($tmp))
            $feld['name'] = $this->text->prepare_as_attribute_value($tmp);

        $feld['newsemail'] = "";
        $tmp = $this->postman->get_plaintext("newsemail");
        if (!is_null($tmp))
            $feld['newsemail'] = $this->text->prepare_as_attribute_value($tmp);

        // View ausfuehren
        return( $this->execute_view(ROOT_PATH . "include/newsletter_form.view.php", $feld));
    }
    
    public static function getDefaultUserGroupForNewUsers(){

        $db = new DbPdo();
        $sql = "SELECT * FROM ".PREFIX."newsletterGroups WHERE specialGroup=?;";
        $result = $db->prepare_and_execute($sql, array( self::NEWSLETTER_GROUP_TYPE_NEW_USERS));

        if( isset( $result[0]['id'])){
            return $result[0]['id'];
        }

        return false;
    }
}
