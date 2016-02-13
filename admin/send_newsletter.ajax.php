<?php
/**
 * Diese Datei ist ein PhpSkript, welches den Versand weiterer Newsletter starten
 * kann. Dieses Skript wird von der Ajax-Oberflaeche, die den Versandprozess
 * anzeigt, gestartet.
 * @author Stefan Rank-Kunitz [at] Open-Letters anno 2009
 * @package Newslettersystem
 * @subpackage Backend
 */
	error_reporting(E_ALL);

	/** definiert, dass alle Dateien aus Sicht eines Unterverzeichnisses (hier "admin")
	 * eingebunden werden */
	define( "ROOT_PATH", "../");

	/** Laden der Config-Datei NUR hier, damit eine andere Datei nicht als Programm
	 * funktionieren kann (weil sie z.B. die DB-Zugangsdaten nicht kennt).*/
	require_once( ROOT_PATH."config/config.inc.php");

	// Newsletterklasse laden und Newsletter-ID holen
	require_once( ROOT_PATH."include/newsletter.class.php");

	// erneutes Senden anstossen
	$nl = new Newsletter( $_GET['newsletter_id']);
	$json_ausgabe = $nl->send( 1);

	// Ausgabe
	echo $json_ausgabe;
?>