<?php
/**
 * Diese Datei enthaelt die Klasse <i>Text</i>.
 * @author Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */

	/** Elternklasse laden */
	require_once( ROOT_PATH."framework/logable.class.php");

/**
 * Diese Klasse ermoeglicht einen zentralen Zugriff auf auszugebende Texte. Dies
 * dient einerseits der Internationalisierung, andererseits auch alle anderen
 * Aufgaben, die eine solche Klasse uebernehmen koennte (z.B. Ver- und
 * Entschluesselung).
 * @author Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */
class Text extends Logable
{
	/** Feld mit den Texten der Standardsprache der Website */
	protected $language_entries;

	/** Name der Sprache, die aktuell ist */
	protected $language_name;

	/** definiert den Namen des Verzeichnisses, in dem die Sprachdateien liegen */
	const LANGUAGE_FOLDER = "languages";

	/**
	 * Der Konstruktor stellt die aktuelle Sprache fest und laed die entsprechende
	 * Sprach-Datei aus dem Verzeichnis ./languages.
	 */
	public function Text()
	{
		parent::__construct();
		
		$this->language_name = DEFAULT_LANGUAGE;

		// nachsehen, ob der Browser eine andere Sprache will und ob das erlaubt ist
		if( ALLOW_OTHER_LANGUAGE==1
			&& array_key_exists("lang", $_GET)) 
        {
            $known_langs = explode(";", KNOWN_LANGUAGES);
            $candidate = strip_tags($_GET['lang']);
			if( in_array( $candidate, $known_langs))
				$this->language_name = $candidate; // Sprache annehmen
        }elseif( ALLOW_OTHER_LANGUAGE==1
			&& is_array( $_SERVER)
			&& array_key_exists("HTTP_ACCEPT_LANGUAGE", $_SERVER)
			&& strlen( $_SERVER['HTTP_ACCEPT_LANGUAGE'])>0)
		{
			// nachgucken, was der Browser will und ob diese Sprache bekannt ist
			$candidate = substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			$known_langs = explode(";", KNOWN_LANGUAGES);
			if( in_array( $candidate, $known_langs))
				$this->language_name = $candidate; // Sprache annehmen
        }

		// Sprachdatei laden
		$filename = ROOT_PATH.self::LANGUAGE_FOLDER."/".$this->language_name.".lang.php";
		if( file_exists( $filename))
		{
			include $filename;
			$this->language_entries = $lang;
		}else
		{
			$meldung = "Fehler in der Textklasse: Das Languagefile ".$filename;
			$meldung.= " für die Sprache ".$this->language_name." wurde nicht gefunden!";
			$this->logmessage( $meldung);
		}
	}

	/**
	 * Diese Methode liefert, wie auch <i>get_text()</i>, einen internationalen
	 * Text zum uebergebenen Identifier. Zusaetzlich wird der Text hier aber
	 * noch zu einem Fehler gemacht (optisch, z.B. rote Textfarbe).
	 * @param string $textname der Identifier zum gesuchten Text (Platzhalter)
	 * @return der fertige Text
	 */
	public function get_error( $textname)
	{
		$text = $this->get_text($textname);

		$rueckgabe = "<h1>".$this->get_text('error')."!</h1>\n";
		$rueckgabe.= "<div style=\"margin: 20px; padding: 10px; border: 1px solid red; color: red;";
		$rueckgabe.= "background-color: yellow;\">".$text."</div>\n";
		return( $rueckgabe);
	}

	/**
	 * Diese Methode gibt einen Text zurueck.
	 * @param string $textname der Identifier des Textes im Languagefile
	 * @return string der Text aus dem Languagefile
	 */
	public function get_text( $textname)
	{
		$rueckgabe = "";

		// existieren Spracheintraege ?
		if( strlen( $textname)>0 && is_array( $this->language_entries) && sizeof( $this->language_entries)>0)
		{
			// gesuchten Eintrag raussuchen
			if( array_key_exists( $textname, $this->language_entries))
				$rueckgabe = $this->language_entries[ $textname];
			else 
			{
				// gesuchter Eintrag ex.. nicht in aktueller Sprache: Fehlermeldung
				$this->logmessage( "Der Text <strong>".$textname."</strong> wurde in der Sprache <strong>".$this->language_name."</strong> nicht gefunden!");

				// Versuch, den Text in Default-Language zu finden
				if( $this->language_name != DEFAULT_LANGUAGE)
				{
					// Sprache laden und Text suchen
					$filename = ROOT_PATH.self::LANGUAGE_FOLDER."/".DEFAULT_LANGUAGE.".lang.php";
					include( $filename);
					if( is_array( $lang) && array_key_exists( $textname, $lang))
						$rueckgabe = $lang[ $textname];
					else
						$this->logmessage( "Der Text <strong>".$textname."</strong> wurde auch in der Standardsprache <strong>".DEFAULT_LANGUAGE."</strong> nicht gefunden!");
				}else
					$this->logmessage( "Der Text <strong>".$textname."</strong> wurde nicht in gefunden!");
			}
		}else
			$this->logmessage( "Der Text zum Identifier <i>".$textname."</i> kann nicht angezeigt werden!");

		return( $rueckgabe);
	}

	/**
	 * Diese Methode bereitet einen uebergebenen Text darauf vor, in der Website angezeigt
	 * zu werden. Das kann beispielsweise dann sinnvoll sein, wenn ein Wert
	 * per Formular eingegeben wurde und nun, wegen Fehlern, erneut in dem Formular
	 * angezeigt werden soll. Dann ist sicher zu stellen, dass der Wert keine
	 * Steuerzeichen enthaelt, die das value-Feld vorzeitig schließen und damit
	 * HTML einfuegen koennen.
	 * @param string $raw_text der zu bereinigende Text
	 * @return string der bereinigte Text
	 */
	public function prepare_as_attribute_value( $raw_text)
	{
		$rueckgabe = $rueckgabe = htmlspecialchars( $raw_text, ENT_QUOTES, "UTF-8");
		return( $rueckgabe);
	}
}
?>