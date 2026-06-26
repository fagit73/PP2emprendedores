<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Configuración SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'pruebas.eabc@gmail.com';
    $mail->Password = 'uhmn fjou oglf vjgm';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Remitente y destinatario
    $mail->setFrom('pruebas.eabc@gmail.com', 'Prueba XAMPP');
    $mail->addAddress('champireynoso@hotmail.com');

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Test desde XAMPP';
    $mail->Body = '🚀 Funciona el envío de mail desde localhost!';

    $mail->send();
    echo "✅ Mail enviado correctamente";
} catch (Exception $e) {
    echo "❌ Error: {$mail->ErrorInfo}";
}