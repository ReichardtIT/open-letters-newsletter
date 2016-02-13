<?php
/**
 * Diese Datei stellt die Klasse <i>NewsletterArchive</i> bereit.
 * @author Sebastian de Vries  und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage Frontend
 */

/** Elternklasse laden */
require_once( ROOT_PATH . "framework/parentclass.class.php");

/**
 * Diese Klasse zeigt das Newsletter-Archiv, also eine Liste von Links
 * der bereits versandten Newsletter, an.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage Frontend
 */
class NewsletterArchive extends Parentclass {

    /**
     * Der Konstruktor ruft nur den Konstruktor der Elternklasse.
     */
    public function NewsletterArchive() {
        parent::__construct();
    }

    /**
     * Gibt die Links zum Inhalt des Newsletter-Archivs aus
     * @return string die fertige Liste von Links
     */
    function show() {
        $rueckgabe = "";

        $sql = "SELECT id, date FROM " . PREFIX . "newsletterCont WHERE sent>:sent ORDER BY date DESC";
        $res = $this->db->prepare_and_execute($sql, array("sent" => 0));

        if( $res && sizeof($res) > 0) {
            $rueckgabe.= "<h2>" . $this->text->get_text("newsletter_archive_headline") . "</h2>\n";

            $rueckgabe.= "<ul>\n";
            for ($i = 0; $i < sizeof($res); $i++) {
                $rueckgabe.= "<li><a class='normal' href='newsletter_view.php?id=" . $res[$i]['id'];
                $rueckgabe.= "' onclick=\"window.open(this.href); return false;\">";
                $rueckgabe.= $this->text->get_text("newsletter_headline_with_date") . " ";
                $rueckgabe.= date("d.m.Y", $res[$i]['date']) . "</a></li>\n";
            }
            $rueckgabe.= "</ul>\n";
        }
        return( $rueckgabe);
    }
}
