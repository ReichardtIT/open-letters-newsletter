<?php
/**
 * Diese Datei enthaelt den View, der die Liste der Empfaenger der Newsletter
 * anzeigt. Im oberen Bereich erzeugt der View eine Box zum Filtern der Empfaenger-
 * Liste, darunter wird die Liste der Empfaenger und zu jedem Eintrag
 * ein <i>Loeschen</i>-Button angezeigt.
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009 nach Vorlage von Sebastian de Vries
 * @package Newslettersystem
 * @subpackage Backend
 */
?>
	<form method="get" action="<?php echo basename( $_SERVER['PHP_SELF']); ?>">
		<h2>i18n(newsletter_admin_section_02)</h2>
		<fieldset id="newsletter_admin_backend_filterform">
			<legend style="padding: 2px 5px; border: 1px solid #999; background-color: #eee;">
                <strong>i18n(filter_list)</strong></legend>
			<p style="margin:0 0 10px 0;">i18n(filter_list_text)</p>

			<?php echo("<input type=\"hidden\" name=\"page\" value=\"".$_GET['page']."\" />\n"); ?>

            <div style="float:left; width: 300px;">
                <label for="filtername">i18n(form_word_name)</label><input type='text' name='filtername'
                <?php
                if(isset($_GET['filtername']))
                    echo "value=\"".$_GET['filtername']."\""; ?> />
            </div>
			

			<label for="filtermail">i18n(form_word_email)</label><input type='text' name='filtermail'
			<?php
			if(isset($_GET['filtermail']))
				echo "value=\"".$_GET['filtermail']."\""; ?> />

			<input type='submit' value='i18n(form_word_filter)' />
		</fieldset>
	</form>

	<br />
	i18n(receivers_list_headline)
	<table border='1' width='100%'>
		<tr>
			<th>i18n(form_word_name)</th>
			<th>i18n(form_word_email)</th>
			<th>i18n(form_word_registration)</th>
			<th>i18n(form_word_last_modification)</th>
			<th></th>
		</tr>
        <?php
        $count = sizeof($params);
        for($i=0; $i<$count; $i++){

            echo "<tr";
            if( $params[$i]['aktiv']==0) echo " class='deleted'";
            echo ">";
            echo "<td>".$params[$i]['anrede']." ".$params[$i]['name'];
            if( $params[$i]['aktiv']==0) echo " (gelöscht)";
            echo "</td>";
            echo "<td>".$params[$i]['email']."</td>";
            echo "<td>".date("d.m.Y H:i:s", strtotime($params[$i]['createdAt']))."</td>";
            echo "<td>".date("d.m.Y H:i:s", strtotime($params[$i]['updatedAt']))."</td>";
            echo "<td><a href='".basename($_SERVER['PHP_SELF'])."?page=".$_GET['page']."&amp;deleteUser=".$params[$i]['email']."' ";
            echo "onclick='return confirm(\"i18n(newsletter_unsubscribe_confirm)\")'>";
            if( $params[$i]['aktiv']==0) echo "i18n(delete_force)";
            else echo "i18n(delete)";
            echo "</a>";
            echo "</td></tr>\n";
        }

        if( $count >=250){
            echo "<tr><td colspan=\"3\">i18n(table_shortened)</td></tr>\n";
        } ?>
	</table>
