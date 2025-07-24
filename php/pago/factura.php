<?php
session_start();
include '../conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.html');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$id_venta = $_GET['venta'] ?? null;

if (!$id_venta) {
    die("<p>No se especificó una venta válida.</p>");
}

// Validar que la venta pertenece al usuario
$stmt = $conn->prepare("SELECT * FROM ventas WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id_venta, $usuario_id);
$stmt->execute();
$venta = $stmt->get_result()->fetch_assoc();

if (!$venta) {
    die("<p>No se encontró la venta o no tienes acceso a ella.</p>");
}

// Detalle de venta
$stmt_detalle = $conn->prepare("
    SELECT dv.*, p.nombre, p.imagen, p.categoria 
    FROM detalle_ventas dv
    INNER JOIN productos p ON dv.producto_id = p.id
    WHERE dv.venta_id = ?
");
$stmt_detalle->bind_param("i", $id_venta);
$stmt_detalle->execute();
$detalles = $stmt_detalle->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <link rel="stylesheet" href="factura.css">
</head>
<body>
<div class="factura-container">
    <div class="factura-header">
        <h2>Factura - TeraComputer</h2>
        <p>ID Venta: <?= htmlspecialchars($venta['id']) ?> | Fecha: <?= htmlspecialchars($venta['fecha']) ?></p>
    </div>

    <div class="factura-datos">
        <p><strong>Total pagado:</strong> $<?= number_format($venta['total'], 2) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Imagen</th>
                <th>Precio unitario</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $detalles->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><img src="../../assets/productos/<?= $row['categoria'] ?>/<?= $row['imagen'] ?>" style="width:50px; border-radius:6px;"></td>
                <td>$<?= number_format($row['precio_unitario'], 2) ?></td>
                <td><?= $row['cantidad'] ?></td>
                <td>$<?= number_format($row['subtotal'], 2) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="total">Total pagado: $<?= number_format($venta['total'], 2) ?></div>

    <div style="text-align:center;" class="acciones-factura">
        <button onclick="window.print()" class="btn-imprimir">🖨️ Imprimir Factura</button>
        <button onclick="window.location.href='../../index.php'" class="btn-volver">⬅️ Volver al inicio</button>
    </div>
</div>
</body>
</html>
