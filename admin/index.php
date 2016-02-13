<?php
/**
 * Hauptseite des Administrationsbereiches fuer das Newslettersystem.
 * In diese Seite werden die restlichen Unterseiten direkt eingebunden.
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009 nach Vorlage von Sebastian de Vries
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

        // do some basic security checks
        require_once( ROOT_PATH."framework/safety_manager.class.php");
        $safe = new SafetyManager();
        $safe->process();

	$ausgabe = "";

	// die eigentliche Arbeit der Admin-Oberflaeche macht eine ordentliche Klasse
	require_once( ROOT_PATH."admin/newsletter_administrator.class.php");
	$nladmin = new NewsletterAdministrator();
	$ausgabe.= $nladmin->show();

	// Inhalte der Website in einem Array sammeln
	$web_contents = array();
	$web_contents[ "#####content#####"] = $ausgabe;
	$web_contents[ '#####title#####'] = "Administration des Newslettersystems";
	$web_contents[ '#####website_url#####'] = ROOT_DOMAIN;
	$web_contents[ '#####date_Y#####'] = date("Y");

	// Uebergabe der Website-Inhalte an das Templatemanagement und Ausgabe
	include( ROOT_PATH."framework/template.class.php");
	$tmpl = new Template( BACKEND_TEMPLATE_FILENAME);
	echo $tmpl->show( $web_contents);
?>
