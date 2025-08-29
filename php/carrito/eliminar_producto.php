<?php
session_start();
include '../conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$id_producto = $_POST['id_producto'] ?? null;

if (!$id_producto) {
    http_response_code(400);
    echo 'ID de producto faltante';
    exit;
}

$sql = "DELETE FROM carrito WHERE id_usuario = ? AND id_producto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_usuario, $id_producto);
$stmt->execute();

echo 'ok';
?>
