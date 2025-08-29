<?php
include "conexion.php";
include __DIR__ . "/includes/mailer.php";

// Datos del formulario
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$contraseña = $_POST['contraseña'] ?? '';

// Validación básica
if (strlen($contraseña) < 4) {
    header("Location: ../registro.html?error=1");
    exit();
}

$contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);

// Generar token único
$token = bin2hex(random_bytes(32));

$sql = "INSERT INTO usuarios (nombre, correo, contraseña, confirmado, token_confirmacion) VALUES (?, ?, ?, 0, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nombre, $correo, $contraseñaHash, $token);

if ($stmt->execute()) {
    // Enviar correo de confirmación
    enviarCorreoConfirmacion($correo, $token);

    // Redirigir al mensaje de registro exitoso
    header("Location: registro-exitoso.php");
    exit();
} else {
    echo "Error al registrar usuario: " . $conn->error;
}


$stmt->close();
$conn->close();
?>
