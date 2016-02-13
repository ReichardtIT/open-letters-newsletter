<?php
/**
 * This view adds a main menu on top of admin web interface and runs the sub-classes
 * to administrate newsletters or newsletter receivers.
 * @author Stefan Rank-Kunitz at Open-Letters anno 2013
 * @package Newslettersystem
 * @subpackage Backend
 */

    $current_page = NewsletterAdministrator::DEFAULT_ADMIN_PAGE;
    if( array_key_exists("page", $_GET) && strlen( $_GET['page'])>0){
        $current_page = $_GET['page'];
    }?>

    <ul class="admin_main_menu">
        <li<?php if( $current_page=='newslettermanagement') echo " class='active_menu_entry'";?>>
            <a href='<?php echo basename($_SERVER['PHP_SELF']); ?>?page=newslettermanagement'>
                <?php echo $this->text->get_text("newsletter_admin_section_01"); ?></a>
        </li>
        <li<?php if( $current_page=='usermanagement') echo " class='active_menu_entry'"; ?>>
            <a href='<?php echo basename($_SERVER['PHP_SELF']); ?>?page=usermanagement'>
                <?php echo $this->text->get_text("newsletter_admin_section_02"); ?></a>
        </li>
    </ul>

<?php
    // ggf. Inhalte eines der Bereiche der Website anzeigen
    if($current_page=='usermanagement'){
        $tmp = new NewsletterUsermanagement();	
    }else{
        $tmp = new NewsletterEditor();
    }
    echo $tmp->show();
