<?php
include __DIR__ . '/../conexion.php';

$termino = trim($_GET['q'] ?? '');

if ($termino === '') {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id, nombre, imagen, categoria, 
        IFNULL(precio_descuento, precio) AS precio
        FROM productos
        WHERE nombre LIKE ? OR descripcion LIKE ?
        LIMIT 10";

$like = "%$termino%";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode($productos);
