<?php
/**
 * Diese Datei zeigt die AJAX-Oberflaeche zum Absenden des Newsletters an. Sie
 * zeigt den Fortschritt des Versandprozesses mit Hilfe einer jQuery Progessbar
 * an und ruft nach einem Timeout ein Skript zum Start des folgenden Versand-
 * prozesses.
 * @author Stefan Rank-Kunitz [at] Open-Letters und Sebastian de Vries [at] QuBit Germany anno 2009
 * @package Newslettersystem
 * @subpackage Backend
 */
 
?>
	Der Newsletter wird versendet: <span id="percentage"><?php echo $params['percentage']; ?>%</span>
	<span id="time_remaining">( ca. <?php echo $params['time_remaining']; ?> verbleibend)</span>
	<div id="progressbar"></div>
	<p>
		<strong>ACHTUNG:</strong><br />
		Bitte schließen Sie dieses Fenster erst, wenn der Versandprozess beendet
		ist. Sollten Sie das Fenster vorher schließen, wird das Versenden des Newsletters
		abgebrochen!
	</p>

	<script type="text/javascript">
		// Versand per Ajax anstossen
		$(function(){
			$("#progressbar").progressbar();
			window.setTimeout("doAjaxRequests()", 10);
		});
		
		// Hilfsfunktion fuer Update des Fortschrittsbalkens nach dem Ajax-Request
		function executeReturnValues( jsonReturnValues)
		{
			$("#progressbar").progressbar( 'value', parseInt( jsonReturnValues['percentage']) );
			$("#percentage").html( jsonReturnValues['percentage']+"%");
			$("#time_remaining").html( "( ca. "+jsonReturnValues['time_remaining']+" verbleibend)");
		}

		// Funktion des AjaxReuests
		function doAjaxRequests()
		{
			var finished = 0;
			$.ajax({type: "GET",
				url: "<?php echo $params['ajax_url']; ?>",
				dataType: "json",
				async: false,
				success: function(value){
					executeReturnValues( value);
					finished=value['finished'];
				}
			});
			
			// nochmal anstossen oder 'fertig' melden?
			if( finished==0)
				window.setTimeout("doAjaxRequests()", 10);
			else
			{
				$(".ui-progressbar-value").css("background-image", "none");
				alert("Der Sendevorgang wurde erfolgreich abgeschlossen. Sie können dieses Fenster nun schließen.");
			}
		}
	</script>
