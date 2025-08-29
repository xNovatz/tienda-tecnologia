<?php
require_once "../conexion.php";

$producto_id = $_GET['producto_id'] ?? null;

if (!$producto_id) {
    http_response_code(400);
    echo json_encode(["error" => "Producto no válido"]);
    exit;
}

$sql = "SELECT r.id, r.calificacion, r.comentario, r.fecha, u.nombre 
        FROM reseñas r
        JOIN usuarios u ON r.usuario_id = u.id
        WHERE r.producto_id = ?
        ORDER BY r.fecha DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $producto_id);
$stmt->execute();
$result = $stmt->get_result();

$reseñas = [];
while ($row = $result->fetch_assoc()) {
    $reseñas[] = $row;
}

echo json_encode($reseñas);
