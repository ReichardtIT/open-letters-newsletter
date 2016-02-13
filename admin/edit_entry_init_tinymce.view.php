<?php
/**
 * Dieser Datei enthaelt alle Skripte zur Initialisierung des WYSIWYG Editors
 * TinyMCE.
 * @author Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage Backend
 */
?>
    <script language="javascript" type="text/javascript" src="<?php echo ROOT_PATH; ?>admin/external_scripts/tinymce/tiny_mce.js"></script>
    <script language="javascript" type="text/javascript">
        tinyMCE.init({
            mode : "exact",
            language : "de",
            elements : "entry_edit_window, nourlconvert",
            convert_urls : false,
            theme: "advanced",
            theme_advanced_disable: "indent, outdent, cut, copy, paste, help, sub, sup, backcolor, visualaid, anchor, newdocument, fontselect, styleselect",
            theme_advanced_layout_manager: "SimpleLayout",
            theme_advanced_toolbar_location: "top",
            theme_advanced_toolbar_align: "left",
            theme_advanced_buttons1: "undo, redo, separator,bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,bullist,numlist",
            theme_advanced_buttons2: "link,unlink,insertimage,image,separator,cleanup,code,hr,removeformat,charmap,separator,formatselect",
            theme_advanced_buttons3: "",
            plugins: "advimage,advlink,media,contextmenu",
            file_browser_callback: "ajaxfilemanager",
            paste_use_dialog: false,
        });

        // TinyMCE-Modul "Ajax Filemanager" initiieren
        function ajaxfilemanager(field_name, url, type, win) {

            var ajaxfilemanagerurl = "<?php echo ROOT_PATH; ?>admin/external_scripts/tinymce/plugins/ajaxfilemanager/ajaxfilemanager.php"
            switch (type) {
                case "image": break;
                case "media": break;
                case "flash": break;
                case "file": break;
                default: return false;
            }

            tinyMCE.activeEditor.windowManager.open({
                url: "../ajaxfilemanager/ajaxfilemanager.php",
                width: 782,
                height: 440,
                inline : "yes",
                close_previous : "no"
            },{
                window : win,
                input : field_name
            });
        }
    </script>
