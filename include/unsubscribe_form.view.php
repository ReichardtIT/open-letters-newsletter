<?php
/**
 * Diese Datei enthaelt den View, der das Formular zur Anmeldung zum Newsletter
 * erstellt.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage Frontend
 */
?>
<h2>i18n(newsletter_unsubscribe_form_headline)</h2>
<?php
echo("<form method=\"get\" action=\"" . $_SERVER['PHP_SELF'] . "?sendit\">\n");
?>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td>i18n(newsletter_unsubscribe_form_text)</td>
    </tr>

    <tr>
        <td>
            <input class="news" type="text" name="unsubscribe" style="margin-bottom:7px;" />
        </td>
    </tr>

    <tr>
        <td align="right">
            <input class="news" type="submit" value="i18n(form_unsubscribe_submit)" />
        </td>
    </tr>
</table>
</form>
