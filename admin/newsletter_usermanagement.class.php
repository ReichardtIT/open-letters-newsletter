<?php
/**
 * Verwaltet die Empfaenger des Newsletters.
 * Hier koennen Empfaenger hinzugefuegt und geloescht werden
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009 nach Vorlage von Sebastian de Vries
 * @author Bernd Krüger-Knauber anno 2012
 * @package Newslettersystem
 * @subpackage Backend
 */

	/** Elternklasse laden */
	require_once( ROOT_PATH."framework/parentclass.class.php");
	
	/** Newsletter-An- und -Abmeldungen verwalten koennen */
	require_once( ROOT_PATH."include/newsletter_form.class.php");

	// für das Einlesen der Adressdateien
	require_once( ROOT_PATH."framework/filemanager.class.php");

/**
 * Diese Klasse ermoeglicht die Verwaltung der Newsletter des Systems und deren
 * Eintraege (Entries).
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009 nach Vorlage von Sebastian de Vries
 * @package Newslettersystem
 * @subpackage Backend
 */
class NewsletterUsermanagement extends Parentclass{

    protected $nl_form;

    public function NewsletterUsermanagement(){

        parent::__construct();
        $this->nl_form = new NewsletterForm();
    }

    /**
     * Diese Methode liest aus <i>$_GET['deleteUser']</i> aus, welcher User
     * zu loeschen ist und loescht alle User dieser Emailadresse aus der
     * Datenbank.
     * @return int gibt bei erfolgreichem Loeschen 1 zurueck, sonst 0
     */
    protected function delete_user(){

        $rueckgabe = 0;

        $sql = "SELECT * FROM ".PREFIX."newsletter WHERE email=?;";
        $erg = $this->db->prepare_and_execute( $sql, array($_GET['deleteUser']));

        if( $erg && is_array( $erg) && sizeof( $erg)>0 ){

            $params = array( "email_id" => $erg[0]['email_id']);
            if( $erg[0]['aktiv']==0){

                // really and finally delete user from database
                $sql = "DELETE FROM ".PREFIX."newsletter WHERE email_id=:email_id;
                        DELETE FROM ".PREFIX."newsletterUserInGroup WHERE user=:user;";
                $returnvalue = 2;
                $params['user'] = $erg[0]['email_id'];
            }else{

                // only mark user as aktiv=0
                $sql = "UPDATE ".PREFIX."newsletter SET aktiv='0',updatedAt=:updatedAt WHERE email_id=:email_id;";
                $params['updatedAt'] = date("Y-m-d H:i:s");
                $returnvalue = 1;
            }

            $result = $this->db->prepare_and_execute( $sql, $params);
            if( $result!==FALSE ){
                $rueckgabe = $returnvalue;
            }
        }
        return( $rueckgabe);
    }

    /**
     * Diese Methode zeigt die Oberflaeche zur Erzeugung eines neuen Empfaengers
     * und die darunter liegende Liste der Empfaenger des Systems an. Zuvor
     * ruft sie evtl. die Methode <i>delete_user()</i> und gibt deren Meldungen
     * mit aus. Die eigentliche Erzeugung der Ausgaben erfolgt dabei durch andere
     * Methoden dieser Klasse.
     * @return String
     */
    public function show(){

        $rueckgabe = "";
        $delete = "";

        if( is_array( $_GET) && array_key_exists("deleteUser", $_GET))
        {
            $result = $this->delete_user();
            if( $result == 2){
                $delete = $this->text->get_text("newsletter_delete_success_admin");
            } elseif( $result == 1) {
                $delete = $this->text->get_text("newsletter_unsubscribe_success_admin");
            } else {
                $delete = $this->text->get_text("newsletter_unsubscribe_form_text");
            }
        }

        $rueckgabe.= $this->show_user_add_form();
        $rueckgabe.= "<hr />\n";
        if( strlen( $delete)>0)
            $rueckgabe.= "<p style=\"color: #f00;\">".$delete."</p><hr />";
        $rueckgabe.= $this->show_user_upload_form();
        $rueckgabe.= $this->show_receivers();

        return( $rueckgabe);
    }

