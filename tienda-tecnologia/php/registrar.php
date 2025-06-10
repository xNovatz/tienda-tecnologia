<?php
include "conexion.php";

// Obtener datos del formulario
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$contraseña = $_POST['contraseña'] ?? '';

// Validar longitud mínima de contraseña
if (strlen($contraseña) < 4) {
    header("Location: ../registro.html?error=1");
    exit();
}

// Hashear contraseña
$contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);

// Insertar en la base de datos
$sql = "INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nombre, $correo, $contraseñaHash);

if ($stmt->execute()) {
    header("Location: registro-exitoso.php");
    exit();
} else {
    echo "Ocurrió un error al registrar el usuario. Inténtalo de nuevo. " . $conn->error;
}

$stmt->close();
$conn->close();
?>
