<?php
/**
 * Diese Datei enthaelt den View, der dem Administrator das Hochladen einer
 * Adressdatei zum Newslettersystem ermoeglicht.
 * @author Bernd Krüger-Knauber anno 2012
 * @package Newslettersystem
 * @subpackage Backend
 */

	echo( "<form enctype=\"multipart/form-data\" action=\"".basename($_SERVER['PHP_SELF'])."?page=usermanagement&amp;uploadit\" method=\"post\">\n");
?>
	<h2>i18n(newsletter_upload_form_headline_admin)</h2>
    <div style="margin: 10px 5px; color: blue;">i18n(newsletter_subscribe_form_notice_admin)</div>
    <div style="margin: 10px 5px;">i18n(newsletter_upload_format_info)</div>
	<div style="margin-bottom: 20px;">
		<input type="hidden" name="uploadit" value="1" />
		<?php echo( "<input type=\"hidden\" name=\"page\" value=\"".$_GET['page']."\" />\n" ); ?>
		<table border="0" cellspacing="4" cellpadding="0" summary="Empfänger hinzufügen">
			<tr>
				<td>i18n(newsletter_upload_form_file_admin)</td>
				<td><input name="uploadedfile" type="file" size="40" /></td>
				<td><input type="submit" value="Upload" /></td>
			</tr>
		</table>
	</div>
	</form>
