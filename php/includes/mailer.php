<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php'; // Asegúrate de que composer instaló phpmailer

function enviarCorreoConfirmacion($destinatario, $token) {
    $mail = new PHPMailer(true);

    try {
        // Configuración servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ventas.teracomputer@gmail.com';
        $mail->Password   = 'mleu usbf jpss xjek'; // tu contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom('ventas.teracomputer@gmail.com', 'TeraComputer');
        $mail->addAddress($destinatario);

        // Contenido del email
        $mail->isHTML(true);
        $mail->Subject = 'Confirma tu cuenta en TeraComputer';

        $url = "http://localhost/tienda-tecnologia/php/usuarios/confirmar.php?token=$token";

        $mail->Body    = "
            <h2>¡Bienvenido a TeraComputer!</h2>
            <p>Gracias por registrarte, por favor confirma tu correo haciendo clic en el siguiente enlace:</p>
            <p><a href='$url'>Confirmar mi cuenta</a></p>
            <br>
            <small>Si tú no te registraste, ignora este correo.</small>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
