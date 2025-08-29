<?php
include "conexion.php";
session_start();

$correo = $_POST['correo'] ?? '';
$contraseña = $_POST['contraseña'] ?? '';

if (empty($correo) || empty($contraseña)) {
    header("Location: ../login.html?error=vacio");
    exit();
}

$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    if (password_verify($contraseña, $usuario['contraseña'])) {
        if ($usuario['confirmado'] == 0) {
            header("Location: ../login.html?error=confirmar&correo=" . urlencode($correo));
            exit();
        }

        $_SESSION['usuario'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['usuario_id'] = $usuario['id'];

        // Redirigir a mi-cuenta si venía de ahí
        if (isset($_SESSION['redirigir_a_mi_cuenta'])) {
            unset($_SESSION['redirigir_a_mi_cuenta']);
            header("Location: ../paginas/mi-cuenta.php");
            exit();
        } else {
            header("Location: ../index.php");
            exit();
        }
    } else {
        header("Location: ../login.html?error=incorrecta");
        exit();
    }
} else {
    header("Location: ../login.html?error=incorrecta");
    exit();
}

$stmt->close();
$conn->close();

