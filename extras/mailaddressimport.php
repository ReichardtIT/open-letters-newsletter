<?php
/**
 * Dieses PHP-Skript erlaubt den Import von Email-Adressen aus einer Text-Datei. Dabei wird
 * angenommen, dass
 *	- dieses PHP-Skript im Top-Verzeichnis des Newslettersystems liegt
 *  - eine Datei "addresses.txt" daneben liegt
 *	- die Datei addresses.txt genau eine E-Mail-Adresse je zeile enthaelt
 * Dieses Skript ist darüber hinaus in der Lage, einigen Müll um die E-Mail-Adresse herum zu
 * entfernen, bevor versucht wird, diese als Mailadresse zu verstehen.
 * @author Stefan Rank-Kunitz [at] Open-Letters anno 2009
 * @author Bernd Krüger-Knauber anno 2012
 */

	define("ROOT_PATH", "./");

	require_once( ROOT_PATH."config/config.inc.php");
	require_once( ROOT_PATH."framework/db.class.php");
	require_once("./framework/filemanager.class.php");
	$db = new Db();

	//
	if( !is_array( $_GET) || !array_key_exists("execute", $_GET))
		echo "Zum Eintragen in die Db das Skript mit mailaddressimport.php?execute aufrufen!<br>\n";
	else
	{
		echo "Bedeutung: <ul><li>. bedeutet erfolgreichen Eintrag einer E-Mailadresse";
		echo "</li><li>- bedeutet Ablehnung einer E-Mailadresse weil sie bereits eingetragen ist";
		echo "</li></ul>";
	}
	$fm = new FileManager("./addresses.txt");
	$anzahl = $fm->read();
	echo "Es wurden ".$anzahl." Zeilen aus der Datei gelesen!<br>\n";
	echo "************************************************************************************<br>\n";

	$unlesbar = 0; $duplikate=0; $empty_lines=0;
	for( $i=0; $i<$anzahl; $i++)
	{
		$candidate = $fm->get_row( $i);

		// Zeile bereinigen
		$candidate = strip_tags( $candidate);
		$candidate = strtolower( $candidate);
		$candidate = trim( $candidate);

		// oeffnende und schliessende Klassern entfernen
		if( substr( $candidate, 0, 1)=="(")
			$candidate = substr( $candidate, 1, strlen($candidate)-1 );
		if( substr( $candidate, strlen($candidate)-1, 1)==")")
			$candidate = substr( $candidate, 0, strlen($candidate)-1 );

		// Emailadresse aus irgendwelchem Muell bergen
		$patterns = array( "/\[(.*?@.*)\]/",
			"/\((.*?@.*)/",
			"/\[(.*?@.*)/",
			"/(.*?@.*)\)/",
			"/e-mail: (.*?@.*)/",
			"/mail: (.*?@.*)/",
			"/.*\s(.*?@.*)/");
		for( $j=0; $j<sizeof( $patterns); $j++)
		{
			if( preg_match( $patterns[$j], $candidate, $founds))
			{
				$candidate = $founds[1];
				break;
			}
		}

		// schliessende eckige Klammern und Semikoli entfernen
		if( substr( $candidate, strlen($candidate)-1, 1)=="]")
			$candidate = substr( $candidate, 0, strlen($candidate)-1 );
		if( substr( $candidate, strlen($candidate)-1, 1)==";")
			$candidate = substr( $candidate, 0, strlen($candidate)-1 );

		// Eintragen in die Db versuchen
		$candidate = trim( $candidate);
		if( strlen( $candidate)>0)
		{
			// valide E-Mail?
			if( !filter_var($candidate, FILTER_VALIDATE_EMAIL))
			{
				// Fehler ausgeben
				echo "<br>Zeile ".$i." nicht verstanden: ".$candidate."<br>\n";
				$unlesbar++;
			} else {
				// Eintrag in Db
				$email = $db->prepare_for_db( $candidate);
				if( is_array( $_GET) && array_key_exists("execute", $_GET)){

					$erg = $db->query( "INSERT INTO ".PREFIX."newsletter SET email='".$email."'", 1);
					if(!is_null( $erg))
					{
						echo "+";
					}
					else
					{
						$duplikate++;
						echo "-";
					}
				}
				echo $email."<br>\n";
			}
		}
		else
			$empty_lines++;
		
		if( $i > 1 && $i % 50==0)
			echo "<br>\n";
	}
	
	if( is_array( $_GET) && array_key_exists("execute", $_GET))
	{
		echo "<br>".$unlesbar." Zeilen konnten nicht verarbeitet werden.";
		echo "<br>".$duplikate." Zeilen waren bereits in der Db vorhanden.";
		echo "<br>".$empty_lines." Zeilen waren leer.";
	}