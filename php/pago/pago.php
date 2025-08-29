<?php
session_start();
include '../conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.html');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$volver = $_GET['volver'] ?? '../../index.php';

$sql = "SELECT c.id_carrito, p.id as id_producto, p.nombre, p.imagen, p.categoria,
            (p.precio * (1 - p.descuento / 100)) AS precio_descuento, 
            c.cantidad, 
            (p.precio * (1 - p.descuento / 100) * c.cantidad) AS subtotal
        FROM carrito c
        INNER JOIN productos p ON c.id_producto = p.id
        WHERE c.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago</title>
    <link rel="stylesheet" href="pago.css">
</head>
<body>
<div class="contenedor-pago">
    <h2>Resumen de compra</h2>

    <div class="tabla-productos">
        <table>
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="../../assets/productos/<?= $row['categoria'] ?>/<?= $row['imagen'] ?>" alt="<?= htmlspecialchars($row['nombre']) ?>" style="width:60px; border-radius:8px;"></td>
                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                        <td>$<?= number_format($row['precio_descuento'], 2) ?></td>
                        <td><?= $row['cantidad'] ?></td>
                        <td>$<?= number_format($row['subtotal'], 2) ?></td>
                    </tr>
                    <?php $total += $row['subtotal']; ?>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="total-pago"><strong>Total a pagar: $<?= number_format($total, 2) ?></strong></div>

    <div class="formulario-pago">
        <h3>Método de pago</h3>
        <form id="formPago">
            <label for="nombre">Nombre del titular</label>
            <input type="text" id="nombre" required placeholder="Nombre completo en la tarjeta">

            <label for="numero">Número de tarjeta</label>
            <input type="text" id="numero" required maxlength="16" pattern="\d{16}" placeholder="1234 5678 9012 3456">

            <div class="fila">
                <div>
                    <label for="expira">Fecha de expiración</label>
                    <input type="month" id="expira" required>
                </div>
                <div>
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" required maxlength="4" pattern="\d{3,4}" placeholder="123">
                </div>
            </div>

            <label for="metodo">Método</label>
            <select id="metodo" required>
                <option value="tarjeta">Tarjeta de crédito/débito</option>
                <option value="paypal">PayPal</option>
            </select>

            <button type="submit">Confirmar pago</button>
        </form>
    </div>

    <div class="acciones-pago">
        <a href="<?= htmlspecialchars($volver) ?>">← Cancelar y volver</a>
    </div>
</div>

<!-- Modal Confirmación -->
<div id="modalConfirmacionPago" class="modal-cerrar" style="display:none;">
    <div class="modal-content-cerrar">
        <h2>¿Deseas confirmar el pago?</h2>
        <div class="modal-buttons">
            <button id="btnConfirmarPago" class="btn-confirmar">Sí, pagar</button>
            <button id="btnCancelarPago" class="btn-cancelar">Cancelar</button>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast-exito" class="modal-exito" style="display:none;">
    <p id="mensaje-toast">¡Pago registrado correctamente!</p>
</div>

<script>
const modalConfirmacion = document.getElementById('modalConfirmacionPago');
const btnConfirmarPago = document.getElementById('btnConfirmarPago');
const btnCancelarPago = document.getElementById('btnCancelarPago');
const toast = document.getElementById('toast-exito');
const mensajeToast = document.getElementById('mensaje-toast');

document.getElementById('formPago').addEventListener('submit', function (e) {
    e.preventDefault();
    modalConfirmacion.style.display = 'flex';
});

btnCancelarPago.addEventListener('click', () => {
    modalConfirmacion.style.display = 'none';
});

btnConfirmarPago.addEventListener('click', async () => {
    modalConfirmacion.style.display = 'none';

    const metodo = document.getElementById('metodo').value;
    const formData = new FormData();
    formData.append('metodo', metodo);

    const res = await fetch('registrar_venta.php', {
        method: 'POST',
        body: formData
    });
    const data = await res.json();

    if (data.ok) {
        mensajeToast.textContent = `✅ Pago exitoso. Redirigiendo a la factura...`;
        toast.style.display = 'flex';
        setTimeout(() => {
            window.location.href = `factura.php?venta=${data.venta_id}`;
        }, 2500);
    } else {
        alert('❌ Error: ' + data.mensaje);
    }
});
</script>
</body>
</html>
