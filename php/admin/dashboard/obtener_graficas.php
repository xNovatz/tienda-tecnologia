<?php
session_start();
include '../../conexion.php';

if ($_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    exit;
}

// Ventas por mes
$sqlVentas = "
    SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, COUNT(*) AS total 
    FROM ventas 
    GROUP BY mes 
    ORDER BY mes ASC
";
$resVentas = $conn->query($sqlVentas);

$meses = [];
$ventas = [];
while ($row = $resVentas->fetch_assoc()) {
    $meses[] = $row['mes'];
    $ventas[] = $row['total'];
}

// Usuarios por mes (fecha_registro)
$sqlUsuarios = "
    SELECT DATE_FORMAT(fecha_registro, '%Y-%m') AS mes, COUNT(*) AS total
    FROM usuarios
    GROUP BY mes
    ORDER BY mes ASC
";
$resUsuarios = $conn->query($sqlUsuarios);

$usuarios = [];
while ($row = $resUsuarios->fetch_assoc()) {
    $usuarios[] = $row['total'];
}

echo json_encode([
    "meses" => $meses,
    "ventas" => $ventas,
    "usuarios" => $usuarios
]);
