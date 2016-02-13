<?php
/**
 * Diese Datei enthaelt die Klasse <i>PostMan</i>.
 * @author Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */

	/** Elternklasse laden */
	require_once( ROOT_PATH."framework/logable.class.php");

/**
 * Diese Klasse vereinfach den Zugriff auf Werte aus dem Array $_POST. Sie beinhaltet
 * Methoden zum Zugriff auf Werte aus diesem Array, bereinigt nach Datentypen.
 * Ferner sind hier Methoden, die die weitere Nutzung der Werte erleichtern.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */
class PostMan extends Logable
{
	/** @var HtmlPurifier Objekt der Klasse HtmlPurifier zum Filtern von HTML */
	protected static $html_purifier;

	/** @var string Name and location of HtmlPurifier folder */
	const HTML_PURIFIER_LOCATION = "external_scripts/htmlpurifier-4.0.0-lite";

    /**
     * @var Array contains all css values that will not be deleted automatically 
     * by HtmlPurifier when saving html texts to db.
     */
    protected $allowed_css_attributes = array( "text-align", "text-decoration", "font-weight",
        "margin", "margin-left", "margin-right", "margin-top", "margin-bottom",
        "padding", "padding-left", "padding-top", "padding-bottom", "padding-right",
        "border", "border-left", "border-right", "border-top", "border-bottom",
        "float", "font-style", "background", "background-image", "background-position",
        "color");
	
	/**
	 * Der Konstruktor ruft nur nach Mami.
	 */
	public function PostMan()
	{
		parent::__construct();
	}

	/**
	 * Diese Methode holt den Wert zum uebergebenen Schluessel aus dem Array $_POST
	 * und prueft ihn auf eine valide E-Mail-Adresse. Zur Validierung der Syntax
	 * der Emailadresse wird die PHP-Filter-Extension verwendet.
	 * @param string $key der gesuchte Schluessel im $_POST-Array
	 * @return sting die valide Mailadresse oder <i>null</i>
	 */
	public function get_email( $key)
	{
		$rueckgabe = null;

		// Wert holen
		$candidate = $this->get_value( $key);
		if( !is_null( $candidate))
		{
			$candidate = strip_tags( $candidate);
			if( strlen( $candidate)>0)
			{
				if( filter_var($candidate, FILTER_VALIDATE_EMAIL))
					$rueckgabe = $candidate;
			}
		}

		return( $rueckgabe);
	}

	/**
	 * Diese Methode liefert den angeforderten Wert aus dem $_POST-Array als
	 * HTML-Text, welcher jedoch nur noch die erlaubten Tags und Attribute
	 * enthaelt, von XSS bereinigt wurde und zu validem XHTML gemacht wurde.
	 * @param string $key der Schluessel im $_POST-Array, dessen Wert geliefert
	 * werden soll
	 * @return string der gepruefte und bereinigte Wert, der angefordert wurde
	 * oder <i>null</i>
	 */
	public function get_html( $key)
	{
		$rueckgabe = null;

		if( array_key_exists( $key, $_POST) && strlen( $_POST[ $key])>0 )
		{
			$candidate = $_POST[ $key];
			if( ini_get('magic_quotes_gpc')=="1")
				$candidate = stripslashes( $candidate);
			if( strlen( $candidate)>0)
			{
				$this->init_html_purifier();
				
				$candidate = self::$html_purifier->purify( $candidate);
				if( ini_get('magic_quotes_gpc')=="1")
					$candidate = addslashes( $candidate);
				if( strlen( $candidate)>0)
					$rueckgabe = $candidate;
			}else
				$rueckgabe = $candidate;
		}

		return( $rueckgabe);
	}

	/**
	 * Diese Methode holt einen Wert aus dem $_POST-Array und liefert ihn nach
	 * Filterung durch <i>strip_tags</i> zurueck.
	 * @param string $key der Schluessel, nach dem in $_POST gesucht werden soll.
	 * @return string gibt den Wert von $_POST[ $key] zurueck oder <i>null</i>
	 */
	public function get_plaintext( $key)
	{
		$rueckgabe = null;

		// Wert holen
		$candidate = $this->get_value( $key);
		if( !is_null( $candidate))
		{
			$candidate = strip_tags( $candidate);
			if( strlen( $candidate)>0)
				$rueckgabe = $candidate;
		}

		return( $rueckgabe);
	}

	/**
	 * Diese Methode liefert den ungefilterten Wert aus dem $_POST-Array.
	 * Ihr Ergebnis sollte niemals ungeprueft weitergereicht werden, da es noch
	 * XSS oder aehnliches enthalten kann.
	 * @param string $key der Schluessel im $_POST-Array, dessen Wert geliefert
	 * werden soll
	 * @return string der Wert des $_POST-Arrays zum gegebenen Schluessel oder
	 * <i>null</i>, falls er nicht existiert
	 */
	protected function get_value( $key)
	{
		$rueckgabe = null;

		if( is_array( $_POST) && array_key_exists( $key, $_POST) && strlen( $_POST[$key])>0)
			$rueckgabe = $_POST[$key];

		return( $rueckgabe);
	}

	/**
	 * Diese Methode erzeugt das lokale Objekt der Klasse <i>HTMLPurifier, das
	 * dann von mehreren Funktionen zur Validierung von Html und CSS genutzt wird.
	 */
	protected function init_html_purifier()
	{
		if( is_null( self::$html_purifier))
		{
			require_once( ROOT_PATH.self::HTML_PURIFIER_LOCATION."/library/HTMLPurifier.auto.php");
		
			$config = HTMLPurifier_Config::createDefault();
			$config->set( 'HTML.DefinitionID', 'Open-Letters Newslettersystem,');
			$config->set( 'HTML.DefinitionRev', 1);
			$config->set( 'HTML.AllowedElements', explode(",", ALLOWED_HTML_TAGS));
			$config->set( 'HTML.AllowedAttributes', explode(",", ALLOWED_HTML_ATTRIBUTES));
			$config->set( 'CSS.AllowedProperties', $this->allowed_css_attributes);
			$def = $config->getHTMLDefinition( true);
			$def->addAttribute('a', 'target', 'Enum#_blank,_self');
			self::$html_purifier = new HTMLPurifier( $config);
		}
	}
}
?>