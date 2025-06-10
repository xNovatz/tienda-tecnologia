<?php
session_start();
$usuarioLogueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TeraComputer - Tienda de Tecnología</title>
  <link rel="stylesheet" href="css/estilos.css">
  <link rel="stylesheet" href="css/cerrar-sesion.css">
  <link rel="stylesheet" href="css/categorias.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
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
      <a href="login.html" class="cuenta-link"><i class="fa-regular fa-user"></i> Mi cuenta</a>
    <?php endif; ?>
  </div>
</div>


<!-- Header principal -->
<header class="main-header">
  <a href="./index.php" class="logo">
    <h1>TERA<span>COM</span>PUTER</h1>
  </a>

  <div class="iconos-header">
    <a href="#"><i class="fa-regular fa-heart"></i> Favoritos</a>
    <a href="#"><i class="fa-solid fa-cart-shopping"></i> Carrito</a>
  </div>
</header>


  <!-------------------------------------- Contenido principal ------------------------------------------>

  <!-- Menú de categorías y secciones destacadas -->
<section class="hero-section">
  <div class="menu-categorias">
  <button class="btn-categorias" onclick="toggleCategorias()">
    <i class="fa-solid fa-bars"></i> Explorar Categorías
  </button>
  <ul class="lista-categorias" id="listaCategorias">
    <li><a href="./paginas/gabinetes.php">Gabinetes</a></li>
    <li><a href="laptops.php">Laptops</a></li>
    <li><a href="procesadores.php">Procesadores</a></li>
    <li><a href="tarjetas-graficas.php">Tarjetas Gráficas</a></li>
    <li><a href="mouses.php">Mouses</a></li>
    <li><a href="monitores.php">Monitores</a></li>
  </ul>
</div>


  <div class="contenedor-promociones">
    <!-- Banner principal -->
    <div class="banner-principal">
      <div class="texto-banner">
        <h4>Nuevos Lanzamientos</h4>
        <h2><span>4K UHD</span><br>ROG Strix XG27UCS</h2>
        <p>¡Tiempo limitado! Solo en línea.</p>
        <button class="btn-banner">Comprar ahora</button>
      </div>
      <img src="assets/MonitosAsus.png" alt="Monitor 4K">
    </div>

    <!-- Productos destacados -->
    <div class="productos-destacados">
      <div class="producto">
        <div class="info">
          <h4>Laptop MSI</h4>
          <p>Vector 16</p>
          <small>¡Tiempo limitado! Solo en línea.</small>
        </div>
        <img src="assets/LaptopVector.png" alt="Nexus Mobile">
      </div>

      <div class="producto">
        <div class="info">
          <h4>NZXT H710</h4>
          <p>Cristal Templado USB 3.1</p>
          <small>¡Tiempo limitado! Solo en línea.</small>
        </div>
        <img src="assets/CaseNZXT.jpg" alt="iPad Mini">
      </div>
    </div>
  </div>
</section>

<!-- Galería de productos redondos -->
<section class="galeria-redonda">
  <a href="paginas/gabinetes.php" class="item-redondo">
    <img src="assets/Galery/GabineteGalery.png" alt="Gabinetes">
  </a>
  <a href="paginas/laptops.html" class="item-redondo">
    <img src="assets/Galery/LaptopGalery.png" alt="Laptops">
  </a>
  <a href="paginas/procesadores.html" class="item-redondo">
    <img src="assets/Galery/ProcesadoresGalery.png" alt="Procesadores">
  </a>
  <a href="paginas/graphic.html" class="item-redondo">
    <img src="assets/Galery/GraphicGalery.png" alt="Graphic">
  </a>
  <a href="paginas/mouse.html" class="item-redondo">
    <img src="assets/Galery/MouseGalery.jpg" alt="Mouse">
  </a>
  <a href="paginas/monitores.html" class="item-redondo">
    <img src="assets/Galery/MonitorGalery.png" alt="Monitores">
  </a>
  <div class="item-redondo">
    <img src="assets/productos/earpods.png" alt="Auriculares">
  </div>
  <div class="item-redondo">
    <img src="assets/productos/control.png" alt="Controlador">
  </div>
</section>

<!-- Productos -->
<section class="featured-section">
  <div class="special-offer">
    <h3>Oferta Especial</h3>
    <img src="assets/MonitorMSI.png" alt="Joystick">
    <p>Monitor MSI OPTIX Curved FHD 1080p 144Hz</p>
    <span class="price">$299.00</span>
  </div>

  <div class="featured-products">
    <div class="caja-titulo-recomendados">
      <h2>Productos destacados</h2>
    </div>
    <div class="products-grid">
      <!-- Producto 1 -->
      <div class="product-card">
        <img src="assets/MouseRazer.jpg" alt="Radiant View LCD">
        <h4>DEATHADDER V2</h4>
        <p>Mouse inalambrico razer deathadder v2 x hyperspeed bluetooth</p>
        <div class="stars">★★★★★</div>
        <div class="price">
          <del>$69.00</del> $59.00
        </div>
        <button>Agregar al carrito</button>
      </div>

      <!-- Producto 2 -->
      <div class="product-card">
        <img src="assets/Intel7.png" alt="Silent Touch Pro">
        <h4>Procesador Intel i7 14700K</h4>
        <p>20 Cores, 33MB, Turbo 5.6Ghz 14TH, LGA1700, UHD Graphics 770, No disipador</p>
        <div class="stars">★★★★★</div>
        <div class="price">
          <del>$579.00</del> $539.00
        </div>
        <button>Agregar al carrito</button>
      </div>

      <!-- Producto 3 -->
      <div class="product-card">
        <img src="assets/Gigabyte3050.png" alt="Supreme Tech Phone">
        <h4>Gigabyte NVIDIA GeForce RTX 3050</h4>
        <p>Graphic Card - 6 GB GDDR6 - N3050WF2OCV2-6GD</p>
        <div class="stars">★★★★☆</div>
        <div class="price">
          <del>$289.00</del> $245.00
        </div>
        <button>Agregar al carrito</button>
      </div>
    </div>
  </div>
