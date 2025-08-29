<?php
header('Content-Type: application/json');
include 'conexion.php';

$id = $_GET['id'] ?? null;

if (!$id) {
  echo json_encode(['exito' => false, 'mensaje' => 'ID no proporcionado']);
  exit;
}

$query = "SELECT * FROM productos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  echo json_encode(['exito' => true, 'producto' => $row]);
} else {
  echo json_encode(['exito' => false, 'mensaje' => 'Producto no encontrado']);
}
