<?php
/**
 * Diese Datei enthaelt die Klasse FileManager.
 * @author Stefan Rank-Kunitz [at] Open-Letters anno 2009
 * @author Bernd Krüger-Knauber anno 2012
 * @package Newslettersystem
 * @subpackage System
 */

	/** Elternklasse laden */
	require_once( ROOT_PATH."framework/logable.class.php");

/**
 * Diese Klasse ermoeglicht den allgemeinen Zugriff auf Dateien.
 * @author Stefan Rank-Kunitz [at] Open-Letters anno 2009
 * @package Newslettersystem
 * @subpackage System
 */
class FileManager extends Logable
{
	/** der auf Existenz gepruefte Dateiname
	 * @var string */
	protected $filename;

	/** der gelesene Inhalt der Datei
	 * @var string */
	protected $filecontent;

	/**
	 * Der Konstruktor prueft den uebergebenen Dateinamen auf Existenz
	 * und uebernimmt ihn nur dann.
	 * @param string $filename der Name (und Pfad) der durch diesen FileManager
	 * zur bearbeitenden Datei.
	 */
	public function FileManager( $filename)
	{
		parent::__construct();

		if( !file_exists( $filename))
		{
			$this->logmessage( "Die Datei ".$filename." existiert nicht!\n");
			$this->filename = null;
		}
		else
			$this->filename = $filename;
	}

	/**
	 * Diese Methode gibt die Zeile der Datei, deren Zeilennummer uebergeben
	 * wurde zurueck.
	 * @param int $linenumber die Zeilennummer
	 * @return string der Inhalt der gesuchten Zeile oder null
	 */
	public function get_row( $linenumber)
	{
		$rueckgabe = null;

		// sicherstellen, dass die Datei schonmal gelesen wurde
		if( !is_array( $this->filecontent) || is_null( $this->filecontent))
			$count = $this->read();

		// gesuchte Zeile holen
		if( is_array( $this->filecontent)
		&& count( $this->filecontent)>=$linenumber)
			$rueckgabe = $this->filecontent[ $linenumber];

		return( $rueckgabe);
	}

    /**
     * This method can split a text file row into columns. It is useful
     * if a CSV file is read. The column seperator MUST be ;.
     * @param int $linenumber
     * @param int $colnumber
     * @return String
     */
	public function get_column( $linenumber, $colnumber)
	{
		$rueckgabe = null;
		
		$zeile = $this->get_row( $linenumber);
		if( !is_null($zeile))
		{
			$spalte = explode(";", $zeile);
			$rueckgabe = $spalte[ $colnumber];
		}
		
		return( $rueckgabe);
	}

	/**
	 * Diese Methode liest die Zeiler der Datei aus und fuellt damit
	 * das Array $this->filecontent.
	 * @return int gibt die Anzahl der gelesenen Zeilen oder null zurueck
	 */
	public function read()
	{
		$rueckgabe = null;

		if( !is_null( $this->filename))
		{
			$this->filecontent = file( $this->filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			$rueckgabe = count( $this->filecontent);
		}

		return( $rueckgabe);
	}
}