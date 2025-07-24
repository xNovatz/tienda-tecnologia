<?php
session_start();
include '../conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.html');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("SELECT id, total, fecha FROM ventas WHERE usuario_id = ? ORDER BY fecha DESC");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Compras</title>
    <link rel="stylesheet" href="historial.css">
</head>
<body>

<div class="compras-container">
    <h2>📝 Historial de Compras</h2>

    <?php if ($result->num_rows > 0): ?>
    <table class="tabla-compras">
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Factura</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td>#<?= $row['id'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($row['fecha'])) ?></td>
                <td>$<?= number_format($row['total'], 2) ?></td>
                <td><a href="../pago/factura.php?venta=<?= $row['id'] ?>" class="btn-factura">Ver Factura</a></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p class="mensaje-vacio">Aún no has realizado ninguna compra.</p>
    <?php endif; ?>

    <div class="boton-regresar">
        <a href="../../index.php">⬅️ Volver al inicio</a>
    </div>
</div>

</body>
</html>
