<?php
require_once "../conexion.php";

$producto_id = $_GET['producto_id'] ?? null;
if (!$producto_id) {
    http_response_code(400);
    echo json_encode(["error" => "Producto no válido"]);
    exit;
}

$sql = "SELECT AVG(calificacion) as promedio FROM reseñas WHERE producto_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $producto_id);
$stmt->execute();
$res = $stmt->get_result();
$promedio = $res->fetch_assoc()['promedio'] ?? null;

echo json_encode(["promedio" => $promedio]);
