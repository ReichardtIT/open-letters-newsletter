Open-Letters Newslettersystem: Newsletter-Template-Verzeichnis
***************************************************************

Was ist ein "Template"?
------------------------
	Die Klasse NewsletterTemplate durchsucht dieses Verzeichnis nach sogenannten
	Templates fuer die zu erstellenden Newsletter. Dabei handelt es sich um HTML-
	Dateien die statt der Newsletter-Eintraegen Platzhaltertexte enthalten,
	beispielsweise in der Art #####inhalt#####.

	Ein Newsletter-Template besteht aus 3 Dateien, die bis auf die Endung identische
	Dateinamen tragen muessen. Dabei handelt es sich um:

		* dateiname.html das Template fuer HTML-E-Mails (eine vollstaendige HTML-Datei). 
			Da Emailprogramme keine externen CSS-Dateien nachladen, sollte in dieser
			Datei ebenfalls das verwendete CSS enthalten sein.
		* dateiname.txt das Template fuer Text-E-Mails (Jeder Newsletter wird als
			HTML- UND als Text-E-Mail versendet.) mit eben den selben Platzhaltern.
		* dateiname.png das Vorschaubild des Templates für den Admin (wird während
			des Erstellens eines neuen Newsletters angezeigt)


Kann ich CSS in den Templates verwenden?
-----------------------------------------
	Natürlich können aktuelle E-Mail-Programme, die HTML-E-Emails anzeigen können,
	auch mit CSS umgehen.

	Wir empfehlen aber, das CSS direkt in das HTML-Template und NICHT in eine
	gesonderte Stylesheet-Datei auszulagern:

		<style type="text/css">
			body  { color:#333; background-color:#ccc; padding:0px; margin:0px;
					font-family:Tahoma,Helvetica,Verdana,Arial,sans-serif; }
		</style>

	Tip: Es hat sich in verschiedenen E-Mail-Clients und E-Mail-Webinterfaces als 
	sinnvoll gezeigt, die Breite des Templates auf 630px zu begrenzen.


Kann ich Bilder in das Template einbinden?
-------------------------------------------
	Sie können natürlich auch Bilder einbinden. Bedenken Sie aber, dass diese Bilder keine
	relative Pfad-Angabe entghalten darf, sondern die vollständige Internetadresse des
	Bildes enthalten muss.

	Wenn Sie Ihre Bilder in des (dafür vorgesehene Verzeichnis) "images" ablegen, so
	können Sie Ihre Bilder auf diese Weise in das Newsletter-Template einbinden:

		<img src="#####website_url#####/images/bild.jpg" alt="Name des Bildes" />

	Dabei ist #####website_url##### ein Template-Platzhalter, der vom Templatemanagement
	automatisch durch die vollständige URL des Newslettersystems ersetzt wird.