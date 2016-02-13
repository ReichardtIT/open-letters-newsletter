<?php
/**
 * Diese Datei enthaelt die Klasse <i>NewsletterEditor</i>.
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009 nach Vorlage von Sebastian de Vries
 * @package Newslettersystem
 * @subpackage Backend
 */

	/** Elternklasse laden */
	require_once( ROOT_PATH."framework/parentclass.class.php");

	/** Klasse Newsletter laden */
	require_once( ROOT_PATH."include/newsletter.class.php");

	/** Ermittlung der Templates des Systems ermoeglichen */
	require_once( ROOT_PATH."include/newsletter_template.class.php");

/**
 * Die Klasse NewsletterEditor deckt den Bereich zum Erstellen und Bearbeiten von
 * Newslettern innerhalb der Admin-Oberflaeche ab. Dabei werden viele der hier
 * anfallenden Aufgaben von der Klasse <i>Newsletter</i> ausgefuehrt.
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009 nach Vorlage von Sebastian de Vries
 * @package Newslettersystem
 * @subpackage Backend
 */
class NewsletterEditor extends Parentclass
{
	/**
	 * Der Konstruktor ruft nur den Konstruktor der Elternklasse.
	 */
	public function NewsletterEditor()
	{
		parent::__construct();
	}

	/**
	 * Diese Methode nimmt umfangreiche Pruefungen der uebergebenen GET- und POST-Parameter
	 * vor und veranlasst jede nur moegliche Veraenderung der Newsletter und ihrer
	 * Eintraege.
	 * @return string entstehende Ausgaben und Fehlermeldungen
	 */
	protected function edit()
	{
		$rueckgabe = "";

		// ggf. Newsletter loeschen
		if( array_key_exists( "deleteNewsletter", $_GET) && strlen( $_GET['deleteNewsletter'])>0)
		{
			$tmp = new Newsletter( $_GET['deleteNewsletter']);
			if( $tmp->delete())
				$rueckgabe.= $this->text->get_text( "newsletter_delete_success");
			else
				$rueckgabe.= $this->text->get_text( "newsletter_delete_error");
		}

		// neuen Newsletter erstellen
		if( array_key_exists( "newNewsletter", $_GET))
			$id = Newsletter::create();

		// fertigen Newsletter versenden
		if( array_key_exists( "sendNewsletter", $_GET) )
		{
			if( array_key_exists( "senderAddress", $_GET) && strlen($_GET['senderAddress'])>0)
			{
				// Schritt 2: Newsletter wirklich absenden
				$newsletter_id = $_GET['sendNewsletter'];
				$tmp = new Newsletter( $newsletter_id);
				$tmp->update_timestamp();
				$rueckgabe = $tmp->send();
//				$rueckgabe.= $this->text->get_text( "newsletter_send_success_01").$count."<br />";
//				$count = $this->db->query("SELECT count(email) FROM ".PREFIX."newsletter");
//				$rueckgabe.= $this->text->get_text( "newsletter_send_success_02").$count[0]['count(email)'];
			}
			else
			{
				// Schritt 1: Absender auswaehlen
				$params = explode( ";", SENDER_ADDRESS);
				$rueckgabe.= $this->execute_view( ROOT_PATH."admin/confirm_sending.view.php", $params);
			}
		}

		// neuen Eintrag zu einem Newsletter anlegen
		if( array_key_exists( "newEntry", $_GET) && array_key_exists( "edit", $_GET) && strlen( $_GET['edit'])>0)
		{
			$tmp = new Newsletter( $_GET['edit']);
			$tmp->add_entry();
		}

		// einen Eintrag loeschen
		if( array_key_exists( "deleteEntry", $_GET) && strlen( $_GET['deleteEntry'])>0 )
		{
			$tmp = new Newsletter( $_GET['edit']);
			$erg = $tmp->delete_entry( $_GET['deleteEntry']);
			$rueckgabe.= $this->text->get_text( "newsletter_entry_delete_success");
		}

		// einen bearbeiteten Eintrag speichern
		$entry_id = $this->postman->get_plaintext( "saveEntry");
		if( !is_null( $entry_id) && strlen( $entry_id)>0 )
		{
			$newsletter_id = Newsletter::get_newsletter_id_for_entry( $entry_id);
			if( !is_null( $newsletter_id))
			{
				$tmp = new Newsletter( $newsletter_id);
				if( $tmp->save_entry())
					$rueckgabe.= $this->text->get_text( "newsletter_save_entry_success");
				else
					$rueckgabe.= $this->text->get_text( "newsletter_save_entry_error");
			}
		}

		// Entry hoch verschieben
		if( array_key_exists( "entryUp", $_GET) && strlen( $_GET['entryUp'])>0)
		{
			$newsletter_id = Newsletter::get_newsletter_id_for_entry( $_GET['entryUp']);
			$tmp = new Newsletter( $newsletter_id);
			$tmp->entry_up( $_GET['entryUp']);
		}

		// Entry nach unten verschieben
		if( array_key_exists( "entryDown", $_GET) && strlen($_GET['entryDown']>0))
		{
			$newsletter_id = Newsletter::get_newsletter_id_for_entry( $_GET['entryDown']);
			$tmp = new Newsletter( $newsletter_id);
			$tmp->entry_down( $_GET['entryDown']);
		}

		return( $rueckgabe);
	}

