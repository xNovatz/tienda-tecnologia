<?php
session_start();
require_once "../conexion.php";

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Debes iniciar sesión"]);
    exit;
}

$id_reseña = $_POST['id'] ?? null;
$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'] ?? 'usuario';

if (!$id_reseña) {
    http_response_code(400);
    echo json_encode(["error" => "ID inválido"]);
    exit;
}

// Verificar si es dueño o admin
$sql = "SELECT usuario_id FROM reseñas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_reseña);
$stmt->execute();
$result = $stmt->get_result();
$reseña = $result->fetch_assoc();

if (!$reseña || ($reseña['usuario_id'] != $usuario_id && $rol != 'admin')) {
    http_response_code(403);
    echo json_encode(["error" => "No autorizado"]);
    exit;
}

$sql = "DELETE FROM reseñas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_reseña);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al eliminar"]);
}
