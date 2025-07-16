<?php
session_start();
include '../conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$sql = "DELETE FROM carrito WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();

echo 'ok';
?>
