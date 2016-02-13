<?php
/**
 * Diese Datei enthaelt die Klasse <i>NewsletterTemplate</i>.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */

/** Elternklasse laden */
require_once( ROOT_PATH . "framework/parentclass.class.php");

/**
 * Diese Klasse ist das Templatemanagement fuer Newsletter: Sie liest die
 * Template-Dateien (ein HTML- und eine Textdatei) aus und setzt die uebergebenen
 * Inhalte in die Platzhalter der Templates ein.
 *
 * Das Template liegt im Verzeichnis <i>./newsletter_templates/</i>. Durch erweitern
 * des an den Konstruktor uebergebenen Template-Namens um die Endungen <i>.html</i>
 * und <i>.txt</i> werden die Namen der als Templates verwendeten Dateien gewonnen.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */
class NewsletterTemplate extends Parentclass {

    /**
     * der Inhalt der HTML-Template-Datei,
     * wird nach und nach vom nackten Template zum fertigen Newsletter umgebaut
     * @var string */
    protected $html_template;

    /**
     * der Inhalt der Text-Template-Datei
     * @var string */
    protected $text_template;

    /** definiert das Verzeichnis zur Ablage der Newsletter-Templates */
    const TEMPLATE_FOLDER = "newsletter_templates/";

    /**
     * Der Konstruktor liest nur das Template aus.
     * @param string $filename der Name des Templates
     */
    public function NewsletterTemplate($filename) {
        parent::__construct();

        // HTML-Template lesen
        $html_file = ROOT_PATH . self::TEMPLATE_FOLDER . $filename . ".html";
        if (file_exists($html_file)) {
            $tmp = file($html_file);
            $this->html_template = implode($tmp);
        }

        // Text-Template lesen
        $text_filename = ROOT_PATH . self::TEMPLATE_FOLDER . $filename . ".txt";
        if (file_exists($text_filename)) {
            $tmp = file($text_filename);
            $this->text_template = implode($tmp);
        }
    }

    /**
     * Entfernt Bilder und laesst nur den in alt="..." angegebenen Text zurueck
     * @param string $html Der Input als HTML-String
     * @return string
     */
    protected function convertIMG($html) {
        //finde alle <img xxx alt="" XXX /> und extrahiere alt.
        $muster = '/(<img|<IMG).*?\/>/';
        $altmuster = '/(?<=(alt="|ALT="))((.)*?)(?=("))/';
        preg_match_all($muster, $html, $erg);
        for ($i = 0; $i < sizeof($erg[0]); $i++) {
            preg_match_all($altmuster, $erg[0][$i], $alt);
            $alttxt = $alt[0][0];
            $html = str_replace($erg[0][$i], $alttxt, $html);
        }
        return $html;
    }

    /**
     * Wandelt <ol>-tags in Aufz�hlung 1., 2., etc. um
     * @param string $html Der Input als HTML-String
     * @return string
     */
    protected function convertOL($html) {
        //finde alle <ol>xxx</ol> und wechsele darin alle <li> nach 1., 2. etc
        $muster = '/(<ol|<OL).*?(<\/ol>|<\/OL>)/';
        $listmuster = '/(?<=(<li>|<LI>))((.)*?)(?=(<\/li>|<\/LI>))/';
        preg_match_all($muster, $html, $erg);
        for ($i = 0; $i < sizeof($erg[0]); $i++) {
            preg_match_all($listmuster, $erg[0][$i], $list);
            $listtext = "";
            for ($j = 0; $j < sizeof($list[0]); $j++) {
                $listtext.=($j + 1) . ". " . $list[0][$j] . "\n";
            }
            $listtext.="\n";
            $html = str_replace($erg[0][$i], $listtext, $html);
        }
        return $html;
    }

    /**
     * Wandelt <ul>-tags in Auflistung mit - am Anfang jedes Punktes um
     * @param string $html Der Input als HTML-String
     * @return string
     */
    protected function convertUL($html) {
        //finde alle <ul>xxx</ul> und wechsele darin alle <li> nach - etc
        $muster = '/(<ul|<UL).*?(<\/ul>|<\/UL>)/';
        $listmuster = '/(?<=(<li>|<LI>))((.)*?)(?=(<\/li>|<\/LI>))/';
        preg_match_all($muster, $html, $erg);
        for ($i = 0; $i < sizeof($erg[0]); $i++) {
            preg_match_all($listmuster, $erg[0][$i], $list);
            $listtext = "";
            for ($j = 0; $j < sizeof($list[0]); $j++) {
                $listtext.="- " . $list[0][$j] . "\n";
            }
            $listtext.="\n";
            $html = str_replace($erg[0][$i], $listtext, $html);
        }
        return $html;
    }

