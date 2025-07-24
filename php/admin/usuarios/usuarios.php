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
  <title>Administrar Usuarios - TeraComputer</title>
  <link rel="stylesheet" href="/tienda-tecnologia/css/estilos.css">
  <link rel="stylesheet" href="/tienda-tecnologia/css/cerrar-sesion.css">
  <link rel="stylesheet" href="/tienda-tecnologia/php/admin/usuarios/admin-usuarios.css">
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
<main class="cuenta-container">

  <h1>Gestión de Usuarios</h1>

  <!-- Buscador -->
  <div class="buscar-usuarios">
    <input type="text" id="filtroUsuarios" placeholder="Buscar por nombre o correo...">
  </div>

  <!-- Tabla -->
  <table class="tabla-usuarios" id="tablaUsuarios">
    <thead>
      <tr>
        <th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Teléfono</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

</main>

<!-- Modal para editar -->
<div class="modal-usuario" id="modalEditar">
  <div class="modal-content-usuario">
    <h2>Editar Usuario</h2>
    <form id="formEditar">
      <input type="hidden" name="id" id="edit-id">
      <input type="text" name="nombre" id="edit-nombre" placeholder="Nombre" required>
      <input type="email" name="correo" id="edit-correo" placeholder="Correo" required>
      <select name="rol" id="edit-rol">
        <option value="usuario">Usuario</option>
        <option value="admin">Admin</option>
      </select>
      <input type="text" name="telefono" id="edit-telefono" placeholder="Teléfono">

      <div class="botones-modal">
        <button type="submit" class="btn-guardar">Guardar</button>
        <button type="button" class="btn-cancelar" onclick="document.getElementById('modalEditar').classList.remove('show')">Cancelar</button>
      </div>
    </form>
  </div>
</div>

  <div id="modalCerrarSesion" class="modal-cerrar">
    <div class="modal-content-cerrar">
      <h2>¿Deseas cerrar sesión?</h2>
      <div class="modal-buttons">
        <button id="confirmarCerrarSesion" class="btn-confirmar">Sí</button>
        <button id="cancelarCerrarSesion" class="btn-cancelar">No</button>
      </div>
    </div>
  </div>

<script src="/tienda-tecnologia/php/admin/usuarios/admin-usuarios.js"></script>
<script src="/tienda-tecnologia/js/script.js"></script>
<script src="/tienda-tecnologia/js/soperte-tecnico.js"></script>
</body>
</html>
