<?php
/**
 * Diese Datei enthaelt den View, der die zu einem Newsletter vorhandenen Eintraege
 * (Entries) als Tabelle anzeigt. Dabei gibt es einen Button zum Anlegen eines
 * neuen Entry und zu allen vorhandenen Entries Aktionsbuttons.
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009 nach Vorlage von Sebastian de Vries
 * @package Newslettersystem
 * @subpackage Backend
 */
	echo("<br /><hr />\n");
	echo("<h2>i18n(newsletter_headline_with_date) ".date("d.m.Y", $params['newsletter'][0]['date'])." i18n(edit)</h2>\n");

	// Button fuer neuen Entry
	echo("<form method='get' action='".basename($_SERVER['PHP_SELF'])."'>\n");
	echo("<input type=\"hidden\" name=\"page\" value=\"".$_GET['page']."\">\n");
	echo("<input type=\"hidden\" name=\"edit\" value=\"".$params['newsletter'][0]['id']."\">\n");
	echo("<input type=\"hidden\" name=\"newEntry\" value=\"true\">\n");
	echo("<input type='submit' value='i18n(create_entry_button)' />\n");
	echo("</form>\n");

	// Tabelle der vorhandenen Entries
	if( sizeof( $params['entries'])>0)
	{ ?>
		<br />
		i18n(existing_entries_headline)

		<?php
		// Schleife ueber alle Eitraege dieses Newsletters
		echo("<table border='1' width='100%'>\n");
		echo("<tr><th>i18n(entry_headline)</th><th>&nbsp;</th></tr>\n");
		for( $i=0; $i<sizeof( $params['entries']); $i++)
		{
			// Ueberschrift des Newsletters
			echo("<tr><td>".$params['entries'][$i]['headline']."</td>\n");

			// Bearbeitungs-Link
			echo("<td><a href='".basename($_SERVER['PHP_SELF'])."?page=".$_GET['page']."&edit=");
			echo( $params['newsletter'][0]['id']."&editEntry=".$params['entries'][$i]['id']."'>i18n(entry_edit)</a>\n");

			// Loeschen-Link
			echo("<a href='".basename($_SERVER['PHP_SELF'])."?page=".$_GET['page']."&edit=".$params['newsletter'][0]['id']);
			echo( "&deleteEntry=".$params['entries'][$i]['id']."' ");
			echo( "onclick='return confirm(\"i18n(entry_confirm_delete)\")'>i18n(entry_delete)</a>\n");

			// Button zum Hochschieben
			if( $params['entries'][$i]['ordering'] > 0)
			{
				echo("<a href='".basename($_SERVER['PHP_SELF'])."?page=".$_GET['page']."&edit=".$_GET['edit']);
				echo("&entryUp=".$params['entries'][$i]['id']."'>i18n(entry_move_up)</a> ");
			}

			// Button zum Runterschieben
			if( $params['entries'][$i]['ordering'] < sizeof($params['entries'])-1)
			{
				echo("<a href='".basename($_SERVER['PHP_SELF'])."?page=".$_GET['page']."&edit=".$_GET['edit']);
				echo("&entryDown=".$params['entries'][$i]['id']."'>i18n(entry_move_down)</a> ");
			}
			echo("</td>\n");
		}
		echo("</table>\n");
	}
?>
