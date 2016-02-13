<?php
/**
 * This update adds new column "aktiv" to db table newsletter.
 */
    if( !$db->column_exists("newsletter", "aktiv")){
        
        try{
            $db->add_column( "newsletter", "aktiv", "TINYINT(1) NOT NULL DEFAULT '0'");
            show_message("success", "Änderung erfolgreich!", "Die Spalte 'aktiv' in der DB-Tabelle 'newsletter' wurde angelegt!");
        }catch( Exception $e){
            show_message("error", "Änderung fehlgeschlagen", $e->getMessage());
            $update_fehler++;
        }
        
        $sql = "Update ".PREFIX."newsletter SET aktiv=1;";
        if( !is_null( $db->query( $sql)))
            show_message("success", "Änderung erfolgreich!", "Die bestehenden Empfänger wurden auf 'aktiv'=1 gesetzt.");
        else {
            show_message("error", "Änderung fehlgeschlagen", "Die Spalte 'aktiv' in der DB-Tabelle 'newsletter' konnte nicht befüllt werden!");
            $update_fehler++;
        }
    }else{
        show_message("success", "Keine Änderung notwendig.", "Die Spalte 'aktiv' in der DB-Tabelle 'newsletter' existierte breits!");
    }