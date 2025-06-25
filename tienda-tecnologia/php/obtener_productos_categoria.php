<?php
include 'conexion.php';

$categoria = $_GET['categoria'] ?? '';

if (!$categoria) {
  echo json_encode([]);
  exit;
}

$stmt = $conn->prepare("SELECT id, nombre FROM productos WHERE categoria = ?");
$stmt->bind_param("s", $categoria);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
  $productos[] = $row;
}

echo json_encode($productos);