    /**
     * Diese Methode verarbeitet das Hinzufuegen eines neuen Empfaengers zum Newslettersystem.
     * Dabei bedient sie sich der Methoden der Klasse NewsletterForm aus dem
     * Frontend.
     * @return String evlt. anfallende Fehlermeldungen oder die Erfolgsmeldung
     */
    protected function process_adding(){

        $error="";
        $rueckgabe = "";

        if(isset($_GET['sendit']))
        {
            $error = $this->nl_form->checkInput();
            if(	strlen($error)==0)
                $rueckgabe = $this->nl_form->subscribe();
            else
                $rueckgabe = $error;
        }

        if(isset($_GET['uploadit']))
        {
            if( strlen($_FILES['uploadedfile']['name']) == 0)
                    $rueckgabe = $this->text->get_text("form_input_error_nofile")."<br>\n";
            else
            {
                $dateiname = ROOT_PATH."uploaded/".$_FILES['uploadedfile']['name'];
                if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $dateiname))
                {
                    $endung = substr($dateiname, -3, 3);
                    if($endung == "txt" || $endung == "csv")
                    {
                        $fm = new FileManager($dateiname);
                        $anzahl = $fm->read();
                        if($endung == "txt")
                            $start = 0;
                        else
                            $start = 1;

                        $sql1 = "INSERT INTO ".PREFIX."newsletter SET email=:email, name=:name,"
                            . " anrede=:anrede, aktiv=1, createdAt=:createdAt, updatedAt=:updatedAt;";
                        $stmt1 = $this->db->prepare($sql1);

                        for( $i=$start; $i<$anzahl; $i++){

                            if( $endung=="txt"){
                                $email = $fm->get_row( $i);
                                $name = "";
                                $anrede = "";
                            }else{
                                $email = $fm->get_column( $i, 0);
                                $name = $fm->get_column( $i, 2);
                                $anrede = $fm->get_column( $i, 1);
                            }

                            // add user
                            $this->db->execute($stmt1, array(
                                "name" => $name,
                                "anrede" => $anrede,
                                "email" => strip_tags( strtolower( trim( $email))),
                                "createdAt" => date("Y-m-d H:i:s"),
                                "updatedAt" => date("Y-m-d H:i:s"),
                            ));
                        }
                        $rueckgabe = $this->text->get_text("newsletter_subscription_success");
                    }else{
                        $rueckgabe = $this->text->get_text("form_input_error_wrongfile")."<br>\n";
                    }

                    unlink($dateiname);
                }else{
                    $rueckgabe = "Failed";
                }
            }
        }

        return( $rueckgabe);
    }

    /**
     * Diese Methode zeigt die Liste der Empfaenger des Newslettersystems an. Sie
     * verarbeitet die Uebergabewerte des Filter-Formulars und erzeugt dann
     * die Liste (und erneut das Filterformular) mit Hilfe eines Views.
     * @return string die Liste der Empfaenger und das darueberliegende Filterformular
     */
    protected function show_receivers(){

        $rueckgabe = "";

        $db_params = array();
        $where="WHERE double_optin_token IS NULL";

        if( isset($_GET['filtername']) 
            && strlen($_GET['filtername'])==0){
            unset($_GET['filtername']);
        }

        if( isset($_GET['filtermail']) 
            && strlen($_GET['filtermail'])==0){
            unset($_GET['filtermail']);
        }

        if( isset($_GET['filtername'])){
            $where.=" AND name LIKE :filtername";
            $db_params['filtername'] = '%'.$_GET['filtername'].'%';
        }

        if( isset($_GET['filtermail'])){
            $where.=" AND email LIKE :filtermail";
            $db_params['filtermail'] = '%'.$_GET['filtermail'].'%';
        }

$entries_per_page = 250;
        $page = 1;
        if( isset( $_GET['pageNumber']) && strlen( $_GET['pageNumber'])>0 ){
            $page = (int)$_GET['pageNumber'];
        }
        $offset = " OFFSET ".($page-1)*$entries_per_page;

        $sql = "SELECT * FROM ".PREFIX."newsletter "
            . $where." ORDER BY createdAt ASC "
            . "LIMIT ".$entries_per_page." ".$offset;
        $params = $this->db->prepare_and_execute( $sql, $db_params);
        $rueckgabe = $this->execute_view( ROOT_PATH."admin/newsletter_receivers.view.php", $params);

        return( $rueckgabe);
    }

    /**
     * Diese Methode erzeugt das Formular zur Anmeldung eines neuen Empfaengers fuer
     * das Newslettersystem. Zuvor wird die Methode <i>process_adding()</i> dieser
     * Klasse gerufen, die ggf. die Eingaben aus einem vorherigen Versenden dieses
     * Formulars verarbeitet.
     * @return Das fertige Formular und evtl. bei der Verarbeitung anfallende Ausgaben
     */
    protected function show_user_add_form(){

        $rueckgabe = "";
        $params = array();
        $error = $this->process_adding();
        if( $error == $this->text->get_text("newsletter_subscription_success"))
            $error = $this->text->get_text("newsletter_subscription_success_admin");

        if( strlen( $error)>0)
            $rueckgabe.= "<p style=\"color: #f00;\">".$error."</p><hr />";


        $tmp = $this->postman->get_plaintext( "title");
        if( !is_null( $tmp))
            $params['title'] = $tmp;
        else
            $params['title'] = "";

        $tmp = $this->postman->get_plaintext( "firstname");
        if( !is_null( $tmp))
            $params['firstname'] = $tmp;
        else
            $params['firstname'] = "";

        $tmp = $this->postman->get_plaintext( "name");
        if( !is_null( $tmp))
            $params['name'] = $tmp;
        else
            $params['name'] = "";

        $tmp = $this->postman->get_email( "newsemail");
        if( !is_null( $tmp))
            $params['newsemail'] = $tmp;
        else
            $params['newsemail'] = "";

        $rueckgabe.= $this->execute_view( ROOT_PATH."admin/user_add_form.view.php", $params);
        return( $rueckgabe);
    }
	
    /**
     * This method creates a form to upload newsletter receivers in admin backend
     * via csv or txt.
     * @return string the HTML form ready to echo
     */
    protected function show_user_upload_form(){

        $rueckgabe = "";
        $params = array();
        $error = $this->process_adding();
		
        $rueckgabe.= $this->execute_view( ROOT_PATH."admin/user_upload_form.view.php", $params);
        return( $rueckgabe);
    }
}
