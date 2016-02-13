<?php
/**
 * Diese Datei enthaelt den View zur Anzeige aller vorhandenen Newsletter im Admin-Bereich
 * des Newslettersystems. Er zeigt eine Tabelle mit allen Newslettern des Systems und
 * die fuer den jeweiligen Newsletter moeglichen Aktionsbuttons an.
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009 nach Vorlage von Sebastian de Vries
 * @package Newslettersystem
 * @subpackage Backend
 */

    $current_page = NewsletterAdministrator::DEFAULT_ADMIN_PAGE;
    if( array_key_exists("page", $_GET) && strlen( $_GET['page'])>0){
        $current_page = $_GET['page'];
    }

	if( is_array($params['newsletters']) && sizeof( $params['newsletters'])>0)
	{ ?>
		<h2>i18n(manage_existing_newsletters_headline)</h2>
		<br />
		i18n(existing_newsletters_headline)
		<table border='1' width='100%'>
			<tr>
				<th>i18n(newsletters_table_td_date)</th>
				<th>i18n(newsletters_table_td_entrycount)</th>
				<th>i18n(newsletters_table_td_status)</th>
				<th>&nbsp;</th>
			</tr>
		<?php
		for( $i=0; $i<sizeof( $params['newsletters']); $i++)
		{
			echo("<tr><td>i18n(newsletter_headline_with_date) ".date("d.m.Y", $params['newsletters'][$i]['date'])."</td>\n");
			echo("<td>".sizeof( $params['newsletters'][$i]['entries'])."</td>");
			if($params['newsletters'][$i]['sent']>0 )
			{
				echo "<td>gesendet</td><td>\n";
				echo "<a href='../newsletter_view.php?id=".$params['newsletters'][$i]['id']."' target='_blank'>i18n(newsletter_status_view)</a>\n";
				echo "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$current_page."&deleteNewsletter=".$params['newsletters'][$i]['id'];
				echo "' onclick='return confirm(\"i18n(newsletter_status_delete_confirm_sended)\")'>i18n(newsletter_status_delete)</a>\n";
				echo("</td>\n");
			}else
			{
				echo( "<td>i18n(newsletter_status_unsend)</td><td>\n");
				echo( "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$current_page."&edit=");
				echo( $params['newsletters'][$i]['id']."'>i18n(newsletter_status_edit)</a>\n");
				echo("<a href='../newsletter_view.php?id=".$params['newsletters'][$i]['id']."' target='_blank'>i18n(newsletter_status_preview)</a>\n");
				if(sizeof($params['newsletters'][$i]['entries'])>0)
				{
					echo( "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$current_page);
					echo( "&sendNewsletter=".$params['newsletters'][$i]['id']."'>i18n(newsletter_status_send)</a>\n");
				}
				echo( "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$current_page);
				echo( "&deleteNewsletter=".$params['newsletters'][$i]['id']."' ");
				echo( "onclick='return confirm(\"i18n(newsletter_status_delete_confirm_unsend)\")'>i18n(newsletter_status_delete)</a>\n");
				echo("</td>\n");
			}
		}
	}
?>
	</table>