</section>

<!-- Productos recomendados -->
<section class="recomended-section">
  <div class="recomended-products">
    <div class="caja-titulo-recomendados">
      <h2>Productos recomendados</h2>
    </div>
    <div class="products-grid">
      <!-- Producto RC 1 -->
      <div class="product-card">
        <img src="assets/5060ti.png" alt="5060Ti">
        <h4>MSI NVIDIA GeForce RTX 5060 Ti</h4>
        <p>La MSI GeForce RTX 5060 Ti 16G GAMING TRIO OC es una tarjeta gráfica de alto rendimiento diseñada para gamers</p>
        <div class="stars">★★★★★</div>
        <div class="price">
          <p>$799.00</p>
        </div>
        <button>Agregar al carrito</button>
      </div>

      <!-- Producto RC 2 -->
      <div class="product-card">
        <img src="assets/acernitro.jpg" alt="Acer Nitro">
        <h4>Laptop Acer Nitro</h4>
        <p>Con intel core i7 13620h, Ram ddr5 16GB - almacenamiento ssd 512gb - rtx4060 8gb - pantalla 144hz</p>
        <div class="stars">★★★★☆</div>
        <div class="price">
          <p>$1499.00</p>
        </div>
        <button>Agregar al carrito</button>
      </div>

      <!-- Producto RC 3 -->
      <div class="product-card">
        <img src="assets/Ryzen9.jpg" alt="Ryzen 9">
        <h4>Procesador AMD Ryzen 9 9900x</h4>
        <p>12c/24t 4.4-5.6ghz 76mb am5 requiere disipador termico</p>
        <div class="stars">★★★★☆</div>
        <div class="price">
          <p>$599.00</p>
        </div>
        <button>Agregar al carrito</button>
      </div>

      <!-- Producto RC 4 -->
      <div class="product-card">
        <img src="assets/MonitorAsus.jpg" alt="Supreme Tech Phone">
        <h4>Monitor asus</h4>
        <p>Panel ips 27p fullhd 120hz 1ms hdmi</p>
        <div class="stars">★★★☆☆</div>
        <div class="price">
          <p>$199.00</p>
        </div>
        <button>Agregar al carrito</button>
      </div>
    </div>
  </div>
</section>

<!-- porque elegirnos -->

<section class="features-section">
  <div class="container">
    <h2 class="section-title">¿Por qué elegirnos?</h2>
    <div class="features-grid">
      <div class="feature-card">
        <img src="ruta-a-icono1.png" alt="Icono 1" class="feature-icon" />
        <h3>Envíos Rápidos</h3>
        <p>Entregamos tu pedido en tiempo récord con cobertura nacional.</p>
      </div>
      <div class="feature-card">
        <img src="ruta-a-icono2.png" alt="Icono 2" class="feature-icon" />
        <h3>Soporte 24/7</h3>
        <p>Atención personalizada siempre que lo necesites, todos los días.</p>
      </div>
      <div class="feature-card">
        <img src="ruta-a-icono3.png" alt="Icono 3" class="feature-icon" />
        <h3>Pagos Seguros</h3>
        <p>Tus transacciones están protegidas con tecnología de punta.</p>
      </div>
    </div>
  </div>
</section>


<!-- pie de pagina -->
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
      <h4>Tienda</h4>
      <ul>
        <li><a href="#">Hombres</a></li>
        <li><a href="#">Mujeres</a></li>
        <li><a href="#">Niños</a></li>
        <li><a href="#">Ofertas</a></li>
      </ul>
    </div>
    <div class="footer-column">
      <h4>Síguenos</h4>
      <div class="social-links">
        <a href="#"><img src="icon-facebook.png" alt="Facebook"></a>
        <a href="#"><img src="icon-twitter.png" alt="Twitter"></a>
        <a href="#"><img src="icon-instagram.png" alt="Instagram"></a>
        <a href="#"><img src="icon-youtube.png" alt="YouTube"></a>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2025 TeraComputer. Todos los derechos reservados.</p>
  </div>
</footer>

  <!-- Modal de confirmación de cierre de sesión -->
    <div id="modalCerrarSesion" class="modal">
    <div class="modal-content">
      <h2>¿Deseas cerrar sesión?</h2>
      <div class="modal-buttons">
        <button id="confirmarCerrarSesion" class="btn-confirmar">Sí</button>
        <button id="cancelarCerrarSesion" class="btn-cancelar">No</button>
      </div>
    </div>
  </div>

  <script src="js/script.js"></script>
  <script src="js/categorias.js"></script>

</body>
</html>
