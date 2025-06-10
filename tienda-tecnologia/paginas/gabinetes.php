<?php
session_start();
$usuarioLogueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gabinetes - TeraComputer</title>
  <link rel="stylesheet" href="../css/estilos.css" />
  <link rel="stylesheet" href="../css/cerrar-sesion.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="../css/paginas.css" />
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
      <a href="../login.html" class="cuenta-link"><i class="fa-regular fa-user"></i> Mi cuenta</a>
    <?php endif; ?>
  </div>
</div>

  <!-- Header principal -->
  <header class="main-header">
    <a href="../index.php" class="logo">
      <h1>TERA<span>COM</span>PUTER</h1>
    </a>

    <div class="iconos-header">
      <a href="#"><i class="fa-regular fa-heart"></i> Favoritos</a>
      <a href="#"><i class="fa-solid fa-cart-shopping"></i> Carrito</a>
    </div>
  </header>

  <!-- Contenido principal -->
  <main class="contenedor">
    <!-- Sidebar de categorías -->
    <aside class="sidebar">
      <h2>Product Categories</h2>
      <ul>
        <li>Elite Audio Gear</li>
        <li>Hyper Glide Mouse</li>
        <li>iPad Standard Plus</li>
        <li>Nexus Mobile Pro 256GB</li>
        <li>Pure Bass Headphones</li>
        <li>Radiant View LCD</li>
        <li>Silent Touch Pro</li>
      </ul>
    </aside>

    <!-- Contenedor de productos -->
    <div class="productos-listado">
      <section class="producto-detalle">
        <div class="producto-imagen">
          <img src="../assets/Galery/GabineteGalery.png" alt="Gabinete">
        </div>
        <div class="producto-info">
          <h2>Gabinete Xtreme RGB</h2>
          <p>Gabinete moderno con diseño elegante y ventilación avanzada. Compatible con placas ATX y microATX.</p>
          <div class="precio">
            <span class="anterior">Antes: $100</span>
            <span class="actual">$85</span>
          </div>
          <button class="btn-agregar">Agregar al carrito</button>
        </div>
      </section>

      <section class="producto-detalle">
        <div class="producto-imagen">
          <img src="../assets/CaseNZXT.jpg" alt="Gabinete Negro">
        </div>
        <div class="producto-info">
          <h2>Gabinete Black Shadow</h2>
          <p>Gabinete con diseño sobrio, panel lateral de vidrio templado y excelente flujo de aire.</p>
          <div class="precio">
            <span class="anterior">Antes: $95</span>
            <span class="actual">$79</span>
          </div>
          <button class="btn-agregar">Agregar al carrito</button>
        </div>
      </section>
    </div>
  </main>

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

  <script src="../js/script.js"></script>
</body>
</html>
