<?php
session_start();
include '../../conexion.php';

if ($_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    exit;
}

$stmt = $conn->query("
    SELECT v.id, v.total, v.fecha, u.nombre AS usuario 
    FROM ventas v 
    INNER JOIN usuarios u ON v.usuario_id = u.id 
    ORDER BY v.fecha DESC 
    LIMIT 5
");

$ventas = [];
while ($row = $stmt->fetch_assoc()) {
    $ventas[] = $row;
}

echo json_encode($ventas);
