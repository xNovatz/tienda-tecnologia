<?php
session_start();
include '../../conexion.php';

if ($_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    exit;
}

$totalVentas = $conn->query("SELECT COUNT(*) FROM ventas")->fetch_row()[0];
$totalUsuarios = $conn->query("SELECT COUNT(*) FROM usuarios")->fetch_row()[0];
$totalIngresos = $conn->query("SELECT IFNULL(SUM(total),0) FROM ventas")->fetch_row()[0];

echo json_encode([
  'total_ventas' => $totalVentas,
  'total_usuarios' => $totalUsuarios,
  'total_ingresos' => $totalIngresos
]);
