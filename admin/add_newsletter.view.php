<?php
/**
 * Diese Datei enthaelt den View zur Anzeige des Buttons, mit dem der  Admin einen
 * neuen Newsletter anlegen kann. Ferner werden hier die vorhandenen Newsletter-
 * Templates zur Auswahl eines solchen fuer den neuen Newsletter ausgewaehlt.
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009 nach Vorlage von Sebastian de Vries
 * @package Newslettersystem
 * @subpackage Backend
 */

?>
	<h2>i18n(create_a_new_newsletter)</h2>
	<?php

	// Button zur Erzeugung eines neuen Newsletters anzeigen
	echo "<form method='post' action='".basename($_SERVER['PHP_SELF'])."?page=newslettermanagement&amp;newNewsletter'>\n";
	echo "<div>\n";
		echo "i18n(choose_template_headline)<br /><br />";

	// Liste der Templates ausgeben
	for( $i=0; $i<sizeof( $params['templates']); $i++)
	{
		echo "<input type=\"radio\" name=\"template\" ";
		if( $i==0) echo "checked=\"checked\" ";
		echo "value=\"".$params['templates'][$i]."\" />\n";
		echo "<img
            src=\"".ROOT_PATH."newsletter_templates/".$params['templates'][$i].".png\"
            alt=\"".$params['templates'][$i]."\"
            title=\"".$params['templates'][$i]."\" />\n\n";
	}
	?>
		<br /><br />
		<input type="hidden" name="newNewsletter" value="true">
		<input type='submit' value='i18n(create_newsletter_button)' />
	</div>
	</form>
	<br />
	<hr />
