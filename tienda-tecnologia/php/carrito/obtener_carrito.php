<?php
session_start();
include '../conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

$sql = "
    SELECT p.id, p.nombre, p.precio, p.imagen, p.descuento, p.categoria, c.cantidad
    FROM carrito c
    INNER JOIN productos p ON c.id_producto = p.id
    WHERE c.id_usuario = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$carrito = [];

while ($row = $result->fetch_assoc()) {
    $precioDescuento = $row['precio'] * (1 - $row['descuento'] / 100);

    $rutaImagen = "/tienda-tecnologia/assets/productos/{$row['categoria']}/{$row['imagen']}";

    $carrito[] = [
        'id' => $row['id'],
        'nombre' => $row['nombre'],
        'precio' => floatval($precioDescuento),
        'precioOriginal' => floatval($row['precio']),
        'imagen' => $rutaImagen,
        'cantidad' => intval($row['cantidad']),
        'tipo_producto' => 'productos'
    ];
}

echo json_encode($carrito);
?>
