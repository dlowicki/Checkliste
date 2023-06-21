<?php

if(isset($_GET['upnName']) && isset($_GET['type']) && isset($_GET['attachment']))
{
    require_once('PHPMailer-master/src/PHPMailer.php');

    $mail = new PHPMailer();
    
    // Settings
    $mail->IsSMTP();
    $mail->CharSet = 'UTF-8';
    
    $mail->Host       = "exchange.jaeger.local";    // SMTP server example
    $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = false;                  // enable SMTP authentication
    $mail->Port       = 25;                    // set the SMTP port for the GMAIL server
    
    // Content
    $mail->setFrom('automatic@schrauben-jaeger.de');
    $mail->addAddress('helpdesk@schrauben-jaeger.de');
    
    $mail->isHTML(true);                       // Set email format to HTML
    $mail->Subject = $_GET['type'] . ' von ' . $_GET['upnName'];
    $mail->Body    = 'Siehe Anhang';
    $mail->AddAttachment($_GET['attachment']);
    
    $mail->send();
}



?>