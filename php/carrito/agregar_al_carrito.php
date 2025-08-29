<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo 'No autenticado';
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$id_producto = $_POST['id_producto'] ?? null;
$cantidad = $_POST['cantidad'] ?? 1;

if (!$id_producto) {
    http_response_code(400);
    echo 'ID de producto inválido';
    exit;
}

// Verificar si el producto existe
$sqlCheck = "SELECT id FROM productos WHERE id = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("i", $id_producto);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows === 0) {
    http_response_code(404);
    echo "Producto no encontrado";
    exit;
}

// Verificar si ya está en el carrito
$sql = "SELECT * FROM carrito WHERE id_usuario = ? AND id_producto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_usuario, $id_producto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $sql = "UPDATE carrito SET cantidad = cantidad + ? WHERE id_usuario = ? AND id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $cantidad, $id_usuario, $id_producto);
} else {
    $sql = "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $id_usuario, $id_producto, $cantidad);
}

if ($stmt->execute()) {
    echo 'ok';
} else {
    http_response_code(500);
    echo 'Error: ' . $stmt->error;
}
?>
