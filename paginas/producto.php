<?php
session_start();
include __DIR__ . '/../php/conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
  die("Producto no encontrado");
}

// Obtener producto
$query = "SELECT * FROM productos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if (!$producto) {
  die("Producto no encontrado");
}

// Obtener promedio de calificaciones
$queryProm = "SELECT AVG(calificacion) as promedio FROM reseñas WHERE producto_id = ?";
$stmtProm = $conn->prepare($queryProm);
$stmtProm->bind_param("i", $id);
$stmtProm->execute();
$resProm = $stmtProm->get_result();
$promedio = $resProm->fetch_assoc()['promedio'] ?? null;


$usuarioLogueado = $_SESSION['usuario'] ?? null;
$rolUsuario = $_SESSION['rol'] ?? null;

$pageTitle = $producto['nombre'] . " - TeraComputer";
$rutaBase = "../";
$pageType = "producto";
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($producto['nombre']) ?> - TeraComputer</title>
  <link rel="stylesheet" href="../css/producto.css">
  <link rel="stylesheet" href="../css/estilos.css"/>
  <link rel="stylesheet" href="../css/cerrar-sesion.css">
  <link rel="stylesheet" href="../css/carrito.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="icon" href="../icon.png" type="image/x-icon">
</head>

<body>
  <!-- Top bar -->
  <div class="top-bar">
    <span>
      <?php
        if ($usuarioLogueado) {
          echo "¡Bienvenido, " . htmlspecialchars($usuarioLogueado) . "!";
        } else {
          echo "Bienvenido a nuestra tienda tecnológica";
        }
      ?>
    </span>
    <div class="idiomas">
      <?php if ($usuarioLogueado): ?>
        <a href="#" id="btnCerrarSesion" class="btn-cerrar-sesion">
          <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
        </a>
      <?php else: ?>
        <a href="../login.html" class="cuenta-link"><i class="fa-regular fa-user"></i> Iniciar Sesión</a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Header principal -->
  <header class="main-header">
      <a href="../index.php" class="logo">
        <h1>TERA<span>COM</span>PUTER</h1>
      </a>

      <?php if ($rolUsuario === 'admin'): ?>
        <!-- Botón para abrir el modal (solo admin) -->
        <button id="abrirModalAgregar" class="btn-agregar-producto">Agregar un producto</button>
      <?php endif; ?>

      <div class="iconos-header">
        <div class="iconos-header-carrito">
          <?php if ($usuarioLogueado): ?>
              <?php if ($rolUsuario === 'admin'): ?>
                <div class="dropdown-admin">
                  <button class="dropbtn">⚙ Administrar ▾</button>
                  <div class="dropdown-content">
                    <a href="/tienda-tecnologia/php/admin/usuarios/usuarios.php">👥 Gestionar Usuarios</a>
                    <a href="/tienda-tecnologia/php/admin/dashboard/dashboard.php">📊 Dashboard</a>
                  </div>
                </div>
              <?php else: ?>
                <a href="/tienda-tecnologia/paginas/mi-cuenta.php">👤 Mi cuenta</a>
                <a href="/tienda-tecnologia/php/compras/historial.php">📝 Mis Compras</a>
                <a href="/tienda-tecnologia/paginas/wishlist.php">❤️ Mis Favoritos</a>
              <?php endif; ?>
          <?php else: ?>
              <a href="/tienda-tecnologia/php/login.php">👤 Mi cuenta</a>
          <?php endif; ?>

          <!-- Ícono del carrito -->
          <a href="#" id="abrir-carrito">
            <i class="fa-solid fa-cart-shopping"></i> Carrito (<span id="contador-carrito">0</span>)
          </a>

          <div class="carrito-contenedor" id="carrito-contenedor">
            <h3>🛒 Carrito</h3>

            <!-- Contenedor con scroll solo para la lista -->
            <div class="carrito-lista-wrapper">
              <ul id="carrito-lista"></ul>
            </div>

            <!-- Pie con total y botón -->
            <div class="carrito-footer">
              <p>Total: $<span id="carrito-total">0</span></p>
              <button id="vaciar-carrito">Vaciar carrito</button>
              <button id="btnPagar" class="btn-pagar">Pagar</button>
            </div>
          </div>

          <!-- FONDO OSCURO -->
          <div id="fondo-oscuro" class="fondo-oscuro"></div>
      </div>
  </div>
