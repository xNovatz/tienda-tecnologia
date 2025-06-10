<?php
include "conexion.php";
session_start();

// Validar entrada
$correo = $_POST['correo'] ?? '';
$contraseña = $_POST['contraseña'] ?? '';

if (empty($correo) || empty($contraseña)) {
    header("Location: ../login.html?error=vacio");
    exit();
}

// Consulta segura con prepared statement
$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    // Verificar la contraseña
    if (password_verify($contraseña, $usuario['contraseña'])) {
        $_SESSION['usuario'] = $usuario['nombre'];
        header("Location: ../index.php");
        exit();
    } else {
        // Contraseña incorrecta
        header("Location: ../login.html?error=incorrecta");
        exit();
    }
} else {
    // Usuario no encontrado
    header("Location: ../login.html?error=incorrecta");
    exit();
}

$stmt->close();
$conn->close();
?>
