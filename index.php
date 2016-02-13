<?php
/**
 * Dies ist die Hautdatei des Frontends des Newslettersystems. Wie Sie sehen,
 * sind hier keine Einstellungen zum Newslettersystem moeglich. Bitte lesen
 * Sie die Datei LIESMICH.txt fuer weitere Informationen.
 *
 * @author Stefan Rank-Kunitz
 * @package Newslettersystem
 * @subpackage Frontend
 */
	error_reporting(E_ALL);

	/** definiertden Pfad zum Top-Verzeichnis der Website: notwendig
	 * fuer das Includieren der selben Klassen aus dem Backend in
	 * Subverzeichnis "admin" */
	define("ROOT_PATH", "./");

	/** Einbinden der Config-Datei: Indem diese Datei NUR HIER eingebunden
	 * wird, kann sichergestellt werden, dass ein Versuch, eine andere
	 * Datei als Programm zu starten, schreitern muss! */
	require_once( ROOT_PATH."config/config.inc.php");

        // do some basic security checks
        require_once( ROOT_PATH."framework/safety_manager.class.php");
        $safe = new SafetyManager();
        $safe->process();
        
	$ausgabe = "";
	$ausgabe.= "<hr />\n";

	// Formular zur Anmeldung/Abmeldung erzeugen
	include( ROOT_PATH."include/newsletter_form.class.php");
	$newsletterFrm = new NewsletterForm();
	$ausgabe.= $newsletterFrm->show();
	$ausgabe.= "<br /><hr />\n";

	// Newsletter-Archiv auflisten lassen
	include( ROOT_PATH."include/newsletter_archive.inc.php");
	$newsletter=new  NewsletterArchive();
	$ausgabe.= $newsletter->show();

	// alle Inhalte der Website in einem Array sammeln: An dieser Stelle werden
	// Ausgabeinhalte ihren Template-Platzhaltern zugewiesen!
	$web_contents = array();
	$web_contents[ '#####content#####'] = $ausgabe; // Hauptinhalte
	$web_contents[ '#####website_url#####'] = ROOT_DOMAIN;	// URL dieser Website
	$web_contents[ '#####date_Y#####'] = date("Y");	// Datum, Jahresangabe

	// Uebergabe der Website-Inhalte an das Templatemanagement und Ausgabe
	include( ROOT_PATH."framework/template.class.php");
	$tmpl = new Template( FRONTEND_TEMPLATE_FILENAME);
	echo $tmpl->show( $web_contents);
?>
