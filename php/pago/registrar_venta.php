<?php
session_start();
include '../conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'mensaje' => 'No has iniciado sesión']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener productos del carrito
$stmt = $conn->prepare("
    SELECT c.id_producto AS producto_id, c.cantidad,
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

$metodo_pago = $_POST['metodo'] ?? 'tarjeta';

// Insertar venta
$stmt = $conn->prepare("INSERT INTO ventas (usuario_id, total) VALUES (?, ?)");
if (!$stmt) {
    echo json_encode(['ok' => false, 'mensaje' => 'Error al preparar venta: ' . $conn->error]);
    exit();
}
$stmt->bind_param("id", $usuario_id, $total);
if (!$stmt->execute()) {
    echo json_encode(['ok' => false, 'mensaje' => 'Error al registrar venta: ' . $stmt->error]);
    exit();
}
$venta_id = $stmt->insert_id;

// Insertar detalle de venta
$stmtDetalle = $conn->prepare("INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
if (!$stmtDetalle) {
    echo json_encode(['ok' => false, 'mensaje' => 'Error preparando detalle venta: ' . $conn->error]);
    exit();
}

foreach ($productos as $p) {
    $subtotal = $p['precio_descuento'] * $p['cantidad'];
    $stmtDetalle->bind_param("iiidd", $venta_id, $p['producto_id'], $p['cantidad'], $p['precio_descuento'], $subtotal);
    if (!$stmtDetalle->execute()) {
        echo json_encode(['ok' => false, 'mensaje' => 'Error al registrar detalle: ' . $stmtDetalle->error]);
        exit();
    }
}

// Vaciar carrito
$conn->query("DELETE FROM carrito WHERE id_usuario = $usuario_id");

echo json_encode([
    'ok' => true,
    'venta_id' => $venta_id
]);
?>
