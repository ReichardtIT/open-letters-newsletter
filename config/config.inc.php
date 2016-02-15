<?php
/**
 * Dies ist die Konfigurationsdatei des Newslettersystems: Hier werden grundsaetzliche
 * Einstellungen zum System getroffen, die eilweise fuer die Funktion notwendig sind,
 * teilweise zur bequemen Administration hier gesammelt wurden.
 *
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009 nach Vorlage von Sebastian de Vries
 * @package Newslettersystem
 * @subpackage System
 */

// 1. Allgemeine Informationen der Website:
//******************************************

	/**
	 * Dies definiert den HTML-Titel (<title>-Tag) der Website. */
	define("HTML_TITLE", "Titel des Newslettersystems");

	/**
	 * Die Domain, unter der das Newslettersystem (Frontend) verfuegbar ist:
	 * Sie wird beispielsweise fuer absolute Weblinks im Newsletter verwendet. */
	define("ROOT_DOMAIN","http://www.example.com/newsletter/");

	/**
	 * Diese Option definiert die Standardsprache der Website (siehe
	 * naechste Option). */
	define("DEFAULT_LANGUAGE", "de");

	/**
	 * Diese Einstellung erlaubt dem Browser des Users, ggf. auch eine
	 * andere als die Standardsprache zu waehlen (1=ja, 0=nein).*/
	define("ALLOW_OTHER_LANGUAGE", 1);

	/**
	 * Dies ist die Liste der bekannten Sprachen (mit Semikolon getrennt):
	 * ACHTUNG: Fuer jede hier angegebene Sprache muss die Datei
	 * ./languages/Sprache.lang.php existieren! */
	define("KNOWN_LANGUAGES", "de;en");

	/**
	 * Diese Konstante definiert den Dateinamen des Templates fuer das
	 * FrontEnd: Die Dateien FRONTEND_TEMPLATE_FILENAME.html und
	 * FRONTEND_TEMPLATE_FILENAME.css muessen im Verzeichnis ./website_templates
	 * existieren.*/
	define("FRONTEND_TEMPLATE_FILENAME", "website_template_frontend_01");

	/**
	 * Diese Konstante definiert den Dateinamen des Templates fuer das
	 * FrontEnd: Die Dateien BACKEND_TEMPLATE_FILENAME.html und
	 * BACKEND_TEMPLATE_FILENAME.css muessen im Verzeichnis ./website_templates
	 * existieren.*/
	define("BACKEND_TEMPLATE_FILENAME", "website_template_backend_01");


// 2. MySQL-Datenbank-Zugangsdaten:
//**********************************

	/** URL des MySQL-Servers */
	define("DB_SERVER","localhost");

	/** Benutzername fuer den MySQL-Server */
	define("DB_USER","newsletteruser");

	/** Passwort fuer diesen Benutzer */
	define("DB_PASSWORD","newsletterpwd");

	/** Name der Datenbank */
	define("DB_NAME","newsletter");

	/**
	 * Prefix (optional): Sie haben die Moeglichkeit, mehrere Instanzen dieses Newslettersystems
	 * in einer einzigen Datenbank zu betreiben. Definieren Sie hier eine Zeichenkette, die 
	 * (abweichend von den Standardnamen der DB-Tabellen) allen Datenbank-Tabellennamen
	 * vorangestellt wurde.*/
	define("PREFIX","");


// 3. Einstellungen zum WYSIWYG-Editor TinyMCS und dessen Verarbeitung:
//**********************************************************************

	/**
	 * Alle vom Administrator eingegebenen Texte werden mit Hilfe des PHP-Programms "HtmlPurifier"
	 * validiert und bereinigt. Diese Definition legt eine Liste erlaubter HTML-Tags fest, die
	 * aus dem Text-Editor "TinyMCS" heraus vom System akzeptiert werden */
	define( "ALLOWED_HTML_TAGS", 'b,br,strong,u,i,p,div,table,thead,tbody,tr,td,th,em,span,li,ul,ol,a,hr,p,img,h1,h2,h3,h4,h5,h6,dt,dl,dd');

	/** Wie oben: Definition erlaubter HTML-Attribute */
	define('ALLOWED_HTML_ATTRIBUTES', '*.style,*.title,a.href,a.target,img.src,img.alt,img.title');


// 4. Einstellungen zur Newsletter-E-Mail:
//*****************************************

	/** Betreff der Newsletter (derzeit leider noch nicht variabel) */
	define("SUBJECT","Open-Letters.de - Newsletter");

	/** gibt an, ob das Datum an den Betreff des Newsletters angehaengt werden soll */
	define("SUBJECT_DATE", "true");

	/** Absenderaddresse des Newsletters */
	define("SENDER_ADDRESS", "sender@example.com");

	/** Name des Besitzers obiger E-Mailadresse */
	define("SENDER_NAME", "Example Newsletter Sender");

	/**
	 * Newsletter versenden 1:
	 * Anzahl der Newsletter-Empfaenger, die bei einem Sende-Ablauf
	 * den Newsletter zugestellt bekommen (geringere Anzahl=geringerer Spam-Verdacht)*/
	define("NEWSLETTER_RECEIVERS_AT_ONE_GO", 25);

	/**
	 * Newsletter versenden 2:
	 * Zeitspanne (in Sekunden), welche die AJAX-Oberflaeche zwischen 2 Sende-Vorgaengen wartet
	 * (laengere Wartezeit = geringerer Spamverdacht aber auch hoehere Gefahr eines Abbruchs
	 * des JavaScrips) */
	define("NEWSLETTER_SENDING_TIMEOUT", 5);


// 5. SMTP-Autentifizierung zum Versenden der E-Mail:
//****************************************************

	/** URL des SMTP-Servers */
	define("SMTP_HOST", "smtp.example.com");

	/** Benutzername */
	define( "SMTP_USER", "sender@example.com");

	/** Passwort */
	define( "SMTP_PASSWORD", "1234567890");

	/** Port des SMTP-Servers */
	define( "SMTP_PORT", 25);
        
        /** optionale Angabe der Verschlüsselungsmethode, mögliche Werte:
         * leerer String "" (Standardwert), "tls" oder "ssl" */
        define( "SMTP_SECURITY", "");


// 6. Debugging und Fehler-Logging (fuer Entwickler):
//****************************************************

	/** Name und Pfad der Log-Datei (schreibbar machen!) */
	define("LOGFILE", ROOT_PATH."config/open-letters.log");

	/** maximal erlaubte Laenge der Logdatei (in Byte)*/
	define("MAX_LOGFILESIZE", 256000); // 256kB

	/** 
     * Werte:
     * 0: Das System ist "scharf", Newsletter werden versendet.
     * 1: Newsletter werden nicht versendet.
     * 2: Newsletter werden nicht versendet, statt dessen wird eine Meldung auf den Bildschirm ausgegeben.
     **/
	define("DEBUG_MODUS", 0);
