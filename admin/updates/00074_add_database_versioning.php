<?php
/**
 * This update adds new db table migrations.
 */

    if( !$db->table_exists("migrations")){
        
        $sql = "CREATE TABLE IF NOT EXISTS ".PREFIX."migrations (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `filename` VARCHAR(255) NOT NULL,
            `createdAt` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
            ) COLLATE = utf8_general_ci;";

        if( !is_null( $db->query( $sql)))
            show_message("success", "Änderung erfolgreich!", "Die DB-Tabelle 'migrations' wurde angelegt!");
        else {
            show_message("error", "Änderung fehlgeschlagen", "Die DB-Tabelle 'migrations' konnte nicht angelegt werden!");
            $update_fehler++;
        }
    }else{
        show_message("success", "Keine Änderung notwendig.", "Die DB-Tabelle 'migrations' existierte schon!");
    }