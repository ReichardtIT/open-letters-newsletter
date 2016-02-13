<?php
/**
 * Dieser View initialiesiert die Progressbar (Fortschrittsbalken) beim Versand
 * der Newsletter.
 * @author Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage Backend
 */
?>

    <style type="text/css">
        .ui-progressbar-value { background-image: url(<?php echo ROOT_PATH; ?>images/pbar-ani.gif);}
    </style>
    <script type="text/javascript">
        jQuery(function() {
            jQuery("#progressbar").progressbar({
                value:<?php echo $params['percentage'];?> 
            });
        });
    </script>
