<?php

/**
 * Diese Datei enthaelt die Klasse <i>Template</i>.
 * @author Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */
/** Elternklasse laden */
require_once( ROOT_PATH . "framework/parentclass.class.php");

/**
 * Diese Klasse ermoeglicht einer Website die Verwendung eines ordentlichen Templates,
 * d.h. eines HTML-Datei (Template) mit Platzhaltertexten drin. Diese Datei wird hier
 * geladen und verarbeitet, dass heißt, die Platzhalter werden durch Inhalte der Website
 * ersetzt.
 * @author Stefan Rank-Kunitz at Open-Letters.de anno 2010
 * @package Newslettersystem
 * @subpackage System
 */
class Template extends Parentclass {

    /** der Inhalt der Template-Datei */
    protected $template;

    /** der Name der Template-Datei */
    protected $template_name;

    /** Verzeichnis, welches die Templates enthaelt */
    const TEMPLATE_FOLDER = "website_templates/";

    /**
     * Der Konstruktor laed die Template-Html-Datei oder gibt eine Fehlermeldung aus.
     * @param string $template_name Name der Template-Datei, also der Teil des Dateinamens
     * ohne Pfad (es wird standardmaessig "./website_templates/" angenommen) und ohne
     * Dateierweiterung.
     */
    public function Template($template_name) {
        parent::__construct();
        if (file_exists(ROOT_PATH . self::TEMPLATE_FOLDER . $template_name . ".html")) {
            $this->template_name = $template_name;
            $this->template = file(ROOT_PATH . self::TEMPLATE_FOLDER . $template_name . ".html");
            $this->template = implode($this->template);
        } else {
            $this->logmessage("Das an die Template-Klasse übergebene Template <strong>"
                    . ROOT_PATH . self::TEMPLATE_FOLDER . $template_name . "</strong> existiert nicht!");
        }
    }

    /**
     * Diese Methode ersetzt die Platzhalter im Template durch die uebergebenen Website-
     * Inhalte und gibt die fertige Website als String zurueck. Sie laed automatisch die
     * zum uebergebenen Templatenamen gehoerende CSS-Datei (wenn vorhanden).
     * @param Array $website_entries ein assoziatives Array, welches jedem Platzhalter
     * des Templates einen String zuweist (am besten sauberes HTML), der statt des
     * Platzhalters in der Website landen soll
     * @return String die fertig gerenderte Website
     */
    public function show($website_entries) {
        $rueckgabe = $this->template;

        $keys = array_keys($website_entries);
        $count = count($keys);

        for ($i = 0; $i < $count; $i++)
            $rueckgabe = str_replace($keys[$i], $website_entries[$keys[$i]], $rueckgabe);

        // Title der Website einsetzen
        $rueckgabe = str_replace("#####title#####", $this->htmlhead->get_title(), $rueckgabe);

        // Zum Haupttemplate gehoerende CSS-Datei zum HtmlHead hinzufuegen
        if (file_exists(ROOT_PATH . self::TEMPLATE_FOLDER . $this->template_name . ".css")) {
            $file_url = ROOT_PATH . self::TEMPLATE_FOLDER . $this->template_name . ".css";
            $file_url = ltrim($file_url, "./");
            $main_css_file = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . rtrim(ROOT_DOMAIN, "/") . "/";
            $main_css_file.= $file_url . "\" />";
            $this->htmlhead->append_tag($main_css_file);
        }

        // JavaScript- und CSS-Tags aus der HTML-Head-Klasse holen und einsetzen
        $tmp = $this->htmlhead->get_tags();
        $rueckgabe = str_replace("#####html_head_entries#####", $tmp, $rueckgabe);

        return( $rueckgabe);
    }
}