	/**
	 * Diese Methode erstelt die Anzeige zur Bearbeitung eines Eintrages eines
	 * Newsletters mit oder ohne einen WYSIWYG-Editor.
	 * @return string das fertige Bearbeitungsformular oder eine Fehlermeldung bei
	 * nicht existierender Entry-ID im GET-Parameter "editEntry"
	 */
	protected function edit_entry()
	{
		$rueckgabe = "";

		$entry_id = $this->db->prepare_for_db( $_GET['editEntry']);
		$sql = "SELECT headline, content FROM ".PREFIX."newsletterEntries WHERE id='".$entry_id."'";
		$params = $this->db->query( $sql);

		if( count( $params)==1)
		{
			// ggf. WYSIWYG-Editor zum HTML-Head hinzufuegen
			if( !array_key_exists( "useRawHtml", $_GET) || $_GET['useRawHtml']!='true')
				if(isset($_GET['editEntry']))
				{
					// jQuery laden
					$tag = "<script type=\"text/javascript\" src=\"".ROOT_PATH."external_scripts/jquery/jquery-1.3.2.min.js\"></script>";
					$this->htmlhead->append_tag( $tag);

					// TinyMCE laden
					$this->htmlhead->append_tag( $this->init_tinymce_editor());
				}

			// View mit Bearbeitungsfenster ausfuehren
			$rueckgabe.= $this->execute_view( ROOT_PATH."admin/edit_entry.view.php", $params);
		}
		else
			$rueckgabe.= $this->text->get_error("entry_edit_unknown_id_error");

		return( $rueckgabe);
	}

	/**
	 * Diese Methode initiiert den WYSIWYG-Editor fuer die Newsletter-Erstellung.
	 * @return string alle fertigen Script-Tags, die dem HTML-Head hinzugefuegt
	 * werden muessen, damit der WYSIWYG-Editor funktioniert
	 */
	protected function init_tinymce_editor()
	{
		$rueckgabe = "";

		if(isset($_GET['useRawHtml']) && $_GET['useRawHtml']=='true')
			return( $rueckgabe);

                $rueckgabe = $this->execute_view("edit_entry_init_tinymce.view.php");
		return( $rueckgabe);
	}

	/**
	 * Diese Methode zeigt den Bereich der Admin-Oberflaeche zur Verwaltung der
	 * Newsletter (Erstellen, Bearbeiten, Loeschen) an. Zuvor prueft sie
	 * alle moeglichen uebergebenen GET-Parameter (Methode <i>edit()</i> und fuehrt
	 * damit Aktionen wie Loeschen oder Erstellen von Newslettern aus.
	 * @return string die fertige Anzeige
	 */
	public function show()
	{
		$rueckgabe = "";
		
		$params = array();
		$error = $this->edit();
		if( strlen( $error)>0)
			$rueckgabe.= "<p><font color='#f00'>".$error."</font></p><hr />";

		// 2 Anzeige-Moeglichkeiten
		if(!isset($_GET['editEntry']))
		{
			// Anzeige der vorhandenen Newsletters und ggf. auch der Eintreage eines der Newsletter
			$rueckgabe.= $this->show_overview();
		}else
			$rueckgabe.= $this->edit_entry(); // Bearbeitung eines Entrages eines Newsletters

		return( $rueckgabe);
	}

	/**
	 * Diese Methode zeigt die Liste aller vorhandenen Newsletter, den Button zum
	 * Erstellen eines neuen Newsletters und ggf. auch die Liste der in einem
	 * Newsletter vorhandenen Eintraege an.
	 * @param string $error eine ggf. mit anzuzeigende Meldung (z.B. Fehler aus
	 * der Verarbeitung der Eingaben im letzten Schritt)
	 * @return string die fertige Anzeige
	 */
	protected function show_overview()
	{
		$rueckgabe = "";

		$params = array();
		$params['newsletters'] = $this->db->query("SELECT id, date, sent FROM ".PREFIX."newsletterCont ORDER BY date DESC");

		// alle Entries zu allen Newslettern auslesen
		for( $i=0; $i<sizeof( $params['newsletters']); $i++)
		{
			$sql = "SELECT id FROM ".PREFIX."newsletterEntries WHERE newsletterContId='".$params['newsletters'][$i]['id']."'";
			$params['newsletters'][$i]['entries'] = $this->db->query( $sql);
		}

		// alle vorhandenen Templates ermitteln
		$params['templates'] = NewsletterTemplate::read_existing_templates();

		// Anzeige zur Erzeugung eines neuen Newsletters bauen
		if( !array_key_exists( "edit", $_GET) || strlen($_GET['edit'])==0 ){
			$rueckgabe.= $this->execute_view( ROOT_PATH."admin/add_newsletter.view.php", $params);
        }

		// Anzeige der existierenden Newsletters bauen
		$rueckgabe.= $this->execute_view( ROOT_PATH."admin/show_newsletters.view.php", $params);

		// einen Newsletter bearbeiten: Eintraege des Newsletters als Tabelle anzeigen
		if( array_key_exists( "edit", $_GET) && strlen($_GET['edit'])>0 )
		{
			$newsletter_id = $this->db->prepare_for_db( $_GET['edit']);
			$sql = "SELECT date,id FROM ".PREFIX."newsletterCont WHERE id='".$newsletter_id."'";
			$params['newsletter'] = $this->db->query( $sql);

			$sql = "SELECT id, headline, content, ordering FROM ".PREFIX."newsletterEntries WHERE newsletterContId='".$newsletter_id."' ORDER BY ordering ASC";
			$params['entries'] = $this->db->query( $sql);

			$rueckgabe.= $this->execute_view( ROOT_PATH."admin/edit_newsletter.view.php", $params);
		}
		return( $rueckgabe);
	}
}
?>
