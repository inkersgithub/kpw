<?php

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

$EMAILOBJ = new PHPMailer(true);

    $EMAILOBJ->SMTPDebug = 1;
    $EMAILOBJ->isSMTP();
    $EMAILOBJ->Host= 'smtp.gmail.com';
    $EMAILOBJ->SMTPAuth   = true;
    $EMAILOBJ->Username   = 'info@ashair.in';
    $EMAILOBJ->Password   = 'Redinfo1#';
    $EMAILOBJ->SMTPSecure = 'tls';
    $EMAILOBJ->Port       = 465;
    $EMAILOBJ->isHTML(true);
    $EMAILOBJ->setFrom('info@ashair.in', 'Mailer');
    
    $EMAILOBJ->addAddress('punyaashokkm@gmail.com', 'punya');
   
    $EMAILOBJ->Subject = 'Here is the subject';
    $EMAILOBJ->Body    = 'This is the HTML message body <b>in bold!</b>';
    $EMAILOBJ->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $EMAILOBJ->send();
    
