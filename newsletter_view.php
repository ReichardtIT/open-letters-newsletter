<?php
/**
 * Diese Datei enthaelt die Klasse NewsletterView.
 * @author Stefan Rank-Kunitz und Benjamin Moll
 * @package Newslettersystem
 * @subpackage System
 */
 
	/** definiert, dass wir "ganz oben" im Verzeichnisbaum sind */
	define( "ROOT_PATH", "./");

	/** Config-Datei laden */
	require_once( ROOT_PATH."config/config.inc.php");

	/** Elternklasse laden */
	require_once( ROOT_PATH."framework/parentclass.class.php");

	/** Templatemanagement fuer Newsletter laden */
	require_once( ROOT_PATH."include/newsletter.class.php");

/**
 * Diese Klasse zeigt einen Newsletter als Website an.
 * @author Stefan Rank-Kunitz und Benjamin Moll
 * @package Newslettersystem
 * @subpackage System
 */
class NewsletterView extends Parentclass
{

	/** Objekt des Nl-Templatemanagements */
	protected $newsletter;

	/**
	 * Der Konstruktor fragt nur nach einem Newsletter der uebergebenen ID
	 * ($_GET['id']) und gibt ggf. dessen HTML-Repraesentation auf den
	 * Bildschirm aus.
	 */
	public function NewsletterView()
	{
		parent::__construct();

		// uebergebene Newsletter-Id lesen und auswerten
		$id = strip_tags( $_GET['id']);

		// Newsletter-Objekt instanzierne und Ansicht holen
		$this->newsletter = new Newsletter( $id);
		echo $this->newsletter->getHtml();
	}
}

// do some basic security checks
require_once( ROOT_PATH."framework/safety_manager.class.php");
$safe = new SafetyManager();
$safe->process();

// Objekt der Klasse erzeugen und Newsletter ausgeben
$tmp = new NewsletterView();
?>