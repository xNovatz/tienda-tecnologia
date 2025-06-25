<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    echo "No autorizado";
    exit();
}

$id = $_SESSION['usuario_id'];
$clave_actual = $_POST['clave_actual'] ?? '';
$nueva_clave = $_POST['nueva_clave'] ?? '';

$stmt = $conn->prepare("SELECT contraseña FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!password_verify($clave_actual, $usuario['contraseña'])) {
    echo "La contraseña actual no es correcta";
    exit();
}

$nueva_hash = password_hash($nueva_clave, PASSWORD_DEFAULT);
$update = $conn->prepare("UPDATE usuarios SET contraseña = ? WHERE id = ?");
$update->bind_param("si", $nueva_hash, $id);
$update->execute();

echo "Contraseña actualizada correctamente";