</header>

<main class="producto-page">
  <div class="producto-layout">

    <!-- Card producto -->
    <div class="producto-card">
      <div class="producto-detalle-box">
        <div class="producto-imagen">
          <img src="../assets/productos/<?= $producto['categoria'] ?>/<?= $producto['imagen']; ?>" 
               alt="<?= $producto['nombre'] ?>">
        </div>

        <div class="producto-info">
          <h1><?= $producto['nombre'] ?></h1>

          <!-- Promedio estrellas -->
          <div class="promedio-estrellas">
            <?php if ($promedio): ?>
              <?= str_repeat("⭐", round($promedio)) ?>
              <span>(<?= number_format($promedio, 1) ?>/5)</span>
            <?php else: ?>
              <span>Sin reseñas todavía</span>
            <?php endif; ?>
          </div>

          <p><?= $producto['descripcion'] ?></p>

          <div class="producto-precio">
            <?php if ($producto['precio_descuento'] !== null): ?>
              <span class="tachado">Antes $<?= number_format($producto['precio'], 2); ?></span>
              <span class="oferta">Ahora $<?= number_format($producto['precio_descuento'], 2); ?></span>
            <?php else: ?>
              <span class="estandar">$<?= number_format($producto['precio'], 2); ?></span>
            <?php endif; ?>
          </div>

          <div class="producto-botones">
            <?php 
              $precioFinal = $producto['precio_descuento'] ?? $producto['precio']; 
            ?>
            <button class="btn-agregar"
              data-id="<?= $producto['id']; ?>"
              data-nombre="<?= htmlspecialchars($producto['nombre']); ?>"
              data-precio="<?= number_format($precioFinal, 2, '.', ''); ?>"
              data-precio-original="<?= number_format($producto['precio'], 2, '.', ''); ?>"
              data-imagen="/tienda-tecnologia/assets/productos/<?= $producto['categoria'] ?>/<?= $producto['imagen']; ?>"
              data-tipo="productos">
              🛒 Agregar al carrito
            </button>


            <button class="btn-favorito" data-id="<?= $producto['id']; ?>">❤️ Favorito</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Card reseñas -->
    <div class="producto-reseñas-card">
      <h2>Opiniones de clientes</h2>
      <?php if ($usuarioLogueado): ?>
        <form class="form-reseña" id="form-reseña">
          <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
          <label>Calificación:</label>
          <select name="calificacion" required>
            <option value="5">⭐⭐⭐⭐⭐ Excelente</option>
            <option value="4">⭐⭐⭐⭐ Muy bueno</option>
            <option value="3">⭐⭐⭐ Regular</option>
            <option value="2">⭐⭐ Malo</option>
            <option value="1">⭐ Pésimo</option>
          </select>
          <textarea name="comentario" placeholder="Escribe tu opinión..." required></textarea>
          <button type="submit">Enviar reseña</button>
        </form>
      <?php else: ?>
        <p><a href="/tienda-tecnologia/login.html">Inicia sesión</a> para dejar una reseña</p>
      <?php endif; ?>

      <div class="producto-lista-reseñas" id="lista-reseñas"></div>
    </div>

  </div>
</main>


  <!-- Modal de confirmación de cierre de sesión -->
  <div id="modalCerrarSesion" class="modal-cerrar">
    <div class="modal-content-cerrar">
      <h2>¿Deseas cerrar sesión?</h2>
      <div class="modal-buttons">
        <button id="confirmarCerrarSesion" class="btn-confirmar">Sí</button>
        <button id="cancelarCerrarSesion" class="btn-cancelar">No</button>
      </div>
    </div>
  </div>

  <script src="../js/script.js"></script>
  <script src="../php/reseñas/reseñas.js"></script>
  <script src="../js/carrito.js"></script>
  <script src="../php/reseñas/producto.js"></script>
  <script src="../php/wishlist/wishlist.js"></script>

  <script src="/tienda-tecnologia/js/agregar-productos.js"></script>
  <script src="/tienda-tecnologia/js/acciones-producto.js"></script>
  <script src="/tienda-tecnologia/js/modificar-producto.js"></script>

</body>
</html>
