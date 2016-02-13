<?php
/**
 * Diese Datei enthaelt den View, der das Formular zur Anmeldung zum Newsletter
 * erstellt.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage Frontend
 */
?>
<h2>i18n(newsletter_subscribe_form_headline)</h2>
<?php
echo("<form method=\"post\" action=\"" . filter_input(INPUT_SERVER, 'PHP_SELF') . "?sendit\">\n");
?>
<table width="100%" cellspacing="0" cellpadding="0" border="0">

    <?php
    if (isset($params['error'])) {
        echo("<tr>\n");
        echo("<td colspan=\"2\"><font color='#ff0000'>" . $params['error'] . "</font></td>\n");
        echo("</tr>\n");
    }
    ?>

    <tr>
        <td class="small">i18n(form_word_salutation)*</td>
        <td class="small" style="padding-left:40px;">
            <span class="small">
                i18n(form_star_description)
            </span>
        </td>
    </tr>

    <tr>
        <td class="small" valign="bottom">
            <select name="title" style="margin-bottom:7px;">
                <option value="">i18n(form_please_select)</option>
                <option value="Frau" <?php if ($params['title'] == "Frau") echo "selected"; ?> >i18n(form_word_misses)</option>
                <option value="Herr" <?php if ($params['title'] == "Herr") echo "selected"; ?> >i18n(form_word_mister)</option>
            </select>
        </td>
        <td class="small">&nbsp;</td>
    </tr>

    <tr>
        <td class="small">i18n(form_word_firstname)*</td>
        <td class="small">i18n(form_word_lastname)*</td>
    </tr>

    <tr>
        <td>
            <input class="news" type="text" name="firstname" style="margin-bottom:7px;"
            <?php
            if (isset($params['firstname']))
                echo "value=\"" . $params['firstname'] . "\" />";
            else
                echo "value=\"\" />"
                ?>
        </td>
        <td>
            <input class="news" type="text" name="name" style="margin-bottom:7px;"
            <?php
            if (isset($params['name']))
                echo "value=\"" . $params['name'] . "\" />";
            else
                echo "value=\"\" />"
                ?>
        </td>
    </tr>

    <tr>
        <td class="small">
            i18n(form_word_mailaddress)*
        </td>
        <td class="small">&nbsp;</td>
    </tr>

    <tr>
        <td>
            <input class="news" type="text" name="newsemail" style="margin-bottom:7px;"
            <?php
            if (isset($params['newsemail']))
                echo "value=\"" . $params['newsemail'] . "\" />";
            else
                echo "value=\"\" />"
                ?>
        </td>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="3" class="small">
            <input type="checkbox" name="newsagreement" value="newsletter_notice_accepted" class="news" /> * i18n(form_newsagreement)
        </td>
    </tr>

    <tr>
        <td colspan="3" align="right">
            <input type="hidden" name="sendit" value="1" />
            <input class="news" type="submit" value="i18n(form_subscribe_submit)" />
        </td>
    </tr>
</table>
</form>