<?php
/**
 * Diese Datei fuehrt ein Update der Datenbank durch, indem es alle Einzelupdates im
 * Verzeichnis ./updates/*.[sql|php] abarbeitet.
 * Achtung: Diese Datei ist nur dann erreichbar, wenn in der Datei 
 * config/config.olf.php der DEBUG_MODE auf 1 steht.
 * @author Stefan Rank-Kunitz [at] Open-Letters Webentwicklung anno 2012
 * @package Newslettersystem
 * @subpackage Updatesystem
 */
    error_reporting(E_ALL);
	$beginn = time();

    define( "ROOT_PATH", realpath("../")."/");
	$update_dir = ROOT_PATH."admin/updates/";
    
    /** zentrale Einstellungen laden */
    require_once( ROOT_PATH."config/config.inc.php");
    
    /** DB-Klasse laden */
	require_once( ROOT_PATH."framework/db_pdo.class.php");
	
	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
		<title>Open-Letters - UpdateSystem</title>
		<link rel="stylesheet" type="text/css" href="update.css" media="screen" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
		<div id="container">
			<h1>Open-Letters Updatesystem</h1>
			<div style="padding: 20px 50px;">
			Im Folgenden sehen Sie eine Auflistung der bereits durchgeführten Updates, wenn Sie dies 
			Lesen ist der eigentliche Updatevorgang also bereits vorbei. Es ist für jeden durchgeführten 
            Update-Schritt jeweils gekennzeichnet,
			ob das Update erfolgreich durchgeführt werden konnte. Sollte dies einmal nicht der Fall sein, so nehmen
			Sie bitte über <a href="http://www.open-letters.de" target="_blank">www.open-letters.de</a> Kontakt mit dem
			Open-Letters-Team auf: Wir helfen Ihnen gern weiter!</div>
			<?php
			
			$db = new DbPdo();

			$update_fehler = 0;
			$global_update_file_counter=0;
			$global_update_success_counter=0;

            $updates = get_updates_to_do( $update_dir, $db);
            $filenames = $updates['updates_to_do'];
            $unsaved_migartions_successful = array(); ?>

            <div id="bottom_box_success">
			<?php echo show_message( "success", null, count($updates['update_files'])." Updates gefunden!"); ?>
            </div>

            <div id="bottom_box_success">
			<?php echo show_message( "success", null, count($updates['migrations_done'])." früher ausgeführte Updates übersprungen!"); ?>
            </div>

            <?php
            $global_update_file_counter+= sizeof( $filenames);
            for( $local_update_file_counter=0; $local_update_file_counter<count( $filenames); $local_update_file_counter++){

                // maximale Ausfuehrungszeit sicherstellen
                $differenzzeit = time()-$beginn;
                if( $differenzzeit > 15){

                    echo "<div style=\"margin: 20px 50px;\">";
                    echo show_message( "error", "Zu lange Programm-Laufzeit!", "Um Fehler zu vermeiden wurde nach ".$differenzzeit."s kein neues Update-Script gestartet! Bitte starten Sie dieses Programm einfach neu!");
                    echo "</div>";
                    $update_fehler++;
                    break;
                }

                echo "<fieldset class=\"kasten\" style=\"margin: 20px 50px;\">";
                    echo "<legend class=\"boxtitle\" style=\"font-size: 12px;\">";
                    echo "Update ".($local_update_file_counter+1).": ";
                    echo $update_dir.$filenames[$local_update_file_counter];
                    echo " (".$differenzzeit."s)";
                    echo "</legend>\n";

                    if( preg_match("/.+\.php$/", $filenames[$local_update_file_counter])){
                        include( $update_dir.$filenames[$local_update_file_counter]);
                    }

                echo "</fieldset>";

                // Nach jedem Update die Ergebnisse sofort an den Browser senden
                flush();

                // Abbruch bei Fehlern zur Verhinderung von Folgefehlern
                if( $update_fehler>0){
                    break;
                }elseif( $db->table_exists("migrations")){
                    $sql = "INSERT INTO ".PREFIX."migrations (filename,createdAt) VALUES (?,NOW());";
                    $db->prepare_and_execute($sql, array( $filenames[$local_update_file_counter]));
                    $global_update_success_counter++;
                }else{
                    // maybe table migrations is created later in this update process
                    $unsaved_migartions_successful[] = $filenames[$local_update_file_counter];
                    $global_update_success_counter++;
                }
            }

            // for downward compatibility => post-insert successful updates if
            // migrations table was created during this process
            if( $db->table_exists("migrations") && count( $unsaved_migartions_successful)>0){
                foreach( $unsaved_migartions_successful as $file){
                    $sql = "INSERT INTO ".PREFIX."migrations (filename,createdAt) VALUES (?,NOW());";
                    $db->prepare_and_execute($sql, array( $file));
                }
            }

            // Auswertung anzeigen
			if( $update_fehler==0){
                
				echo "<div id=\"bottom_box_success\">\n";
				echo show_message( "success", null, $global_update_file_counter." Updates wurden erfolgreich ausgeführt!");
			} else {
				
                echo "<div id=\"bottom_box_error\">\n";
				$error_text = "Es sind ".$update_fehler." Fehler aufgetreten!";
				if( $update_fehler==1)
					$error_text = "Es ist ".$update_fehler." Fehler aufgetreten!";
				echo show_message( "error", null, "Es wurden ".$global_update_success_counter." von ".$global_update_file_counter." Updates bearbeitet. ".$error_text);
			}
			?>
		</div>
    </body>
</html><?php

/**
 * Hilfsfunktion zur Ausgabe
 * @param String $css_class name einer CSS-Klasse
 * @param string $headline eine H2-Überschrift oder null
 * @param string $message der Ausgabetext oder null
 */
function show_message($css_class, $headline=null, $message=null){
    echo "<div class='".$css_class."'>";

    if( !is_null( $headline))
        echo "<h2>".$headline."</h2>";
    if( !is_null( $message))
        echo $message;

    echo "</div>";
}

/**
 * Hilfsfunktion zum Auslesen aller noch "unerledigten" Updates
 * @param string $update_dir absoluter Pfad zum Updates-Verzeichnis
 * @param DbPdo $db eine offene Datenbankverbindung
 * @return array list of filenames
 */
function get_updates_to_do( $update_dir, $db){

    $filenames = array();
    
    // get all available updates from updates directory
    $update_dir_handle = opendir( $update_dir);
    if( $update_dir_handle){

        $filenames['update_files'] = array();
        while ($update_file = readdir($update_dir_handle)){

            if( $update_file!="update.css" && !is_dir( $update_file)
                && $update_file[0]!="."){
                $filenames['update_files'][] = $update_file;
            }
        }
    }
    
    unset($update_file);    
    closedir($update_dir_handle);

    // set defaults for backwards compatibility
	$filenames['migrations_done'] = array();
	$filenames['updates_to_do'] = $filenames['update_files'];

    if( $db->table_exists("migrations")){
        $sql = "SELECT * FROM ".PREFIX."migrations";
        $result = $db->prepare_and_execute($sql);
        
        $filenames['migrations_done'] = array();
        foreach( $result as $migration_done){
            $filenames['migrations_done'][] = $migration_done['filename'];
        }

        $filenames['updates_to_do'] = array_diff( $filenames['update_files'], $filenames['migrations_done']);
    }

    sort( $filenames['updates_to_do']);
    sort( $filenames['update_files']);
    sort( $filenames['migrations_done']);
    return $filenames;
}
