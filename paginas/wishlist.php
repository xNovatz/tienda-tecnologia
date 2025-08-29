<?php
session_start();
include __DIR__ . '/../php/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
  header("Location: /tienda-tecnologia/login.html");
  exit;
}

$id_usuario = $_SESSION['usuario_id'];

$usuarioLogueado = $_SESSION['usuario'] ?? null;
$rolUsuario = $_SESSION['rol'] ?? 'usuario';

$pageTitle = "Mis Favoritos - TeraComputer";
$rutaBase = "../";
$pageType = "categoria";

?>
<!DOCTYPE html>
<html lang="es">

<link rel="stylesheet" href="../css/estilos.css"/>
<link rel="stylesheet" href="../css/cerrar-sesion.css">
<link rel="stylesheet" href="../css/carrito.css">
<link rel="stylesheet" href="../css/wishlist.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link rel="icon" href="../icon.png" type="image/x-icon">

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

      <!-- 🔎 Buscador en el centro -->
      <div class="buscador-header">
        <form id="formBuscador" onsubmit="return false;">
          <input type="text" id="inputBuscador" placeholder="Buscar en toda la tienda..." required>
          <button type="submit"><i class="fa fa-search"></i></button>
        </form>
      </div>

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

<main class="wishlist-page">
  <div class="wishlist-layout">

    <!-- Card título -->
    <div class="wishlist-titulo-card">
      <h1>❤️ Mis Favoritos</h1>
    </div>

    <!-- Grid de favoritos -->
    <div class="wishlist-grid">
      <?php
        $sql = "SELECT p.* 
                FROM wishlist w
                JOIN productos p ON w.producto_id = p.id
                WHERE w.usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0):
          while ($row = $result->fetch_assoc()):
            $precioFinal = $row['precio_descuento'] ?? $row['precio'];
      ?>
        <div class="wishlist-card">
          <a href="/tienda-tecnologia/paginas/producto.php?id=<?= $row['id'] ?>" class="wishlist-imagen">
            <img src="../assets/productos/<?= $row['categoria'] ?>/<?= $row['imagen']; ?>" 
                 alt="<?= $row['nombre']; ?>">
          </a>

          <div class="wishlist-info">
            <h2><?= $row['nombre']; ?></h2>
            <p class="wishlist-descripcion"><?= $row['descripcion']; ?></p>

            <div class="wishlist-precio">
              <?php if ($row['precio_descuento'] !== null): ?>
                <span class="tachado">Antes $<?= number_format($row['precio'], 2); ?></span>
                <span class="oferta">Ahora $<?= number_format($row['precio_descuento'], 2); ?></span>
              <?php else: ?>
                <span class="estandar">$<?= number_format($row['precio'], 2); ?></span>
              <?php endif; ?>
            </div>

            <div class="wishlist-botones">
              <button class="btn-agregar"
                data-id="<?= $row['id']; ?>"
                data-nombre="<?= htmlspecialchars($row['nombre']); ?>"
                data-precio="<?= number_format($precioFinal, 2, '.', ''); ?>"
                data-imagen="/tienda-tecnologia/assets/productos/<?= $row['categoria'] ?>/<?= $row['imagen']; ?>"
                data-tipo="productos">
                🛒 Agregar al carrito
              </button>

              <button class="btn-favorito-remove" data-id="<?= $row['id']; ?>">
                💔 Quitar
              </button>
            </div>
          </div>
        </div>
      <?php endwhile; else: ?>
        <p class="wishlist-vacio">Aún no tienes productos en tu lista de favoritos.</p>
      <?php endif; ?>
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
  <script src="../js/carrito.js"></script>  
  <script src="../php/wishlist/wishlist.js"></script>

  <script src="/tienda-tecnologia/js/agregar-productos.js"></script>
  <script src="/tienda-tecnologia/js/acciones-producto.js"></script>
  <script src="/tienda-tecnologia/js/modificar-producto.js"></script>
  <script src="/tienda-tecnologia/php/buscador/buscador.js"></script>

</body>
</html>
