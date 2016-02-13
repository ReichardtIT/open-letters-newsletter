<?php
/**
 * Diese Datei ist die Sprachdatei zur Darstellung aller Texte des Systems in
 * deutscher Sprache.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters anno 2009
 * @author Bernd Krüger-Knauber anno 2012
 * @package Newslettersystem
 * @subpackage System
 */
	$lang = array();

	$lang['error'] = "Fehler";
	$lang['error_no_db_connection'] = "Die Datenbank-Verbindung konnte nicht aufgebaut werden.";
	$lang['error_no_database_selected'] = "Die Datenbank konnte nicht ausgewählt werden!";
	$lang['error_unknown_view'] = "Der angeforderte View wurde nicht gefunden!";

	$lang['newsletter_archive_headline'] = "Newsletter-Archiv";
	$lang['newsletter_headline_with_date'] = "Newsletter vom";
	$lang['edit'] = "bearbeiten";
	$lang['save'] = "speichern";
	$lang['delete'] = "löschen";
	$lang['delete_force'] = "endgültig löschen";

	$lang['newsletter_subscribe_form_headline'] = "Anmeldung zum Newsletter";
	$lang['newsletter_subscribe_form_headline_admin'] = "Newsletter-Empfänger hinzufügen";
	$lang['newsletter_subscribe_form_notice_admin'] = "<strong>ACHTUNG!</strong><br>Bitte beachten Sie, dass das manuelle Eintragen von Newsletter-Empfängern ebenfalls eine Double-Opt-In-Email versendet und damit erst nach der Bestätigung durch den User abgeschlossen ist!";
    $lang['newsletter_upload_format_info'] = "<strong>Hinweis zu den Formaten:</strong><br>Sie können eine einfache Textdatei hochladen (Endung \".txt\", Kodierung UTF8), die in jeder Zeile eine E-Mail-Adresse enthält. Die Angabe von Namen und Anrede wird für Textdateien nicht unterstützt. <br> Ferner können Sie eine CSV-Datei hochladen, welche in jeder Zeile eine E-Mail-Adresse und die Anrede und den Namen eines Newsletterempfängers enthält. Als Trennzeichen nutzen Sie bitte ein Semikolon: mail@example.com;Herr;Paul Schmidt;";

	$lang['newsletter_upload_form_headline_admin'] = "Newsletter-Empfänger upload";
	$lang['newsletter_upload_form_file_admin'] = "Adressdatei (.txt oder .csv)";

	$lang['newsletter_unsubscribe_form_headline'] = "Abmelden vom Newsletter";
	$lang['newsletter_unsubscribe_form_text'] = "Bitte geben Sie Ihre E-Mail-Adresse zur Löschung aus dem Newslettersystem ein!";
	$lang['form_unsubscribe_submit'] = "Newsletter abbestellen";
	$lang['newsletter_unsubscribe_confirm'] = "Möchten Sie Ihre E-Mail-Adresse wirklich aus unserem Verteiler löschen?";
	$lang['newsletter_unsubscribe_success'] = "Ihre E-Mail-Adresse wurde aus unserem System gelöscht. Sie bekommen keine weiteren Newsletter von uns.";
	$lang['newsletter_unsubscribe_success_admin'] = "Die E-Mail-Adresse wurde als <strong>gelöscht markiert</strong>. Der Empfänger bekommt keine weiteren Newsletter.<br>Für das endgültige Löschen nutzen Sie bitte die Funktion <strong>endgültig löschen</strong>.";
	$lang['newsletter_delete_success_admin'] = "Die E-Mail-Adresse wurde endgültig <strong>gelöscht</strong>.";
	$lang['newsletter_unsubscribe_address_not_found'] = "Wir haben Ihre E-Mail-Adresse nicht in unserer Datenbank gefunden. Sie waren (und sind) kein Empfänger unseres Newsletters.";

	$lang['form_word_salutation'] = "Anrede";
	$lang['form_word_mister'] = "Herr";
	$lang['form_word_misses'] = "Frau";
	$lang['form_star_description'] = "Mit * gekennzeichnete Felder sind Pflichtfelder!";
	$lang['form_please_select'] = "Bitte ausw&auml;hlen";
	$lang['form_word_firstname'] = "Vorname";
	$lang['form_word_lastname'] = "Nachname";
	$lang['form_word_name'] = "Name";
	$lang['form_word_email'] = "E-Mail";
	$lang['form_word_mailaddress'] = "E-Mail-Adresse";
	$lang['form_subscribe_submit'] = "Anmelden";
	$lang['form_newsagreement'] = "Ich möchte künftig den Newsletter mit Hinweisen zu Angeboten und Informationen erhalten.<br />Die Einwilligung ist freiwillig und kann jederzeit wiederrufen werden. Der Widerruf kann durch das Abbestellformular auf dieser Website, durch Klick des Abbestellinks in den zugesandten Nachrichten oder durch den Abbestellwunsch an die Kontaktadresse erfolgen.";
	$lang['form_admin_newsagreement'] = "Der Nutzer mit den angegebenen Daten hat zugestimmt, den Newsletter und die E-Mail zur Anmeldebestätigung zu erhalten.<br>Wenn Sie <strong>Anmelden</strong> klicken, wird dem Empfänger eine \"Double-OptIn-Mail\" mit einem Link zur Bestätigung der Anmeldung zugetellt.";
    $lang['form_word_registration'] = "Anmeldung";
    $lang['form_word_last_modification'] = "Letzte Änderung";

	$lang['form_input_error_salutation'] = "Bitte wählen Sie eine Anrede!";
	$lang['form_input_error_firstname'] = "Bitte geben Sie Ihren Vornamen ein!";
	$lang['form_input_error_name'] = "Bitte geben Sie Ihren Nachnamen ein!";
	$lang['form_input_error_email'] = "Bitte geben Sie eine gültige E-Mail-Adresse ein!";
	$lang['form_input_error_newsagreement'] = "Bitte akzeptieren Sie die Einverständniserklärung!";

	$lang['form_input_error_nofile'] = "Keine Datei übertragen!";
	$lang['form_input_error_wrongfile'] = "Keine txt oder csv Datei!";

	$lang['newsletter_subscription_not_needed'] = "Sie sind bereits als Empfänger unseres Newsletters eingetragen.<br /><br />Wir bedanken uns für Ihr Interesse!";
    $lang['newsletter_subscription_opt_in_message'] = "<h2>Vielen Dank für Ihre Anmeldung.</h2>Bitte prüfen Sie Ihren E-Mail-Posteingang: Zum Abschließen der Anmeldung müssen Sie auf den Link in der E-Mail klicken, die wir Ihnen soeben zugestellt haben (Double Opt-In)!";
    $lang['newsletter_subscription_opt_in_error_message'] = "<h2>Es ist ein Fehler aufgetreten!</h2>Der Versand der Anmelde-Email konnte nicht durchgeführt werden. Bitte prüfen Sie Ihre Angaben und wiederholen Sie die Anmeldung!";
    $lang['newsletter_subscription_opt_in_email_message'] = "Sehr geehrte Damen und Herren,\n\nVielen Dank für Ihre Anmeldung zu unserem Newsletter-Dienst. Zum Abschluss Ihrer Anmeldung klicken Sie bitte auf den folgenden Link:\n\n#####url#####\n\nMit freundlichen Grüßen\nIhr #####host##### Newsletter-Team";
	$lang['newsletter_subscription_opt_in_email_subject'] = "Ihre Anmeldung zum Newsletter bei ";
	$lang['newsletter_subscription_success'] = "<h2>Vielen Dank für Ihre Anmeldung!</h2>Sie wurden erfolgreich in den Newsletter-Verteiler eingetragen. Sobald der n&auml;chste Newsletter erscheint, erhalten Sie automatisch ein Exemplar zugestellt.<br /><br />Vielen Dank f&uuml;r Ihr Interesse!";
	$lang['newsletter_subscription_opt_in_link_error'] = "<h2>Fehler</h2>Der verwendete Link ist ungültig! Bitte prüfen Sie den verwendeten Link!";
	$lang['newsletter_subscription_success_admin'] = "Der Empfänger wurde erfolgreich hinzugefügt.";

	$lang['newsletter_dosnt_exists'] = "Der gewünschte Newsletter existiert nicht!";

	$lang['newsletter_admin_section_01'] = "Newsletter-Erstellen und Bearbeiten";
	$lang['newsletter_admin_section_02'] = "Verwaltung der Newsletter-Empf&auml;nger";
	$lang['filter_list'] = "Empfänger suchen";
	$lang['filter_list_text'] = "Hier können Sie die untenstehende Liste filtern. Sie k&ouml;nnen auch nach Teilen der zu filternden Worte suchen.";
	$lang['form_word_filter'] = "filtern";
	$lang['receivers_list_headline'] = "Die folgenden Empfänger bekommen derzeit Ihren Newsletter zugestellt.<br /><strong>Hinweis:</strong> Das Löschen eines Empfängers führt im ersten Schritt nur zur <em>Markierung</em> des Empfängers als <em>gelöscht</em>. Damit wird ein versehentlicher Re-Import des Empfängers verhindert. Im zweiten Schritt können Sie den Empfänger dann <em>endgültig löschen</em> (wie es das Datenschutzgesetz auf Verlangen des Betroffenen notwendig macht).";
	$lang['table_shortened'] = "... Tabelle gekürzt ...";

	$lang['newsletter_delete_success'] = "Der Newsletter wurde gelöscht!";
	$lang['newsletter_delete_error']  = "Der Newsletter konnte nicht gelöscht werden!";
	$lang['newsletter_entry_delete_success'] = "Der Eintrag des Newsletters wurde gelöscht!";
	$lang['newsletter_entry_delete_error']  = "Der Eintrag des Newsletters konnte nicht gelöscht werden!";

	$lang['newsletter_send_headline'] = "Versenden eines Newsletters";
	$lang['newsletter_send_headline_01'] = "Wenn Sie sicher sind, den Newsletter jetzt zu versenden, so wählen Sie bitte ein Absender aus oder brechen Sie den Vorgang ab!";
	$lang['newsletter_send_button'] = "Newsletter jetzt versenden";
	$lang['newsletter_send_abort'] = "Abbruch";
	$lang['newsletter_send_success_01'] = "fehlerfrei versendete Newsletter: ";
	$lang['newsletter_send_success_02'] = "Anzahl eingetragener Empfänger: ";

	$lang['newsletter_save_entry_success'] = "Der Eintrag wurde gespeichert.";
	$lang['newsletter_save_entry_error'] = "Der Eintrag konnte nicht gespeichert werden.";

	$lang['entry_edit_unknown_id_error'] = "Der angeforderte Newsletter existiert nicht.";

	$lang['create_a_new_newsletter'] = "Erstellung eines neuen Newsletters";
	$lang['create_newsletter_button'] = "Newsletter erstellen";
	$lang['choose_template_headline'] = "Bitte wählen Sie ein Layout (Template) und drücken Sie den Button darunter:";
	$lang['manage_existing_newsletters_headline'] = "Verwaltung der vorhandenen Newsletter";
	$lang['existing_newsletters_headline'] = "Diese Newsletter wurden bereits angelegt:";
	$lang['newsletters_table_td_date'] = "Datum";
	$lang['newsletters_table_td_entrycount'] = "Einträge";
	$lang['newsletters_table_td_status'] = "Status";

	$lang['newsletter_status_view'] = "Ansehen";
	$lang['newsletter_status_delete'] = "Löschen";
	$lang['newsletter_status_delete_confirm_sended'] = "Möchten Sie diesen Newsletter wirklich löschen? Er wird auch aus dem Archiv auf Ihrer Internetseite entfernt!";
	$lang['newsletter_status_edit'] = "Bearbeiten";
	$lang['newsletter_status_preview'] = "Vorschau";
	$lang['newsletter_status_send'] = "-SENDEN-";
	$lang['newsletter_status_delete_confirm_unsend'] = "Möchten Sie diesen Newsletter wirklich löschen? Er wurde noch nicht versendet!";
	$lang['newsletter_status_unsend'] = "erstellt";

	$lang['create_entry_button'] = "einen neuen Eintrag hinzufügen";
	$lang['existing_entries_headline'] = "Diese Einträge gibt es in diesem Newsletter schon:";
	$lang['entry_headline'] = "Überschrift des Eintrages";
	$lang['entry_delete'] = "Löschen";
	$lang['entry_confirm_delete'] = "Möchten Sie diesen Eintrag wirklich löschen?";
	$lang['entry_move_up'] = "Hochschieben";
	$lang['entry_move_down'] = "Runterschieben";
	$lang['entry_edit'] = "Bearbeiten";
	$lang['edit_entry_headline'] = "Bearbeitung eines Newsletter-Eintrages";
	$lang['entry_edit_description_headline'] = "Kurzanleitung";
	$lang['entry_edit_description_content'] = "Hier können Sie den Eintrag des Newsletters mit Hilfe einer einfachen Textverarbeitung bearbeiten. Dies funktioniert ebenso, wie die Ihnen sicher bekannte Textverarbeitung auch. Und wie in einem gewöhnlichen Textverarbeitungsprogramm sollten Sie hier die über das Menü <strong>Format</strong> erreichbaren Formatvorlagen zur Gestaltung Ihres Textes verwenden.<br /><br /> Wenn Sie statt dessen lieber ein einfaches Eingabefenster wünschen oder Ihr Browser das Textverarbeitungsprogramm nicht fehlerfrei darstellt, so klicken Sie bitte";
	$lang['entry_edit_description_link'] = "hier";
	$lang['entry_edit_raw_headline'] = "Wenn Sie eine etwas komfortablere Textverarbeitung wünschen, klicken Sie bitte ";
	$lang['entry_edit_headline'] = "Überschrift";
	$lang['entry_edit_entry'] = "Eintrag";
