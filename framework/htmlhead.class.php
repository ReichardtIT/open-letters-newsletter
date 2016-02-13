<?php
/**
 * Diese Datei enthaelt die Klasse <i>HtmlHead</i>.
 * @author Stefan Rank-Kunitz [at] Open-Letters Webentwicklung anno 2009
 * @package Newslettersystem
 * @subpackage System
 */

/** Elternklasse laden */
require_once( ROOT_PATH . "framework/logable.class.php");

/**
 * Die Klasse HtmlHead stellt allen anderen Klassen eine Schnittstelle zur Bearbeitung
 * der im HTML-Head der Website angezeigten Informationen. Sie ermoeglicht die
 * Manipulation des Website-Title und das Hinzufuegen von Script- und Style-Tags.
 * @author Stefan Rank-Kunitz [at] Open-Letters Webentwicklung anno 2009
 * @package Newslettersystem
 * @subpackage System
 */
class HtmlHead extends Logable {

    /** ein Feld von Html-Tags, fertig zur Ausgabe im HTML-HEAD
     * @var string[] */
    public static $tags;

    /** eine Zeichenkette zur Anzeige im Title-Tag des HTML-HEAD, per Default
     * wird der Wert der Konstanten HTML_TITLE (siehe config.inc.php) verwendet
     * @var string
     */
    public static $title;

    /**
     * Der Konstruktor initiiert nur die Attribute des Objektes.
     */
    public function HtmlHead() {
        parent::__construct();

        if (is_null(self::$tags) || !is_array(self::$tags))
            self::$tags = array();

        if (is_null(self::$title) || strlen(self::$title) == 0)
            self::$title = HTML_TITLE;
    }

    /**
     * Diese Methode teilt dem HtmlHead mit, dass der uebergebene Html-Tag mit im
     * Head der Website erscheinen soll. Dieser Tag wird dabei allen schon
     * vorhandenen Tags hinten angestellt. Diese Methode nimmt keine Html-Validierung
     * des uebergebenen Strings vor.
     * @param string $tag ein fertiger (nicht leerer) Html-Head-Tag zum Einfuegen in den Head
     * @return int die Anzahl der Tags, die dem Head schon hinzugefuegt wurden
     */
    public function append_tag($tag) {
        if (!is_array(self::$tags))
            self::$tags = array();

        if (strlen($tag) > 0)
            self::$tags[] = $tag;

        return( sizeof(self::$tags));
    }

    /**
     * Diese Methode haengt den uebergebenen String nach einem <i>strip_tags</i> an
     * den aktuellen HTML-HEAD-Title an.
     * @param string $title_appendix eine nicht leere Zeichenkette
     * @return string aktuelle HTML-Head-Title der Website
     */
    public function append_to_title($title_appendix) {
        $rueckgabe = self::$title;
        if (strlen($title_appendix) > 0) {
            self::$title.= strip_tags($title_appendix);
            $rueckgabe = $rueckgabe = self::$title;
        }

        return( $rueckgabe);
    }

    /**
     * Diese Methode fuehrt alle bei dieser Klasse angemeldeten JavaScript-Tags
     * zu einem String zusammen und gibt diesen zurueck.
     * @return string die fertigen HTML-Head-Tags oder ein leerer String
     */
    public function get_tags() {
        $rueckgabe = "";

        if (is_array(self::$tags) && sizeof(self::$tags) > 0)
            $rueckgabe = implode("\n", self::$tags);

        return( $rueckgabe);
    }

    /**
     * Diese Methode liefert den aktuellen HTML-Head-Titel der Website aus.
     * @return string
     */
    public function get_title() {
        return( self::$title);
    }

    /**
     * Diese Methode teilt die HtmlHead der Website mit, dass der uebergebene
     * String ebenfalls im Head der Website ausgegeben werden soll. Der uebergebene
     * Tag wird dabei den vorhandenen Tags vorangestellt. Diese Methode fuehrt dabei
     * keine Html-Validierung des uebergebenen Strings durch. Sie erkennt aber,
     * wenn ein (wirklich) identischer Tag bereits existiert.
     * @param string $html_tag ein fertiger (nicht leerer) HtmlTag zur Ausgabe
     * im Head der Website
     * @return int die Anzahl der bereits hinzugefuegten HtmlTag-Angaben
     */
    public function prepend_tag($html_tag) {
        if (!is_array(self::$tags))
            self::$tags = array();

        // fuer leere oder bekannte HtmlTags machen wir keine Arbeit
        if (strlen($html_tag) > 0 && !in_array($html_tag, self::$tags)) {
            // neues Array mit uebergebenem Wert anlegen
            $tmp = array($html_tag);

            // alle alten Werte hinzufuegen
            for ($i = 0; $i < sizeof(self::$tags); $i++)
                $tmp[] = self::$tags[$i];

            // altes Array ueberschreiben
            self::$tags = $tmp;
        }

        return( sizeof(self::$tags));
    }

    /**
     * Diese Methode stellt den uebergebenen String dem aktuellen HTML-Head-Title
     * nach einem <i>strip_tags()</i> voran.
     * @param string $title_suffix eine nicht leere Zeichenkette
     * @return der aktuelle HTML-Head-Title
     */
    public function prepend_to_title($title_suffix) {
        $rueckgabe = self::$title;
        if (strlen($title_appendix) > 0) {
            self::$title = strip_tags($title_appendix) . $rueckgabe;
            $rueckgabe = $rueckgabe = self::$title;
        }

        return( $rueckgabe);
    }

    /**
     * Diese Methode ueberschreibt den aktuellen HTML-HEAD-Title mit dem uebergebenen
     * Wert. Dies geschieht nur, wenn der uebergebene String nicht leer ist und
     * auch nur nach einem <i>strip_tags()</i>.
     * @param string $new_title der neu zu setzende Title
     * @return string der HTML-Title der Website (sollte gleich dem uebergebenen Wert sein)
     */
    public function set_title($new_title) {
        $rueckgabe = self::$title;
        if (strlen($new_title) > 0) {
            self::$title = strip_tags($new_title);
            $rueckgabe = self::$title;
        }

        return( $rueckgabe);
    }

}
