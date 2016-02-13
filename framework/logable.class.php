<?php
/**
 * Diese Datei enthaelt die Klasse <i>Logable</i>.
 * @author Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */

/**
 * Diese Klasse ist als abstrakte Elternklasse all derer Klassen vorgesehen,
 * die Fehler verursachen und daher Logmessages in eine Logdatei schreiben
 * wollen.
 * @author Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */
abstract class Logable {

    /**
     * Dieser Konstruktor tut erschreckend wenig.
     */
    public function __construct() {
    }

    /**
     * Diese Methode schreibt die uebergebene Nachricht versehen mit Datum und
     * Uhrzeit an den Anfang der Logdatei des Systems. Der Name und Pfad und die
     * maximale Laenge der Logdatei koennen in der Config-Datei vorgegeben werden.
     * @return int gibt bei Erfolg 1 zurueck, sonst 0
     */
    protected function logmessage($message, $calling_function = null) {
        $rueckgabe = 0;

        // ggf. Bildschirmausgabe des Fehlers
        if (DEBUG_MODUS == 2) {
            echo "<fieldset style=\"fontweight: bold; color: red; ";
            echo "border: 1px solid red; background-color: yellow; ";
            echo "margin: 20px; padding: 10px;\">";
            echo "<legend style=\"border: 1px solid red; background-color: yellow; ";
            echo "font-weight: bold;\">Error!</legend>\n";
            echo $message . "</fieldset>\n";
        }

        // neue Logmessage in Logdatei schreiben
        if (file_exists(LOGFILE)) {
            // Logfile schreibbar?
            $perms = fileperms(LOGFILE);
            $perms = substr(decoct($perms), 3);
            $perms = $perms % 10;
            $schreibrechte = array(2, 3, 6, 7);

            if (in_array($perms, $schreibrechte)) {                
                // alten Inhalt lesen
                $old_content = file(LOGFILE);
                $old_content = implode($old_content);

                // neue Nachricht voranstellen
                $new_content = date("Y-m-d H:i:s") . " ";
                if( !is_null( $calling_function)){
                    $new_content.= $calling_function." ";
                }
                $new_content = $new_content . $message . "\n" . $old_content;
                $new_content = substr($new_content, 0, MAX_LOGFILESIZE);

                // Datei neu schreiben
                $zeiger = fopen(LOGFILE, "w+b");
                fwrite($zeiger, $new_content);
                fclose($zeiger);

                $rueckgabe = 1;
            } else {
                echo "<div style=\"fontweight: bold; color: red;\">\n"
                . "ACHTUNG: Die Logdatei " . realpath(LOGFILE)
                . " ist nicht schreibbar!</div>\n";
            }
        } else {
            echo "<div style=\"fontweight: bold; color: red;\">\n"
            . "ACHTUNG: Die Logdatei " . realpath(LOGFILE)
            . " existiert nicht!</div>\n";
        }

        return( $rueckgabe);
    }

}
