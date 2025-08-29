<?php
session_start();
$usuarioLogueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
$rolUsuario = $_SESSION['rol'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TeraComputer</title>
  <link rel="stylesheet" href="css/estilos.css">
  <link rel="stylesheet" href="css/cerrar-sesion.css">
  <link rel="stylesheet" href="css/categorias.css">
  <link rel="stylesheet" href="css/carrito.css">
  <link rel="stylesheet" href="css/modal-banner.css">
  <link rel="stylesheet" href="css/modal.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="icon" href="icon.png" type="image/x-icon">
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
      <a href="./login.html" class="cuenta-link"><i class="fa-regular fa-user"></i> Iniciar Sesión</a>
    <?php endif; ?>
  </div>
</div>


<!-- Header principal -->
<header class="main-header">
  <a href="./index.php" class="logo">
    <h1>TERA<span>COM</span>PUTER</h1>
  </a>
</header>

<header class="sub-header">
  <!-- Menú de categorías y secciones destacadas -->
  <div class="menu-categorias">
    <button class="btn-categorias" onclick="toggleCategorias()">
      <i class="fa-solid fa-bars"></i> Explorar Categorías
    </button>
    <ul class="lista-categorias" id="listaCategorias">
      <li><a href="./paginas/gabinetes.php">Gabinetes</a></li>
      <li><a href="./paginas/laptops.php">Laptops</a></li>
      <li><a href="./paginas/procesadores.php">Procesadores</a></li>
      <li><a href="./paginas/graphic.php">Tarjetas Gráficas</a></li>
      <li><a href="./paginas/mouse.php">Mouses</a></li>
      <li><a href="./paginas/monitores.php">Monitores</a></li>
      <li><a href="./paginas/rams.php">Memorias RAM</a></li>
    </ul>
  </div>

  <!-- 🔎 Buscador en el centro -->
      <div class="buscador-header">
        <form id="formBuscador" onsubmit="return false;">
          <input type="text" id="inputBuscador" placeholder="Buscar en toda la tienda..." required>
          <button type="submit"><i class="fa fa-search"></i></button>
        </form>
      </div>

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
</header>

<!-------------------------------------- Contenido principal ------------------------------------------>
<?php
$conexion = new mysqli("localhost", "root", "", "tienda_db");
$bannerQuery = $conexion->query("SELECT * FROM banners ORDER BY producto_id ASC");
$datosBanner = $bannerQuery->fetch_all(MYSQLI_ASSOC);
?>
<section class="hero-section">
  <div class="contenedor-promociones">

    <!-- Banner principal clickeable  -->
    <div class="banner-principal" onclick="location.href='paginas/monitores.php'" style="cursor: pointer;">
      <div class="texto-banner">
        <h4><p>Nuevos Lanzamientos</p></h4>
        <h2><?php echo $datosBanner[0]['nombre'] ?? 'ROG Strix XG27UCS'; ?></h2>
        <h1><?php echo $datosBanner[0]['descripcion'] ?? 'Nuevos Lanzamientos'; ?></h1>
      </div>
      <img src="assets/<?php echo $datosBanner[0]['imagen'] ?? 'MonitosAsus.png'; ?>" alt="Banner 1">
    </div>

    <!-- Productos destacados -->
    <div class="productos-destacados">
      <a href="paginas/graphic.php" class="producto-link">
      <div class="producto">
        <div class="info">
          <h4><?php echo $datosBanner[1]['nombre'] ?? 'Laptop MSI'; ?></h4>
          <p><?php echo $datosBanner[1]['descripcion'] ?? 'Vector 16'; ?></p>
          <h3><p>¡Ofertas Especiales!</p></h3>
        </div>
        <img src="assets/<?php echo $datosBanner[1]['imagen'] ?? 'LaptopVector.png'; ?>" alt="Banner 2">
      </div>

      <a href="paginas/laptops.php" class="producto-link">
        <div class="producto">
          <div class="info">
            <h4><?php echo $datosBanner[2]['nombre'] ?? 'NZXT H710'; ?></h4>
            <p><?php echo $datosBanner[2]['descripcion'] ?? 'Cristal Templado USB 3.1'; ?></p>
            <h3><p>¡Ofertas Especiales!</p></h3>
          </div>
          <img src="assets/<?php echo $datosBanner[2]['imagen'] ?? 'CaseNZXT.jpg'; ?>" alt="Banner 3">
        </div>
      </a>
    </div>
  </div>

  <!---- Botón para mostrar el modal ---->
  <div style="text-align: right; padding: 10px;">
    <?php if ($rolUsuario === 'admin'): ?>
      <button class="btn-banner" id="abrirModal">Mostrar Producto</button> 
    <?php endif; ?> 
  </div>
</section>

<!--------------------- Galería de productos redondos --------------------->
<section class="galeria-redonda">
  <a href="paginas/gabinetes.php" class="item-redondo">
    <img src="assets/Galery/GabineteGalery.png" alt="Gabinetes">
  </a>
  <a href="paginas/laptops.php" class="item-redondo">
    <img src="assets/Galery/LaptopGalery.png" alt="Laptops">
  </a>
  <a href="paginas/procesadores.php" class="item-redondo">
    <img src="assets/Galery/ProcesadoresGalery.png" alt="Procesadores">
  </a>
  <a href="paginas/graphic.php" class="item-redondo">
    <img src="assets/Galery/GraphicGalery.png" alt="Graphic">
  </a>
  <a href="paginas/mouse.php" class="item-redondo">
    <img src="assets/Galery/MouseGalery.jpg" alt="Mouse">
  </a>
  <a href="paginas/monitores.php" class="item-redondo">
    <img src="assets/Galery/MonitorGalery.png" alt="Monitores">
  </a>
  <div class="item-redondo">
  <a href="paginas/rams.php" class="item-redondo">
    <img src="assets/Galery/ram.png" alt="Rams">
  </a>  
  </div>
</section>

<!-------------------------------- Productos destacados --------------------------->
<section class="featured-section">
  <?php
    include './php/conexion.php';

    $oferta = $conn->query("
      SELECT p.* FROM oferta_especial oe
      INNER JOIN productos p ON oe.producto_id = p.id
      LIMIT 1
    ")->fetch_assoc();


    $destacados = $conn->query("
      SELECT p.* FROM productos_destacados pd
      INNER JOIN productos p ON pd.producto_id = p.id
      WHERE pd.posicion LIKE 'destacado%'
      ORDER BY pd.posicion ASC
    ");
  ?>

  <?php if ($oferta): ?>
    <div class="special-offer">
      <h3>Oferta Especial</h3>
      <img src="<?= 'assets/productos/' . htmlspecialchars($oferta['categoria']) . '/' . htmlspecialchars($oferta['imagen']) ?>" alt="<?= htmlspecialchars($oferta['nombre']) ?>">
      <h4><?= htmlspecialchars($oferta['nombre']) ?></h4>
      <?php
        $precio = $oferta['precio'];
        $descuento = $oferta['descuento'] ?? 0;
        $precioFinal = $precio * (1 - $descuento / 100);
      ?>

      <div class="price">
        <?php if ($descuento > 0): ?>
          <del>$<?= number_format($precio, 2) ?></del>
          $<?= number_format($precioFinal, 2) ?>
        <?php else: ?>
          $<?= number_format($precio, 2) ?>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="featured-products">
    <div class="caja-titulo-recomendados">
      <h2>Productos destacados
        <?php if ($rolUsuario === 'admin'): ?>
          <button id="abrirSeleccion" class="boton-mostrar-producto">Mostrar Producto</button>
        <?php endif; ?>
      </h2>
    </div>

    <div class="products-grid">
      <?php while($row = $destacados->fetch_assoc()): ?>
        <div class="product-card">
          <img src="<?= 'assets/productos/' . htmlspecialchars($row['categoria']) . '/' . htmlspecialchars($row['imagen']) ?>" alt="<?= htmlspecialchars($row['nombre']) ?>">
          <h4><?= htmlspecialchars($row['nombre']) ?></h4>
          <p class="descripcion-producto"><?= htmlspecialchars($row['descripcion']) ?></p>
          <div class="contenido-inferior">
            <div class="price">
              <?php if (!is_null($row['precio_descuento']) && $row['precio_descuento'] < $row['precio']): ?>
                <del>$<?= number_format($row['precio'], 2) ?></del>
                $<?= number_format($row['precio_descuento'], 2) ?>
              <?php else: ?>
                $<?= number_format($row['precio'], 2) ?>
              <?php endif; ?>
            </div>
            <button class="btn-agregar"
              data-id="<?= $row['id'] ?>"
              data-nombre="<?= htmlspecialchars($row['nombre']) ?>"
              data-precio="<?= $row['precio_descuento'] ?? $row['precio'] ?>"
              data-precio-original="<?= $row['precio'] ?>"
              data-imagen="<?= 'assets/' . htmlspecialchars($row['imagen']) ?>"
              data-tipo="destacados">
              Agregar al carrito
            </button>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!---------------------------- Productos recomendados ----------------------------->
<section class="recomended-section">
  <?php
    $recomendados = $conn->query("
      SELECT p.* FROM productos_destacados pd
      INNER JOIN productos p ON pd.producto_id = p.id
      WHERE pd.posicion LIKE 'recomendado%'
      ORDER BY pd.posicion ASC
    ");
  ?>
  <div class="recomended-products">
    <div class="caja-titulo-recomendados">
      <h2>Productos recomendados</h2>
    </div>
    <div class="products-grid">
      <?php while($row = $recomendados->fetch_assoc()): ?>
        <div class="product-card">
          <img src="<?= 'assets/productos/' . htmlspecialchars($row['categoria']) . '/' . htmlspecialchars($row['imagen']) ?>" alt="<?= htmlspecialchars($row['nombre']) ?>">
          <h4><?= htmlspecialchars($row['nombre']) ?></h4>
          <p class="descripcion-producto"><?= htmlspecialchars($row['descripcion']) ?></p>
          <div class="contenido-inferior">
            <div class="price">
              <?php if (!is_null($row['precio_descuento']) && $row['precio_descuento'] < $row['precio']): ?>
                <del>$<?= number_format($row['precio'], 2) ?></del>
                $<?= number_format($row['precio_descuento'], 2) ?>
              <?php else: ?>
                $<?= number_format($row['precio'], 2) ?>
              <?php endif; ?>
            </div>
            <button class="btn-agregar"
              data-id="<?= $row['id'] ?>"
              data-nombre="<?= htmlspecialchars($row['nombre']) ?>"
              data-precio="<?= $row['precio_descuento'] ?? $row['precio'] ?>"
              data-precio-original="<?= $row['precio'] ?>"
              data-imagen="<?= 'assets/' . htmlspecialchars($row['imagen']) ?>"
              data-tipo="destacados">
              Agregar al carrito
            </button>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!---------------------------- porque elegirnos ---------------------------->
<section class="features-section">
  <div class="container">
    <h2 class="section-title">¿Por qué elegirnos?</h2>
    <div class="features-grid">
      <div class="feature-card">
        <img src="assets/Galery/envios.webp" alt="Icono 1" class="feature-icon" />
        <h3>Envíos Rápidos</h3>
        <p>Entregamos tu pedido en tiempo récord con cobertura nacional.</p>
      </div>
      <div class="feature-card">
        <img src="assets/Galery/24 7.webp" alt="Icono 2" class="feature-icon" />
        <h3>Soporte 24/7</h3>
        <p>Atención personalizada siempre que lo necesites, todos los días.</p>
      </div>
      <div class="feature-card">
        <img src="assets/Galery/pagos.png" alt="Icono 3" class="feature-icon" />
        <h3>Pagos Seguros</h3>
        <p>Tus transacciones están protegidas con tecnología de punta.</p>
      </div>
    </div>
  </div>
</section>

<!-------------------------------- pie de pagina ------------------------------------->
<footer class="footer">
  <div class="footer-container">
    <div class="footer-column">
      <h4>Compañía</h4>
      <ul>
        <li><a href="#">Sobre Nosotros</a></li>
        <li><a href="#">Carreras</a></li>
        <li><a href="#">Blog</a></li>
        <li><a href="#">Afiliados</a></li>
      </ul>
    </div>
    <div class="footer-column">
      <h4>Ayuda</h4>
      <ul>
        <li><a href="#">Preguntas Frecuentes</a></li>
        <li><a href="#">Envíos</a></li>
        <li><a href="#">Devoluciones</a></li>
        <li><a href="#">Estado del Pedido</a></li>
      </ul>
    </div>
    <div class="footer-column">
      <h4>Síguenos</h4>
      <div class="social-links">
        <a href="#"><img src="assets/Redes/facebook.png" alt="Facebook"></a>
        <a href="#"><img src="assets/Redes/twitter.png" alt="Twitter"></a>
        <a href="#"><img src="assets/Redes/instagram.png" alt="Instagram"></a>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2025 TeraComputer. Todos los derechos reservados.</p>
  </div>
</footer>

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

  <!---------------------- Modal de confirmación de cierre de sesión --------------------->
  <div id="modalCerrarSesion" class="modal-cerrar">
    <div class="modal-content-cerrar">
      <h2>¿Deseas cerrar sesión?</h2>
      <div class="modal-buttons">
        <button id="confirmarCerrarSesion" class="btn-confirmar">Sí</button>
        <button id="cancelarCerrarSesion" class="btn-cancelar">No</button>
      </div>
    </div>
  </div>

  <!---------------------- Modal banner principal --------------------------->
  <div id="modalProducto" class="modal-fondo">
    <div class="modal-contenido">
      <span class="modal-cerrar">&times;</span>
      <h2>Editar producto del banner</h2>

      <form id="formEditarProducto" enctype="multipart/form-data">
        <label for="producto">Seleccionar Banner:</label>
        <select name="producto" id="producto" required>
          <option value="1">Banner 1</option>
          <option value="2">Banner 2</option>
          <option value="3">Banner 3</option>
        </select>

        <label for="nombre">Nombre del producto:</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" rows="3" required></textarea>

        <label for="imagen">Imagen (Sin fondo):</label>
        <input type="file" name="imagen" id="imagen" accept="image/*">

        <button type="submit">Guardar Cambios</button>
      </form>
    </div>
  </div>

 <!------------- Modal para seleccionar productos existentes ---------->
  <div id="modalSeleccionProducto" class="modal-fondo">
    <div class="modal-contenido">
      <span class="modal-cerrar" onclick="cerrarModalSeleccion()">&times;</span>
      <h2>Seleccionar producto para mostrar</h2>
      <form id="formSeleccionProducto">
        <label for="tipoSeleccion">¿Dónde mostrarlo?</label>
          <select id="tipoSeleccion" name="tipoSeleccion" required>
            <option value="oferta">Oferta Especial</option>
            <option value="destacado1">Producto Destacado 1</option>
            <option value="destacado2">Producto Destacado 2</option>
            <option value="destacado3">Producto Destacado 3</option>
            <option value="recomendado1">Producto Recomendado 1</option>
            <option value="recomendado2">Producto Recomendado 2</option>
            <option value="recomendado3">Producto Recomendado 3</option>
            <option value="recomendado4">Producto Recomendado 4</option>
          </select>

        <label for="categoriaSeleccion">Seleccionar categoría</label>
        <select id="categoriaSeleccion" name="categoriaSeleccion" required>
          <option value="">Seleccione una categoría</option>
          <option value="gabinetes">Gabinetes</option>
          <option value="laptops">Laptops</option>
          <option value="procesadores">Procesadores</option>
          <option value="graphic">Tarjetas Graficas</option>
          <option value="Mouse">Mouses</option>
          <option value="monitores">Monitores</option>
          <option value="rams">Memorias RAM</option>
          <!-- Agregar más categorías aquí -->
        </select>

        <label for="productoSeleccion">Seleccionar producto</label>
        <select id="productoSeleccion" name="productoSeleccion" required>
          <option value="">Seleccione una categoría primero</option>
        </select>

        <button type="submit">Guardar selección</button>
      </form>
    </div>
  </div>

  <!-- Mensaje flotante tipo toast -->
  <div id="mensaje-toast" class="toast-oculto">Producto asignado correctamente.</div>


<!----------------------------- / ---------------------------->

  <script src="/tienda-tecnologia/js/soperte-tecnico.js"></script>
  <script src="js/script.js"></script>
  <script src="js/categorias.js"></script>
  <script src="js/carrito.js"></script>
  <script src="js/modal-banner.js"></script>
  <script src="js/modificar_seleccion.js" defer></script>
  <script src="/tienda-tecnologia/php/buscador/buscador.js"></script>
  
</body>
</html>
