<?php
/**
 * This update adds new column "aktiv" to db table newsletter.
 */

    if( !$db->column_exists("newsletter", "double_optin_token")){
        
        try{
            $db->add_column( "newsletter", "double_optin_token", "varchar(255) NULL DEFAULT NULL");
            show_message("success", "Änderung erfolgreich!", "Die Spalte 'double_optin_token' in der DB-Tabelle 'newsletter' wurde angelegt!");
        }catch( Exception $e){
            show_message("error", "Änderung fehlgeschlagen", $e->getMessage());
            $update_fehler++;
        }
    }else{
        show_message("success", "Keine Änderung notwendig.", "Die Spalte 'double_optin_token' in der DB-Tabelle 'newsletter' existierte bereits!");
    }