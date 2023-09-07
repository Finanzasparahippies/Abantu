<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 2;  // Habilita la salida de depuración detallada
    $mail->isSMTP(); // Configura el correo utilizando SMTP
    $mail->Host = 'smtp-sendpulse.com';  // Especifica el servidor SMTP
    $mail->SMTPAuth = true;  // Habilita la autenticación SMTP
    $mail->Username = 'felicesdeayudar@abantu.mx';  // Tu usuario SMTP
    $mail->Password = 'G2pZHDctRA4qAk7';  // Tu contraseña SMTP
    $mail->SMTPSecure = 'tls';  // Habilita el cifrado TLS, `ssl` también es aceptado
    $mail->Port = 2525;  // El puerto TCP al que se conecta

    // Configura los destinatarios
    $mail->setFrom('felicesdeayudar@gmail.com', 'Equipo Abantu');
    $mail->addAddress('jsaul_7@hotmail.com', 'Saul villegas');

    // Contenido del correo electrónico
    $mail->isHTML(true);  // Configura el formato del correo electrónico como HTML
    $mail->Subject = 'Aquí va el asunto';
    $mail->Body    = 'Este es el cuerpo en HTML del mensaje <b>en negrita!</b>';
    $mail->AltBody = 'Este es el cuerpo en texto plano para clientes de correo que no son HTML';

    $mail->send();
    echo 'El mensaje ha sido enviado';
} catch (Exception $e) {
    echo "El mensaje no pudo ser enviado. Error de correo: {$mail->ErrorInfo}";
}
?>