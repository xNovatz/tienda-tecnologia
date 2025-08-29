<?php
session_start();
require_once "../conexion.php";

// Validar sesión
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Debes iniciar sesión para reseñar"]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$producto_id = $_POST['producto_id'] ?? null;
$calificacion = $_POST['calificacion'] ?? null;
$comentario = $_POST['comentario'] ?? '';

if (!$producto_id || !$calificacion) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

$sql = "INSERT INTO reseñas (usuario_id, producto_id, calificacion, comentario) 
        VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiis", $usuario_id, $producto_id, $calificacion, $comentario);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al guardar reseña"]);
}
