<?php
session_start();
include __DIR__ . "/../conexion.php";
require __DIR__ . "/../includes/mailer.php";


$correo = $_GET['correo'] ?? '';

if (empty($correo)) {
    $_SESSION['mensaje_error'] = "❌ Correo inválido.";
    header("Location: ../../login.html");
    exit;
}

$sql = "SELECT id, nombre, confirmado FROM usuarios WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    if ($usuario['confirmado'] == 1) {
        $_SESSION['mensaje_ok'] = "✅ Tu cuenta ya estaba confirmada.";
        header("Location: ../../login.html");
        exit;
    }

    // Generar nuevo token
    $token = bin2hex(random_bytes(16));
    $update = $conn->prepare("UPDATE usuarios SET token_confirmacion = ? WHERE id = ?");
    $update->bind_param("si", $token, $usuario['id']);
    $update->execute();

    // Enlace de confirmación
    $link = "http://localhost/tienda-tecnologia/php/usuarios/confirmar.php?token=$token";

    // Enviar correo
    // Enviar correo de confirmación
    if (enviarCorreoConfirmacion($correo, $token)) {
        $_SESSION['mensaje_ok'] = "📩 Se ha reenviado el correo de confirmación a <b>$correo</b>.";
    } else {
        $_SESSION['mensaje_error'] = "❌ Error al enviar el correo de confirmación.";
    }
    header("Location: ../../login.html");
    exit;


    $_SESSION['mensaje_ok'] = "📩 Se ha reenviado el correo de confirmación a <b>$correo</b>.";
    header("Location: ../../login.html");
    exit;

} else {
    $_SESSION['mensaje_error'] = "❌ No existe un usuario con ese correo.";
    header("Location: ../../login.html");
    exit;
}
