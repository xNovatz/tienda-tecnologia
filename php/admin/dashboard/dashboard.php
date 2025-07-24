<?php
session_start();
$usuarioLogueado = $_SESSION['usuario'] ?? null;
$rolUsuario = $_SESSION['rol'] ?? null;

if ($rolUsuario !== 'admin') {
  header('Location: ../../index.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Administrativo - TeraComputer</title>
  <link rel="stylesheet" href="/tienda-tecnologia/css/estilos.css">
  <link rel="stylesheet" href="/tienda-tecnologia/css/cerrar-sesion.css">
  <link rel="stylesheet" href="/tienda-tecnologia/php/admin/dashboard/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>

<!-- Top bar -->
<div class="top-bar">
  <span>
    <?php
      if ($usuarioLogueado) {
        echo "¡Bienvenido, " . htmlspecialchars($usuarioLogueado) . "! (admin)";
      } else {
        echo "Bienvenido a nuestra tienda tecnológica";
      }
    ?>
  </span>
  <div class="idiomas">
    <a href="#" id="btnCerrarSesion" class="btn-cerrar-sesion">
      <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
    </a>
  </div>
</div>

<!-- Header principal -->
<header class="main-header">
  <a href="/tienda-tecnologia/index.php" class="logo">
    <h1>TERA<span>COM</span>PUTER</h1>
  </a>

  <div class="iconos-header">
    <div class="iconos-header-carrito">
      <div class="dropdown-admin">
        <button class="dropbtn">⚙ Administrar ▾</button>
          <div class="dropdown-content">
            <a href="/tienda-tecnologia/php/admin/usuarios/usuarios.php">👥 Gestionar Usuarios</a>
            <a href="/tienda-tecnologia/php/admin/dashboard/dashboard.php">📊 Dashboard</a>
          </div>
      </div>
      <a href="/tienda-tecnologia/paginas/mi-cuenta.php">👤 Mi cuenta</a>
    </div>
  </div>
</header>

<!-- Contenido principal -->
<main class="dashboard-container">
  <h1>📊 Dashboard - Panel de Control</h1>

  <div class="estadisticas">
    <div class="card">
      <h3>🛒 Ventas Totales</h3>
      <p id="total-ventas">Cargando...</p>
    </div>
    <div class="card">
      <h3>👥 Total Usuarios</h3>
      <p id="total-usuarios">Cargando...</p>
    </div>
    <div class="card">
      <h3>💰 Ingresos Totales</h3>
      <p id="ingresos-totales">Cargando...</p>
    </div>
  </div>

  <h2>📌 Últimas Ventas</h2>
  <table id="tabla-notificaciones">
    <thead>
      <tr>
        <th>ID Venta</th>
        <th>Usuario</th>
        <th>Total</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</main>
      
  <div id="modalCerrarSesion" class="modal-cerrar">
    <div class="modal-content-cerrar">
      <h2>¿Deseas cerrar sesión?</h2>
      <div class="modal-buttons">
        <button id="confirmarCerrarSesion" class="btn-confirmar">Sí</button>
        <button id="cancelarCerrarSesion" class="btn-cancelar">No</button>
      </div>
    </div>
  </div>

<script src="/tienda-tecnologia/php/admin/dashboard/dashboard.js"></script>
<script src="/tienda-tecnologia/js/script.js"></script>
<script src="/tienda-tecnologia/js/soperte-tecnico.js"></script>
</body>
</html>
