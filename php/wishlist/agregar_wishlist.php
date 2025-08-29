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

// Insertar en wishlist si no existe
$sql = "INSERT INTO wishlist (usuario_id, producto_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_usuario, $id_producto);

try {
    $stmt->execute();
    echo json_encode(['status' => 'success', 'message' => 'Producto agregado a favoritos']);
} catch (mysqli_sql_exception $e) {
    // Ya existe (clave única usuario_id + producto_id)
    echo json_encode(['status' => 'exists', 'message' => 'El producto ya está en favoritos']);
}
