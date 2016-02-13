<?php
/**
 * Diese Datei ist die Sprachdatei zur Darstellung aller Texte des Systems in
 * englischer Sprache.
 * @author Stefan Rank-Kunitz at Open-Letters anno 2009
 * @author Bernd Krüger-Knauber anno 2012
 * @package Newslettersystem
 * @subpackage System
 */
	$lang = array();

	$lang['error'] = "Error";
	$lang['error_no_db_connection'] = "The database connection could not be initialised!";
	$lang['error_no_database_selected'] = "The database could not be selected!";
	$lang['error_unknown_view'] = "The requested view was not found!";

	$lang['newsletter_archive_headline'] = "Newsletter archive";
	$lang['newsletter_headline_with_date'] = "Newsletter from";
	$lang['edit'] = "edit";
	$lang['save'] = "save";
    $lang['delete'] = "delete";
	$lang['delete_force'] = "delete finally";

	$lang['newsletter_subscribe_form_headline'] = "Subscribe to newsletter";
	$lang['newsletter_subscribe_form_headline_admin'] = "add a newsletter receiver";
    $lang['newsletter_subscribe_form_notice_admin'] = "<strong>NOTICE</strong><br>To add newsletter receivers manually does send out double-opt-in emails and so is successful after user acknowledgement!";
    $lang['newsletter_upload_format_info'] = "<strong>format specification:</strong><br>You can upload a text file (extension \".txt\", encoding UTF8), containing one email-adrress in ever line. Uploading names and salutation is not supported in text files.<br>You can also upload a CSV file containing one receiver with email-address, salutation and name in every row. Please use a semicolon as separator: info@example.com;Mister;John Doe;";

	$lang['newsletter_upload_form_headline_admin'] = "Newsletter receiver upload";
	$lang['newsletter_upload_form_file_admin'] = "Addressfile (.txt or .csv)";

	$lang['newsletter_unsubscribe_form_headline'] = "Unsubscribe from newsletter";
	$lang['newsletter_unsubscribe_form_text'] = "Please insert your email address for deletion from our newsletter!";
	$lang['form_unsubscribe_submit'] = "Unsubscribe my email";
	$lang['newsletter_unsubscribe_confirm'] = "Are you shure you want to delete your email from our system?";
	$lang['newsletter_unsubscribe_success'] = "Your email was unsubscribed. You will not get further newsletters.";
	$lang['newsletter_unsubscribe_success_admin'] = "The email was marked <strong>unsubscribed</strong>. The receiver will not get further newsletters.<br>To finally delete email address please use button <strong>delete finally</strong>.";
	$lang['newsletter_delete_success_admin'] = "The email was <strong>deleted finally</strong>.";
	$lang['newsletter_unsubscribe_address_not_found'] = "Your email was not found in our system. You will not get our newsletters.";

	$lang['form_word_salutation'] = "Salutation";
	$lang['form_word_mister'] = "Mr.";
	$lang['form_word_misses'] = "Mrs.";
	$lang['form_star_description'] = "Fields marked with * must be completed.";
	$lang['form_please_select'] = "Please select";
	$lang['form_word_firstname'] = "First name";
	$lang['form_word_lastname'] = "Last name";
	$lang['form_word_name'] = "name";
	$lang['form_word_email'] = "email";
	$lang['form_word_mailaddress'] = "Email address";
	$lang['form_subscribe_submit'] = "Subscribe";
	$lang['form_newsagreement'] = "By entering this competition you are agreeing to sign up to receive our newlsetter for information and updates. You may withdraw your consent of receiving a newsletter by form or contact-us page.";
    $lang['form_admin_newsagreement'] = "The user agreed receiving Your newsletter emails and a subscription mail.<br>If You click the subscription button below, the user will receive a \"Double-OptIn-Mail\" containing a link to agree subscription.";
    $lang['form_word_registration'] = "Registration";
    $lang['form_word_last_modification'] = "last changed";

	$lang['form_input_error_salutation'] = "Please select a salutation!";
	$lang['form_input_error_firstname'] = "Please insert your first name!";
	$lang['form_input_error_name'] = "Please insert your last name!";
	$lang['form_input_error_email'] = "Please insert a valid email address!";
	$lang['form_input_error_newsagreement'] = "Please accept the declaration of agreement!";

	$lang['form_input_error_nofile'] = "No file uploaded!";
	$lang['form_input_error_wrongfile'] = "No txt or csv file!";

	$lang['newsletter_subscription_not_needed'] = "You already receive our newsletter.<br /><br />Thank you for your interest!";
	$lang['newsletter_subscription_opt_in_message'] = "<h2>Thank Your for subscribing to our newsletter.</h2>Please check Your email inbox now. To finish subscription You must click the link in the email we just sent You (double opt-in).";
    $lang['newsletter_subscription_opt_in_error_message'] = "<h2>An error appeared!</h2>Sending You a subscription email was not successful. Please check Your input data and repeat subscription!";
	$lang['newsletter_subscription_opt_in_email_message'] = "Dear ladies and gentlemen,\n\nThank Your for subscribing to our newsletter. To finish subscription You must click the following link:\n\n#####url#####\n\nsincerely yours\n#####host##### newsletter team";
	$lang['newsletter_subscription_opt_in_email_subject'] = "Your newsletter registration at ";
    $lang['newsletter_subscription_success'] = "<h2>Thank You for subscribing!</h2>We successfully added you to our mailing list. Beginning with the next newsletter a copy will be send to you automatically.<br /><br />Thank You for Your interest!";
	$lang['newsletter_subscription_opt_in_link_error'] = "<h2>Error</h2>The used hyperlink was wrong, please check again!";
	$lang['newsletter_subscription_success_admin'] = "The receiver was successfully added to the mailing list.";

	$lang['newsletter_dosnt_exists'] = "The requested newsletter doesn't exist!";

	$lang['newsletter_admin_section_01'] = "create or edit newsletters";
	$lang['newsletter_admin_section_02'] = "manage newsletter receivers";
	$lang['filter_list'] = "search for receivers";
	$lang['filter_list_text'] = "Here you can search for entries from the receiver list below.<br />You can even search for letters or parts of words.";
	$lang['form_word_filter'] = "filter";
	$lang['receivers_list_headline'] = "The following people currently get a copy of your newsletters.<br><strong>Attention:</strong> If You delete a newsletter receiver it will only be marked as <em>deleted</em>. This will be done to denial a accidential reimport of a user. The second deletion will finally erase the user from the system (as required in Germany).";
	$lang['table_shortened'] = "... table shortened ...";

	$lang['newsletter_delete_success'] = "The newsletter was deleted!";
	$lang['newsletter_delete_error']  = "The newsletter could not be deleted!";
	$lang['newsletter_entry_delete_success'] = "The newsletter entry was deleted!";
	$lang['newsletter_entry_delete_error']  = "The newsletter entry could not be deleted!";

	$lang['newsletter_send_headline'] = "Send a newsletter";
	$lang['newsletter_send_headline_01'] = "If you are shure to send the newsletter now to ALL receivers, select a sender. Instead you can abort sending.";
	$lang['newsletter_send_button'] = "send newsletter now";
	$lang['newsletter_send_abort'] = "abort sending";
	$lang['newsletter_send_success_01'] = "count of successfully sended newsletters: ";
	$lang['newsletter_send_success_02'] = "count of newsletter receivers: ";

	$lang['newsletter_save_entry_success'] = "The entry was saved.";
	$lang['newsletter_save_entry_error'] = "The entry could not be saved";

	$lang['entry_edit_unknown_id_error'] = "The requested newsletter entry doesn't exist.";

	$lang['create_a_new_newsletter'] = "create a new newsletter";
	$lang['create_newsletter_button'] = "add newsletter";
	$lang['choose_template_headline'] = "Choose a layout (template) and press button below:";
	$lang['manage_existing_newsletters_headline'] = "manage existing newsletters";
	$lang['existing_newsletters_headline'] = "These newsletters already exist:";
	$lang['newsletters_table_td_date'] = "date";
	$lang['newsletters_table_td_entrycount'] = "entries";
	$lang['newsletters_table_td_status'] = "status";

	$lang['newsletter_status_view'] = "View";
	$lang['newsletter_status_delete'] = "Delete";
	$lang['newsletter_status_delete_confirm_sended'] = "Do you really want to delete this newsletter? This removes the newsletter from the archive at your website too!";
	$lang['newsletter_status_edit'] = "Edit";
	$lang['newsletter_status_preview'] = "Preview";
	$lang['newsletter_status_send'] = "SEND";
	$lang['newsletter_status_delete_confirm_unsend'] = "Do you really want to delete this newsletter? I was not send!";
	$lang['newsletter_status_unsend'] = "created";

	$lang['create_entry_button'] = "add a new entry to newsletter";
	$lang['existing_entries_headline'] = "These entries allready exist:";
	$lang['entry_headline'] = "entry headline";
	$lang['entry_delete'] = "Delete";
	$lang['entry_confirm_delete'] = "Do you really want to delete this entry?";
	$lang['entry_move_up'] = "Move up";
	$lang['entry_move_down'] = "Move down";
	$lang['entry_edit'] = "Edit";
	$lang['edit_entry_headline'] = "Edit a newsletter entry";
	$lang['entry_edit_description_headline'] = "Description";
	$lang['entry_edit_description_content'] = "Here you can edit the entry of your newsletter.<br />It works just linke a usual word processor. Like in a word processor you should use format templates to insert headlines or enumerations.<br />If you want to use an input window without these functionality or your browser is not abled to use this editor, klick";
	$lang['entry_edit_description_link'] = "here";
	$lang['entry_edit_raw_headline'] = "If you want to use a more comfortable editor, klick ";
	$lang['entry_edit_headline'] = "headline";
	$lang['entry_edit_entry'] = "entry";
