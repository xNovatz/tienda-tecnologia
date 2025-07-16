<?php
session_start();
$usuarioLogueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
$rolUsuario = $_SESSION['rol'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Procesadores - TeraComputer</title>
  <link rel="stylesheet" href="../css/estilos.css" />
  <link rel="stylesheet" href="../css/cerrar-sesion.css">
  <link rel="stylesheet" href="../css/carrito.css">
  <link rel="stylesheet" href="../css/paginas.css" />
  <link rel="stylesheet" href="../css/agregar-producto.css">
  <link rel="stylesheet" href="../css/acciones-producto.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
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
          <?php if ($rolUsuario === 'admin'): ?>
            <a href="/tienda-tecnologia/admin/usuarios.php">⚙ Administrar</a>
          <?php else: ?>
            <a href="/tienda-tecnologia/paginas/mi-cuenta.php">👤 Mi cuenta</a>
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

  <!-- Contenido principal -->
  <main class="contenedor">
    <!-- Sidebar de categorías -->
    <aside class="sidebar">
      <h2>Más Categorías</h2>
      <ul>
        <li><a href="/tienda-tecnologia/paginas/gabinetes.php">Gabinetes</a></li>
        <li><a href="/tienda-tecnologia/paginas/graphic.php">Tarjetas Graficas</a></li>
        <li><a href="/tienda-tecnologia/paginas/laptops.php">Laptops</a></li>
        <li><a href="/tienda-tecnologia/paginas/monitores.php">Monitores</a></li>
        <li><a href="/tienda-tecnologia/paginas/procesadores.php">Procesadores</a></li>
        <li><a href="/tienda-tecnologia/paginas/mouse.php">Mouses</a></li>
        <li><a href="/tienda-tecnologia/paginas/rams.php">Memorias RAM</a></li>
      </ul>
    </aside>

    <div class="productos-listado">
    <?php
      include __DIR__ . '/../php/conexion.php';

      $categoria = 'procesadores'; // Esta página es para 
      $query = "SELECT * FROM productos WHERE categoria = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("s", $categoria);
      $stmt->execute();
      $result = $stmt->get_result();

      while ($row = $result->fetch_assoc()):
        $precioFinal = isset($row['precio_descuento']) && $row['precio_descuento'] !== null
          ? $row['precio_descuento']
          : $row['precio'];

    ?>
      <section class="producto-detalle">
        <div class="producto-imagen">
          <img src="../assets/productos/<?php echo $row['categoria'] . '/' . $row['imagen']; ?>" alt="Producto">
        </div>
        <div class="producto-info">
          <h2 class="titulo-con-logo">
            <?php echo $row['nombre']; ?>
          </h2>
          <div class="descripcion-scroll">
            <p><?php echo $row['descripcion']; ?></p>
          </div>
          <div class="precio-boton">
            <div class="precio">
              <?php if ($row['precio_descuento'] !== null): ?>
                <span class="tachado">Antes $<?php echo number_format($row['precio'], 2); ?></span>
                <span class="oferta">Ahora $<?php echo number_format($row['precio_descuento'], 2); ?></span>
              <?php else: ?>
                <span class="estandar">$<?php echo number_format($row['precio'], 2); ?></span>
              <?php endif; ?>
            </div>
            <button class="btn-agregar"
                data-id="<?= $row['id']; ?>"
                data-nombre="<?= htmlspecialchars($row['nombre']); ?>"
                data-precio="<?= number_format($precioFinal, 2, '.', ''); ?>"
                data-precio-original="<?= number_format($row['precio'], 2, '.', ''); ?>"
                data-imagen="/tienda-tecnologia/assets/productos/<?= $row['categoria'] ?>/<?= $row['imagen']; ?>"
                data-tipo="productos">
                Agregar al carrito
            </button>
          </div>

          <?php if ($rolUsuario === 'admin'): ?>
            <div class="acciones-producto">
              <button class="btn-modificar" data-id="<?php echo $row['id']; ?>">
                <i class="fa-solid fa-pen-to-square"></i>
              </button>
              <button class="btn-eliminar" data-id="<?php echo $row['id']; ?>">
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
          <?php endif; ?>

        </div>
    </section>
    <?php endwhile; ?>
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

  <!-- Modal para agregar producto -->
    <div id="modalAgregarProducto" class="modal">
      <div class="modal-contenido">
        <span id="cerrarModalAgregar" class="cerrar">&times;</span>
        <h2>Agregar nuevo producto</h2>
        <form id="formAgregarProducto" enctype="multipart/form-data">
          <label for="nombre">Nombre:</label>
          <input type="text" name="nombre" required>
          <input type="hidden" name="id">

          <label for="descripcion">Descripción:</label>
          <textarea name="descripcion" required></textarea>

          <label for="precio">Precio:</label>
          <input type="number" name="precio" step="0.01" required>

          <label for="descuento">Descuento:</label>
          <select name="descuento">
            <option value="0">Sin descuento</option>
            <option value="5">5%</option>
            <option value="10">10%</option>
            <option value="20">20%</option>
            <option value="30">30%</option>
            <option value="40">40%</option>
            <option value="50">50%</option>
          </select>

          <input type="hidden" name="categoria" value="procesadores">

          <label for="imagen">Imagen:</label>
          <input type="file" name="imagen" accept="image/*" required>

          <button type="submit">Agregar</button>
        </form>
        <p id="mensajeAgregar" class="mensaje"></p> 
      </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div id="modalConfirmacion" class="modal-confirmacion">
      <div class="modal-confirmacion-contenido">
        <p>¿Estás seguro de que deseas eliminar este producto?</p>
        <div class="botones-confirmacion">
          <button id="btnConfirmarEliminar">Sí</button>
          <button id="btnCancelarEliminar">No</button>
        </div>
      </div>
    </div>

    <!-- Modal de éxito -->
    <div id="modalExito" class="modal-exito">
      <p id="mensajeExito">Producto eliminado correctamente.</p>
    </div>

    <!-- Chat flotante -->
    <div id="chat-float">
      <button id="chat-toggle">💬</button>
      <div id="chat-box" class="hidden">
        <div id="chat-header">Soporte en línea</div>
        <div id="chat-content">
          <p>Hola 👋 ¿En qué puedo ayudarte?</p>
        </div>
        <input type="text" id="chat-input" placeholder="Escribe tu mensaje...">
      </div>
    </div>

  <script src="/tienda-tecnologia/js/soperte-tecnico.js"></script>

  <script src="/tienda-tecnologia/js/script.js"></script>
  <script src="/tienda-tecnologia/js/carrito.js"></script>
  <script src="/tienda-tecnologia/js/agregar-productos.js"></script>
  <script src="/tienda-tecnologia/js/acciones-producto.js"></script>
  <script src="/tienda-tecnologia/js/modificar-producto.js"></script>

</body>
</html>
