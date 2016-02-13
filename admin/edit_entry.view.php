<?php
/**
 * Dieser Datei enthaelt den View zur Bearbeitung eines Newsletter-Eintrages durch
 * den Admin. Das Haupteingabefenster ist dabei von der ID "tinymce", so dass ein
 * woanders initiierter WYSIWYG-Editor das hier verwendete Textarea gern ersetzen darf.
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009 nach Vorlage von Sebastian de Vries
 * @package Newslettersystem
 * @subpackage Backend
 */
?>
	<h2>i18n(edit_entry_headline)</h2>
	<p>
	<?php
	if(isset($_GET['useRawHtml']) && $_GET['useRawHtml']=='true')
	{
		echo("<p>i18n(entry_edit_raw_headline)");
		echo("<a href='".basename($_SERVER['PHP_SELF'])."?page=".$_GET['page']."&edit=".$_GET['edit']);
		echo("&editEntry=".$_GET['editEntry']."'>i18n(entry_edit_description_link)</a>.</p>\n");
	}else
	{
		?>
		<strong>i18n(entry_edit_description_headline)</strong><br />
		i18n(entry_edit_description_content)
		<?php
		echo("<a href='".basename($_SERVER['PHP_SELF'])."?page=".$_GET['page']."&edit=".$_GET['edit']);
		echo("&editEntry=".$_GET['editEntry']."&useRawHtml=true'>i18n(entry_edit_description_link)</a>.\n");
		?>

		</p>
		<?php
	}

	echo("<br />\n");
	echo("<form method='post' action='".basename($_SERVER['PHP_SELF'])."?page=".$_GET['page']."&edit=".$_GET['edit']."'>\n");
		echo("<label>i18n(entry_edit_headline)</label>\n");
		echo("<input type=\"hidden\" name=\"saveEntry\" value=\"".$_GET['editEntry']."\">\n");
		echo("<input style='font-size:1.9em;' type='text' size='40' name='headline' ");
		echo("value='".$params[0]['headline']."' /><br /><br />\n");

		echo("<label>i18n(entry_edit_entry)</label><br />\n");
		echo("<textarea id='entry_edit_window' class='content' name='content' rows='16' cols='90'>");
		echo($params[0]['content']."</textarea><br />\n");

		echo("<input type='submit' value='i18n(save)' />\n");
	echo("</form>\n");
?>
