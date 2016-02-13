<?php
/**
 * This update adds new db tables newsletterGroups and newsletterUserInGroup and inserts default data.
 */

    // add database table newsletterGroups
    if( !$db->table_exists("newsletterGroups")){
        
        $sql = "CREATE TABLE IF NOT EXISTS ".PREFIX."newsletterGroups (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `userGroup` VARCHAR(255) NOT NULL,
            `createdAt` DATETIME NOT NULL,
            `specialGroup` int(11) NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) COLLATE = utf8_general_ci;";

        if( !is_null( $db->query( $sql))){
            show_message("success", "Änderung erfolgreich!", "Die DB-Tabelle 'newsletterGroups' wurde angelegt!");
        } else {
            show_message("error", "Änderung fehlgeschlagen", "Die DB-Tabelle 'newsletterGroups' konnte nicht angelegt werden!");
            $update_fehler++;
        }
    }else{
        show_message("success", "Keine Änderung notwendig.", "Die DB-Tabelle 'newsletterGroups' existierte schon!");
    }

    // add database table newsletterUserInGroup
    if( $update_fehler==0 && !$db->table_exists("newsletterUserInGroup")){
        
        $sql = "CREATE TABLE IF NOT EXISTS ".PREFIX."newsletterUserInGroup (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user` int(11) NOT NULL,
            `userGroup` int(11) NOT NULL,
            `createdAt` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
            ) COLLATE = utf8_general_ci;";

        if( !is_null( $db->query( $sql))){
            show_message("success", "Änderung erfolgreich!", "Die DB-Tabelle 'newsletterUserInGroup' wurde angelegt!");
        } else {
            show_message("error", "Änderung fehlgeschlagen", "Die DB-Tabelle 'newsletterUserInGroup' konnte nicht angelegt werden!");
            $update_fehler++;
        }
    }else{
        show_message("success", "Keine Änderung notwendig.", "Die DB-Tabelle 'newsletterUserInGroup' existierte schon!");
    }

    // add default user groups
    require_once( ROOT_PATH."include/newsletter_form.class.php");
    $default_user_groups = array(
        "Ungruppiert" => NewsletterForm::NEWSLETTER_GROUP_TYPE_UNGROUPED,
        "Neue Anmeldungen" => NewsletterForm::NEWSLETTER_GROUP_TYPE_NEW_USERS);
    $sql = "INSERT INTO ".PREFIX."newsletterGroups (userGroup, specialGroup, createdAt) VALUES (?, ?, ?);";
    $stmt = $db->prepare( $sql);
    foreach( $default_user_groups as $groupName => $groupType){
        $db->execute( $stmt, array($groupName, $groupType, date("Y-m-d H:i:s")));
    }

    // add ALL users to defaul user group
    $sql = "SELECT * FROM ".PREFIX."newsletter;";
    $result = $db->prepare_and_execute( $sql);
    if( $result){
        
        $sql = "INSERT INTO ".PREFIX."newsletterUserInGroup (user,userGroup,createdAt) VALUES (?,?,?);";
        $stmt = $db->prepare( $sql);
        if( $stmt){
            foreach( $result as $row){
                $db->execute( $stmt, array( $row['email_id'], 1, date("Y-m-d H:i:s")));
            }
        }
    }