    /**
     * Wandelt <a XXX href='http://www.link.de' XXX >Linklabel</a> in Linklabel (http://www.link.de) um
     * @param string $html Der Input als HTML-String
     * @return string
     */
    protected function extractLinks($html) {
        //finde alle links und extrahiere sie:
        //aus "<a XXX href='http://www.link.de' XXX >Linklabel</a>
        //wird "Linklabel (http://www.link.de)"
        $muster = '/(<a|<A).*?(<\/a>|<\/A>)/';
        $linkmuster = '/(?<=href=("|\'))((.)*?)(?=("|\'))/';
        $labelmuster = '/(?<=>)(.)*?(?=<)/';
        preg_match_all($muster, $html, $erg);
        for ($i = 0; $i < sizeof($erg[0]); $i++) {
            preg_match_all($linkmuster, $erg[0][$i], $link);
            preg_match_all($labelmuster, $erg[0][$i], $label);
            $html = str_replace($erg[0][$i], trim($label[0][0]) . " (" . trim($link[0][0]) . ")", $html);
        }
        return $html;
    }

    /**
     * Diese Methode liefert den Newsletter als HTML-Text zur Anzeige als Website
     * oder zum Einfuegen in eine E-Mail. Sie ruft automatisch die Methode
     * <i>render_html()</i>, die eine Menge Platzhalter im Template durch Inhalte
     * ersetzt.
     * @return string der fertige Newsletter
     */
    public function getHtml($newsletter_id, $content_entries, $timestamp) {
        $this->render_html($newsletter_id, $content_entries, $timestamp);
        return( $this->html_template);
    }

    /**
     * Diese Methode liefert den Newsletter als Text zum Einfuegen in eine E-Mail.
     * Sie ruft automatisch die Methode <i>render_txt()</i>, die eine Menge
     * Platzhalter im Template durch Inhalte ersetzt und die verbliebenen
     * HTML-Tags zu Texten umbaut.
     * @return string der fertige Newsletter
     */
    public function getTxt($newsletter_id, $content_entries, $timestamp) {
        $this->render_txt($newsletter_id, $content_entries, $timestamp);
        return( $this->text_template);
    }

    /**
     * Diese Methode liest aus, welche Templates fuer Newsletter im Verzeichnis
     * ./newsletter_templates/ sind.
     * @return Array ein Feld von Dateinamen ohne Dateierweiterungen
     */
    public static function read_existing_templates() {
        $template_files = array();

        // Verzeichnis lesen und Dateinamen ohne Erweiterung merken
        $dir = opendir(ROOT_PATH . self::TEMPLATE_FOLDER);
        while ($file = readdir($dir)) {
            // Verzeichnisnamen ausschliessen
            if ($file != "." && $file != ".." && "readme.txt" != strtolower($file) && !is_dir($file)) {
                // Dateierweiterung abspalten
                $file = explode(".", $file);
                unset($file[sizeof($file) - 1]);
                implode(".", $file);

                if (!in_array($file[0], $template_files))
                    $template_files[] = $file[0];
            }
        }

        return( $template_files);
    }

    /**
     * Diese Methode ersetzt alle Platzhalter im HTML-Template durch Inhalte: Nur der
     * Platzhalter fuer die E-Mail-Adresse des Empfaengers bleibt bestehen, da
     * dieser erst direkt vor dem Versenden ersetzt werden soll.
     * @param int $newsletter_id die ID des Newsletters wird fuer einen Link im Newsletter gezeigt
     */
    protected function render_html($newsletter_id, $content_entries, $timestamp) {
        // Contents zusammenbauen
        $contents = "";

        $count = sizeof($content_entries);
        for ($i = 0; $i < $count; $i++) {
            $contents.= "<div class=\"newsletterentry\">\n";
            $contents.= "<h2>" . $content_entries[$i]['headline'] . "</h2>";
            $contents.= $content_entries[$i]['content'] . "</div>\n";

            if ($i + 1 < $count)
                $contents.= "<hr class=\"separator\" />\n";
        }
        $this->replace_html_template_placeholder("#####content#####", $contents);

        // andere Platzhalter ersetzen
        $html_title = $this->text->get_text("newsletter_headline_with_date") . " " . date("d.m.Y", $timestamp);
        $this->replace_html_template_placeholder("#####title#####", $html_title);

        $this->replace_html_template_placeholder("#####newsletter_id#####", $newsletter_id);
        $this->replace_html_template_placeholder("#####website_url#####", rtrim(ROOT_DOMAIN, "/"));
        $this->replace_html_template_placeholder("#####date_Y#####", date("Y", $timestamp));
        $this->replace_html_template_placeholder("#####date#####", date("d.m.Y", $timestamp));
    }

