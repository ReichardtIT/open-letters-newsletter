<?php
/**
 * Diese Datei enthaelt den View, der dem Administrator das Hinzufuegen eines
 * Empfaengers zum Newslettersystem ermoeglicht.
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009 nach Vorlage von Sebastian de Vries
 * @package Newslettersystem
 * @subpackage Backend
 */

	echo( "<form method=\"post\" action=\"".basename($_SERVER['PHP_SELF'])."?page=usermanagement&amp;sendit\">\n" );
?>
	<h2>i18n(newsletter_subscribe_form_headline_admin)</h2>
    <div style="margin: 10px 5px; color: blue;">i18n(newsletter_subscribe_form_notice_admin)</div>
	<div style="margin-bottom: 20px;">
		<input type="hidden" name="sendit" value="1" />
		<?php echo( "<input type=\"hidden\" name=\"page\" value=\"".$_GET['page']."\" />\n" ); ?>

		<table border="0" cellspacing="4" cellpadding="0" summary="Empfänger hinzufügen">
			<tr>
				<td>i18n(form_word_salutation)</td>
				<td>
					<select name="title" style="margin-bottom:7px;">
						<option value="">i18n(form_please_select)</option>
						<option value="Frau" <?php if( $params['title']=="Frau") echo "selected"; ?> >i18n(form_word_misses)</option>
						<option value="Herr" <?php if( $params['title']=="Herr") echo "selected"; ?> >i18n(form_word_mister)</option>
					</select>
				</td>
			</tr>

			<tr>
				<td>i18n(form_word_firstname)</td>
				<td>
					<input type="text" name="firstname"
					<?php
						if(strlen($params['firstname'])>0)
							echo "value=\"".$params['firstname']."\" />\n";
						else
							echo "value=\"\" />\n";
					?>
				</td>
			</tr>

			<tr>
				<td>i18n(form_word_lastname)</td>
				<td>
					<input type="text" name="name"
					<?php
						if( strlen($params['name'])>0)
							echo "value=\"".$params['name']."\" />\n";
						else
							echo "value=\"\" />\n";
					?>
				</td>
			</tr>

			<tr>
				<td>i18n(form_word_mailaddress)</td>
				<td>
					<input type="text" name="newsemail"
					<?php
						if(strlen($params['newsemail'])>0)
							echo "value=\"".$params['newsemail']."\" />\n";
						else
							echo "value=\"\" />\n";
					?>
				</td>
			</tr>
            
            <tr>
				<td>
                    <input type="checkbox" name="newsagreement" />
				</td>
                <td><label for="agreement">i18n(form_admin_newsagreement)</label></td>
			</tr>
		</table>
        <input type='submit' value='i18n(form_subscribe_submit)' />
	</div>
	</form>
