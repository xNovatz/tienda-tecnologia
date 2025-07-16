<?php
session_start();
include '../conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'mensaje' => 'No has iniciado sesión']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("
    SELECT c.id_producto, c.cantidad,
           (p.precio * (1 - p.descuento / 100)) AS precio_descuento
    FROM carrito c
    INNER JOIN productos p ON c.id_producto = p.id
    WHERE c.id_usuario = ?
");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
    $total += $row['precio_descuento'] * $row['cantidad'];
}

if (empty($productos)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'mensaje' => 'El carrito está vacío']);
    exit();
}

// Insertar venta
$stmt = $conn->prepare("INSERT INTO ventas (usuario_id, total) VALUES (?, ?)");
$stmt->bind_param("id", $usuario_id, $total);
$stmt->execute();
$venta_id = $stmt->insert_id;

// Insertar detalle_ventas
$stmtDetalle = $conn->prepare("INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");

foreach ($productos as $p) {
    $producto_id = $p['id_producto'];
    $cantidad = $p['cantidad'];
    $precio = $p['precio_descuento'];
    $subtotal = $cantidad * $precio;

    $stmtDetalle->bind_param("iiidd", $venta_id, $producto_id, $cantidad, $precio, $subtotal);
    $stmtDetalle->execute();
}

// Limpiar carrito
$conn->query("DELETE FROM carrito WHERE id_usuario = $usuario_id");

echo json_encode(['ok' => true, 'mensaje' => 'Venta registrada correctamente', 'venta_id' => $venta_id]);