    /**
     * Diese Methode ersetzt alle Platzhalter im Txt-Template durch Inhalte: Nur der
     * Platzhalter fuer die E-Mail-Adresse des Empfaengers bleibt bestehen, da
     * dieser erst direkt vor dem Versenden ersetzt werden soll.
     * @param int $newsletter_id die ID des Newsletters wird fuer einen Link im Newsletter gezeigt
     */
    protected function render_txt($newsletter_id, $content_entries, $timestamp) {
        // Contents zusammenbauen
        $contents = "";

        $count = sizeof($content_entries);
        for ($i = 0; $i < $count; $i++) {
            // Ueberschrift mit Unterstreichung
            $contents.= "  " . $content_entries[$i]['headline'] . "\n";
            $contents.= $this->textHr(0, 4 + strlen(html_entity_decode($content_entries[$i]['headline'])), 2);

            // Text wortweise anhaengen, nach 75 Zeichen Zeilenumbruch
            $feld = explode(" ", $content_entries[$i]['content']);
            $tmp = "";
            for ($j = 0; $j < sizeof($feld); $j++) {
                if (strlen($tmp) > 75) {
                    $contents.= "  " . $tmp . "\n";
                    $tmp = "";
                }

                $tmp.= $feld[$j] . " ";
            }
            $contents.= "  " . $tmp;

            // 2 Zeilenumbrueche nach dem aktuellen Eintrag
            $contents.= "\n\n";
        }
        $this->replace_txt_template_placeholder("#####content#####", $contents);

        // andere Platzhalter ersetzen
        $html_title = $this->text->get_text("newsletter_headline_with_date") . " " . date("d.m.Y", $timestamp);
        $this->replace_txt_template_placeholder("#####title#####", $html_title);

        $this->replace_txt_template_placeholder("#####newsletter_id#####", $newsletter_id);
        $this->replace_txt_template_placeholder("#####website_url#####", rtrim(ROOT_DOMAIN, "/"));
        $this->replace_txt_template_placeholder("#####date_Y#####", date("Y", $timestamp));
        $this->replace_txt_template_placeholder("#####date#####", date("d.m.Y", $timestamp));

        $this->text_template = $this->unHtmlize($this->text_template);
    }

    /**
     * Diese Methode ersetzt einen Platzhalter im HTML-Template durch einen
     * uebergebenen Wert.
     * @param string $placeholder der zu ersetzende Template-Platzhalter, z.B. in
     * der Form #####name_des_platzhalters#####
     * @param mixed $replace der einzusetzende Wert
     * @return return int gibt 1 zurueck, wenn ein Template zum Einsetzen des
     * uebergebenen Wertes vorhanden war
     */
    public function replace_html_template_placeholder($placeholder, $replace) {
        $rueckgabe = 0;

        if (strlen($this->html_template) > 0) {
            $this->html_template = str_replace($placeholder, $replace, $this->html_template);
            $rueckgabe = 1;
        }

        return( $rueckgabe);
    }

    /**
     * Diese Methode ersetzt einen Platzhalter im HTML-Template durch einen
     * uebergebenen Wert.
     * @param string $placeholder der zu ersetzende Template-Platzhalter, z.B. in
     * der Form #####name_des_platzhalters#####
     * @param mixed $replace der einzusetzende Wert
     * @return return int gibt 1 zurueck, wenn ein Template zum Einsetzen des
     * uebergebenen Wertes vorhanden war
     */
    public function replace_txt_template_placeholder($placeholder, $replace) {
        $rueckgabe = 0;

        if (strlen($this->text_template) > 0) {
            $this->text_template = str_replace($placeholder, $replace, $this->text_template);
            $rueckgabe = 1;
        }

        return( $rueckgabe);
    }

    /**
     * Erzeugt eine Horizontale Linie aus Bindestrichen
     * @param int $nl gibt an, ob vor und nach der Linie je eine Leerzeile sein soll (1) oder nicht (sonst)
     * @param int $length gibt an, wie viele Zeichen lang die Linie sein soll
     * @param int $indent gibt die Anzahl der Zeichen an, die zu Beginn der Linie
     * durch Leerzeichen ersetzt werden sollen ( also Einrueckung der Linie)
     * @return string
     */
    protected function textHr($nl = 0, $length = 75, $indent = 0) {
        $hr = "";
        for ($i = 0; $i < $length; $i++) {
            if ($i < $indent)
                $hr.=" ";
            else
                $hr.="-";
        }

        if ($nl == 1)
            $hr = "\n" . $hr . "\n";

        return $hr . "\n";
    }

    /**
     * Wandelt HTML in Plain-Text um
     * @param string $html Der Input als HTML-String
     * @return string
     */
    protected function unHtmlize($html) {
        $html = trim($html);
        $html = str_replace("<br>", "\n  ", $html);
        $html = str_replace("<br />", "\n  ", $html);
        $html = str_replace("</p>", "\n\n", $html);
        $html = str_replace("</h1>", "\n\n", $html);
        $html = str_replace("</h2>", "\n\n", $html);
        $html = str_replace("</h3>", "\n\n", $html);
        $html = str_replace("</h4>", "\n\n", $html);
        $html = str_replace("</h5>", "\n\n", $html);
        $html = str_replace("</h6>", "\n\n", $html);
        $html = $this->extractLinks($html);
        $html = $this->convertOL($html);
        $html = $this->convertUL($html);
        $html = $this->convertIMG($html);
        $html = strip_tags($html);
        $html = html_entity_decode($html, ENT_COMPAT, 'UTF-8');
        return $html;
    }
}
