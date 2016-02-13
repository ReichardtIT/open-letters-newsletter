<?php
/**
 * Open-Letters Newslettersystem SMTP tester: This file will help You checking
 * Your SMTP configuration. The configuration for sending emails will be loaded
 * from config.inc.php and used for connection to the email server. On successful
 * connection an email will be send.
 * 
 * Please check Your email options in config/config.inc.php, change the receiver
 * email address below and call this file with Your webbrowser like 
 * http://www.example.com/newsletter/extras/phpMailerSmtpTest.php .
 */
    error_reporting(E_ALL);

    /** definiert, dass alle Dateien aus Sicht eines Unterverzeichnisses
     * eingebunden werden */
    define( "ROOT_PATH", "../");

    require_once( realpath(ROOT_PATH).'/include/newsletter.class.php');
    require_once( realpath(ROOT_PATH)."/config/config.inc.php");
    
    $ports = array( 25, 587, 465);
    $secures = array("ssl","", "tls");

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
            span.success{
                color: green;font-weight: bold;
            }
            span.error{
                color:red;font-weight: bold;
            }
        </style>
    </head>

    <body>
        <div id="container">
        <h1>Willkommen im Open-Letters SMTP-Hilfsprogramm!</h1>
        Diese Datei hilft Ihnen bei der Einrichtung des SMTP-Zugangs in der
        Datei config.inc.php. Die dort eingegebenen Daten werden für den
        Aufbau einer Verbindung zum Email-Server und zum Versand einer Test-
        Email genutzt.</br></br>
        Das Programm nutzt für die Optionen SMTP_PORT und SMTP_SECURITY nicht
        die hinterlegten Werte, sondern probiert diese Werte durch:<dl>
            <dt>Ports</dt><dd><?php echo implode(",", $ports) ?></dd>
            <dt>Security</dt><dd><?php echo implode(",", $secures) ?></dd>
        </dl>

        <?php
        foreach( $ports as $port){
            foreach( $secures as $sec){
                echo "<h3>Prüfe die Konfiguration mit Port ".$port." und Security '".$sec."'</h3>";
                if( check_configuration($port, $sec)){
                    exit(0);
                }
            }
        }
        ?>
        </div>
    </body>
</html>
<?php

function check_configuration( $port, $security){
    
    flush();
    try {
        $mail = Newsletter::initMailer($port, $security);
        $mail->Timeout = 3; // seconds
        $mail->SMTPDebug = 2;
        
        $mail->clearAddresses();
        $mail->addAddress(SENDER_ADDRESS, SENDER_NAME);
        $mail->addReplyTo(SENDER_ADDRESS, SENDER_NAME);
        $mail->setFrom( SENDER_ADDRESS, SENDER_NAME);
        $mail->Subject = "Open-Letters NewsletterSoftware => Test-Email";
        $mail->Body = "Dear Newsletter owner,\n\nThis mail was sent to You by file "
            .__FILE__." on Your Webspace. That You can read this mail means the"
            . " email configuration in Your config/config.inc.php is right.\n\n"
            . "best regards\nOpen-Letters";
        
        $mail->smtpConnect();
        if( !$mail->send()){
            throw new Exception("ERROR sending an Email: ".$mail->ErrorInfo, "666");
        }else{
            echo "This configuration DID work: Port=".$port." and Security="
                .$security."!!!<br>"."The inbox of ".SENDER_ADDRESS." should "
                . "have a new email right now. If this email was not received, "
                . "please check Your spam folder and configuration.<br>"
                . "<span class='success'>Success, test mail was sent!</span><br>";
            return true;
        }

    } catch (Exception $e) {

        echo "Your configuration did NOT work: Port=".$port." and Security=".$security."<br>";
        echo $e->getMessage();
        echo "<span class='error'>Sorry, this try did not work.</span><br><br>";
        return false;
    }
}