<?php
/**
 * Diese Datei stellt die Klasse <i>Parentclass</i> bereit.
 * @author Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */

/** ELternklasse einbinden */
require_once( ROOT_PATH . "framework/logable.class.php");

/** einbinden der Datenbankdatei */
require_once( ROOT_PATH . "framework/db_pdo.class.php");

/** Klasse Text laden um i18n zu ermoeglichen */
require_once( ROOT_PATH . "framework/text.class.php");

/** PostMan einbinden */
require_once( ROOT_PATH . "framework/post_man.class.php");

/** HtmlHead-Klasse zur Berbeitung des HTMl-Head einbinden */
require_once( ROOT_PATH . "framework/htmlhead.class.php");

/**
 * Diese Klasse ist die Oberklasse aller anderen Klassen. Sie setzt voraus,
 * dass die Config-Datei mit den DB-Zugangsdaten und dem Pfad und der max.
 * Groesse der Log-Datei bereits geladen wurde.
 * Diese Klasse bietet allen Kindern die Moeglichkeit, Logmessages abzusetzen,
 * bietet eine stehende DB-Verbindung (Attribut $db) und einen Zugriff auf
 * internationalisierte Texte (Attribut $text).
 * @author Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */
abstract class Parentclass extends Logable {

    /** @var DbPdo ein Objekt der Datenbankklasse */
    protected $db;

    /** Objekt der Klasse <i>Text</i> */
    protected $text;

    /** Objekt der Klasse <i>PostMan</i> zum Zugriff auf Werte aus $_POST */
    protected $postman;

    /** Objekt der Klasse <i>HtmlHead</i> zur Manipulation von Angaben im HtmlHead */
    protected $htmlhead;

    /**
     * Konstruktor erstellt die als Attribute verfuegbaren Objekte.
     */
    public function Parentclass() {
        $this->db = new DbPdo();
        $this->text = new Text();
        $this->postman = new PostMan();
        $this->htmlhead = new HtmlHead();
    }

    /**
     * Fuehrt einen View aus und gibt dessen Ausgaben als String zurueck.
     * @param string $file_path_and_name vollstaendiger Pfad zur View-Datei
     * @param array $params ein Feld von Daten, welches im View
     * unter dem Namen <i>$params</i> verfuegbar sein wird
     * @return string die Ausgaben des View
     */
    protected function execute_view($file_path_and_name, $params = array()) {
        $rueckgabe = "";

        // pruefen ob die Datei existiert
        if (file_exists($file_path_and_name)) {
            // Skript in einen Puffer gemantelt ausfuehren
            ob_start();
            include( $file_path_and_name);
            $rueckgabe = ob_get_contents();
            ob_end_clean();

            // Platzhaltertexte durch richtige Texte ersetzen
            $pattern = "/i18n\(([a-zA-Z_0-9]+)\)/";
            $count = preg_match_all($pattern, $rueckgabe, $treffer);
            for ($i = 0; $i < $count; $i++) {
                $rueckgabe = str_replace($treffer[0][$i], $this->text->get_text($treffer[1][$i]), $rueckgabe);
            }
        } else {
            $meldung = $this->text->get_text("error_unknown_view") . " (";
            $meldung.= $file_path_and_name . ")<br />\n";
            $this->logmessage($meldung);
        }

        return( $rueckgabe);
    }
}
