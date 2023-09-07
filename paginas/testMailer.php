<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

   require '../vendor/autoload.php';
   use PHPMailer\PHPMailer\PHPMailer;
   $mail = new PHPMailer;
   $mail->isSMTP();
   $mail->SMTPDebug = 2;
   $mail->Host = 'smtp-pulse.com';
   $mail->Port = 2525;
   $mail->SMTPAuth = true;
   $mail->Username = 'felicesdeayudar@abantu.mx';
   $mail->Password = 'G2pZHDctRA4qAk7';
   $mail->setFrom('felicesdeayudar@abantu.mx', 'Equipo Abantu');
   $mail->addReplyTo('felicesdeayudar@abantu.mx', 'Equipo Abantu');
   $mail->addAddress('jsaul_7@hotmail.com', 'Saul Villegas');
   $mail->Subject = 'Checking if PHPMailer works';
   $mail->msgHTML(file_get_contents('message.html'), __DIR__);
   $mail->Body = 'This is just a plain text message body';
   //$mail->addAttachment('attachment.txt');
   if (!$mail->send()) {
       echo 'Mailer Error: ' . $mail->ErrorInfo;
   } else {
       echo 'The email message was sent.';
   }
?>