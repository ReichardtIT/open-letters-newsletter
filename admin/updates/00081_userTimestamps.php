<?php
/**
 * This update adds new db table migrations.
 */

    if( !$db->column_exists("newsletter", "createdAt")){
        
        try{
            $db->add_column( "newsletter", "createdAt", "DATETIME NOT NULL");
            show_message("success", "Änderung erfolgreich!", "Die Spalte 'createdAt' in der DB-Tabelle 'newsletter' wurde angelegt!");
        }catch( Exception $e){
            show_message("error", "Änderung fehlgeschlagen", $e->getMessage());
            $update_fehler++;
        }
    }else{
        show_message("success", "Keine Änderung notwendig.", "Die Spalte 'createdAt' in der DB-Tabelle 'newsletter' existierte bereits!");
    }
    
    if( !$db->column_exists("newsletter", "updatedAt")){
        
        try{
            $db->add_column( "newsletter", "updatedAt", "DATETIME NOT NULL");
            show_message("success", "Änderung erfolgreich!", "Die Spalte 'updatedAt' in der DB-Tabelle 'newsletter' wurde angelegt!");
        }catch( Exception $e){
            show_message("error", "Änderung fehlgeschlagen", $e->getMessage());
            $update_fehler++;
        }
    }else{
        show_message("success", "Keine Änderung notwendig.", "Die Spalte 'updatedAt' in der DB-Tabelle 'newsletter' existierte bereits!");
    }
    
    if( $update_fehler==0){
        $sql = "SELECT * FROM ".PREFIX."newsletter WHERE createdAt='0000-00-00 00:00:00' OR updatedAt='0000-00-00 00:00:00';";
        $result = $db->prepare_and_execute( $sql);
        $now = date("Y-m-d H:i:s");
        
        $sql = "UPDATE ".PREFIX."newsletter SET createdAt=?,updatedAt=? WHERE email_id=? LIMIT 1;";
        $stmt = $db->prepare( $sql);

        foreach( $result as $line){
            $result = $db->execute( $stmt, array( $now, $now, $line['email_id']));
        }
    }