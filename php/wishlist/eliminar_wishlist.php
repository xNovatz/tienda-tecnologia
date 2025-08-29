<?php
session_start();
include __DIR__ . '/../conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No has iniciado sesión']);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$id_producto = $_POST['id_producto'] ?? null;

if (!$id_producto) {
    echo json_encode(['status' => 'error', 'message' => 'Producto no válido']);
    exit;
}

$sql = "DELETE FROM wishlist WHERE usuario_id = ? AND producto_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_usuario, $id_producto);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Producto eliminado de favoritos']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'El producto no estaba en favoritos']);
}
