<?php
/**
 * Diese Datei ermoeglicht Ihnen die Ermittlung des absoluten Verzeichnis-
 * Pfades, unter dem Ihre Software auf dem Webserver laeuft. Dies benoetigen
 * Sie beispielsweise zur Einrichtung eines Htaccess-Verzeichnis-Schutzes.
 */
// 0. Manual ausgeben:
//********************* 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Htaccess-Hilfedatei</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style type="text/css">
            body{ background-color: #eee; color: #999; font-size: 12px;}
            hr{ border: 1px solid #999; border-bottom:0px; border-left: 0px;
                border-right: 0px; margin: 15px 0px;}
            #container{	border:1px solid #999; width:800px; margin: 20px auto;
                        padding: 15px; background-color: #fff;}

            fieldset{ margin: 10px 30px; border: 1px solid #999;
                      background-color: #eee;
                      padding: 10px; padding-left: 30px;}
            legend{ border: 1px solid #999; background-color: #999;
                    padding: 5px 10px; font-weight: bold; color: #fff;}
            </style>
        </head>

        <body>
            <div id="container">
            <h1>Willkommen im Open-Letters Htaccess-Hilfsprogramm!</h1>
            Diese Datei hilft Ihnen bei der Einrichtung eines Htaccess-Verzeichnis-
            Schutzes für die Admin-Oberfläche (Verzeichnis "admin"). Es generiert Ihnen
            eine Datei .htaccess und eine Datei .htpasswd, die Sie bitte beide im
            Verzeichnis "admin" der Newslettersoftware ablegen.

            <?php
            // 1. SERVER_ARRAY ausgeben:
            //*************************** ?>
            <hr noshade />

            <h2>Setup der Datei .htaccess</h2>
            Bitte bearbeiten Sie die Datei ".htaccess" im Verzeichnis "admin" mit
            einem Editor (z.B: <a href="http://www.geany.org/">Geany</a> so, dass
            Sie die folgenden Einträg enthält:<br />

            <?php 
                $pathinfo = pathinfo( __FILE__);
                $path = realpath( $pathinfo['dirname']."/..");
                $filename = $pathinfo['basename'];
            ?>

            <fieldset>
                <legend>Inhalt der Datei ./admin/.htaccess</legend>
                AuthType basic<br />
                AuthName "Administrationsoberfläche des Newselettersystems"<br />
                AuthUserFile <?php echo $path; ?>/admin/.htpasswd<br />
                Require valid-user
            </fieldset>

            <br />
            <hr noshade />
            
            <h2>Setup der Datei .htpasswd</h2>
            Das folgende Formular erlaubt Ihnen, den Inhalt / die Inhalte der Datei 
            <?php echo $path; ?>/admin/.htpasswd zu erzeugen. Denken Sie sich bitte einen Benutzernamen
            und ein Passwort aus und tragen Sie diese in dasd Formular ein:

            <form method="post" action="<?php echo $filename; ?>">
                <fieldset>
                    <legend>Eingabe der gewünschten Zugangsdaten für den Verzeichnisschutz</legend>
                    <table border="0" cellspacing="10" cellpadding="0">
                    <tr><td>Benutzername</td><td><input type="text" name="user" /></td></tr>
                    <tr><td>Passwort</td><td><input type="password" name="pwd_01" /></td></tr>
                    <tr><td>Passwort (wiederholen)</td><td><input type="password" name="pwd_02" /></td></tr>
                    <tr><td colspan="2" align="right">
                        <input type="submit" name="Eintrag erzeugen" value="Eintrag erzeugen" /></td></tr>
                    </table>
                </fieldset>
            </form>

            <?php
            if( is_array( $_POST)
            && array_key_exists("user", $_POST)
            && array_key_exists("pwd_01", $_POST)
            && array_key_exists("pwd_02", $_POST)
            && strlen( trim($_POST['user']))>0
            && strlen( trim($_POST['pwd_01']))>0
            && strlen( trim($_POST['pwd_02']))>0
            && trim($_POST['pwd_02'])==trim($_POST['pwd_01'])): ?>

                Aus Ihren Eingaben wurde die folgende Zeile generiert. Bitte tragen Sie
                diese in die Datei ./admin/.htpasswd ein. Sie können darüber hinaus
                weitere Einträge erzeugen und in diese Datei kopieren.
                <fieldset>
                    <legend>Inhalt der Datei ./admin/.htpasswd</legend>
                    <strong><?php echo $_POST['user'].":".crypt( trim($_POST['pwd_01'])); ?></strong>
                    <br />
                </fieldset>

            <?php endif; ?>
        </div>
    </body>
</html>