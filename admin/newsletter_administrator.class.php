<?php
/**
 * Diese Datei enthaelt die Klasse <i>NewsletterAdministrator</i>.
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009 nach Vorlage von Sebastian de Vries
 * @package Newslettersystem
 * @subpackage Backend
 */

	/** Elternklasse laden */
	require_once( ROOT_PATH."framework/parentclass.class.php");

	/** User hinzufuegen koennen */
	require_once( ROOT_PATH."include/newsletter_form.class.php");

	/** Programmteil zur Administration der Empfaenger laden */
	require_once( ROOT_PATH."admin/newsletter_usermanagement.class.php");

	/** Programmteil zur Administration der Newsletter laden */
	require_once( ROOT_PATH."admin/newsletter_editor.class.php");

/**
 * Die Klasse <i>NewsletterAdministrator</i> ist die Top-Klasse des Adminbereiches.
 * Sie erstellt die beiden Links am Kopf des Admin-Bereiches und initiiert den
 * WYSIWYG-Editor für Textareas. Die eigentliche Administration der Empfaenger
 * der Newsletter und der Newsletter selbst passiert in den Klassen<ul>
 * <li>NewsletterUsermanagement (Bearbeitung der Empfaenger)</li>
 * <li>NewsletterEditor (Bearbeitung der Newsletter und ihrer Eintraege)</li></ul>
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009 nach Vorlage von Sebastian de Vries
 * @package Newslettersystem
 * @subpackage Backend
 */
class NewsletterAdministrator extends Parentclass
{
    const DEFAULT_ADMIN_PAGE  = "newslettermanagement";

	/**
	 * Der Konstruktor ruft nur den Konstruktor der Elternklasse.
	 */
	public function NewsletterAdministrator()
	{
		parent::__construct();
	}

	/**
	 * Diese Methode waehlt aus, was in der Admin-Oberflaeche angezeigt werden soll:
	 * Je ein Link zur <i>Verwaltung der Newsletter-Empfänger</i> und zur
	 * <i>Erstellung / Bearbeitung der Newsletter</i> oder einen der beiden
	 * Links ersetzt durch die Inhalte des entsprechenden Submoduls.
	 * @return string die fertigen Inhalte der Admin-Seite
	 */
	public function show()
	{
        $rueckgabe = $rueckgabe = $this->execute_view( ROOT_PATH."admin/newsletter_administrator.view.php", array());
		return( $rueckgabe);
	}
}
