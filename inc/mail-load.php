<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function


// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
     
    
    
    //Server settings
    $mail->SMTPDebug = 0;
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'aceeduplus.in@gmail.com';                     // SMTP username
    $mail->Password   = 'Redinfo1#';                             // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('info@aceeduplus.in', 'Ace EduPlus');
         // Add a recipient
    // $mail->addAddress('@example.com');               // Name is optional
    $mail->addReplyTo('info@aceeduplus.in', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    
    //echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  
}
?>