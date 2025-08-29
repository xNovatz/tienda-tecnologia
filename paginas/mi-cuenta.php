<?php
session_start();
require '../php/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['redirigir_a_mi_cuenta'] = true;
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['usuario_id'];
$usuarioNombre = $_SESSION['usuario'] ?? 'Usuario';

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Cuenta</title>
  <link rel="stylesheet" href="../css/estilos.css">
  <link rel="stylesheet" href="../css/cerrar-sesion.css">
  <link rel="stylesheet" href="../css/categorias.css">
  <link rel="stylesheet" href="../css/carrito.css">
  <link rel="stylesheet" href="../css/mi-cuenta.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="icon" href="../icon.png" type="image/x-icon">
</head>
<body>

<!-- Top bar -->
<div class="top-bar">
  <span>¡Bienvenido, <?= htmlspecialchars($usuarioNombre) ?>!</span>
  <div class="idiomas">
    <a href="#" id="btnCerrarSesion" class="btn-cerrar-sesion">
      <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
    </a>
  </div>
</div>

<!-- Header principal -->
<header class="main-header">
  <a href="../index.php" class="logo">
    <h1>TERA<span>COM</span>PUTER</h1>
  </a>
</header>

<!-- Sub Header -->
<header class="sub-header">
  <div class="menu-categorias">
    <button class="btn-categorias" onclick="toggleCategorias()">
      <i class="fa-solid fa-bars"></i> Explorar Categorías
    </button>
    <ul class="lista-categorias" id="listaCategorias">
      <li><a href="./gabinetes.php">Gabinetes</a></li>
      <li><a href="./laptops.php">Laptops</a></li>
      <li><a href="./procesadores.php">Procesadores</a></li>
      <li><a href="./tarjetas-graficas.php">Tarjetas Gráficas</a></li>
      <li><a href="./mouses.php">Mouses</a></li>
      <li><a href="./monitores.php">Monitores</a></li>
    </ul>
  </div>

  <div class="iconos-header">
    <a href="../php/verificar-mi-cuenta.php">👤 Mi cuenta</a>
    <a href="/tienda-tecnologia/php/compras/historial.php">📝 Mis Compras</a>
    <a href="#" id="abrir-carrito">
      <i class="fa-solid fa-cart-shopping"></i> Carrito (<span id="contador-carrito">0</span>)
    </a>
  </div>
</header>

<!-- Vista principal -->
<div class="cuenta-container">
  <div class="cuenta-info">
    <h2>Información Personal</h2>
    <p><strong>Nombre:</strong> <span id="info-nombre"><?= htmlspecialchars($usuario['nombre']) ?></span></p>
    <p><strong>Teléfono:</strong> <span id="info-telefono"><?= htmlspecialchars($usuario['telefono']) ?></span></p>
    <p><strong>Departamento:</strong> <span id="info-departamento"><?= htmlspecialchars($usuario['departamento']) ?></span></p>
    <p><strong>Municipio:</strong> <span id="info-municipio"><?= htmlspecialchars($usuario['municipio']) ?></span></p>
    <p><strong>Dirección:</strong> <span id="info-direccion"><?= htmlspecialchars($usuario['direccion']) ?></span></p>
    <p><strong>Punto de Referencia:</strong> <span id="info-referencia"><?= htmlspecialchars($usuario['punto_referencia']) ?></span></p>
  </div>

  <div class="cuenta-actions">
    <button id="btnActualizar">Actualizar Datos</button>
    <button id="btnCambiar">Cambiar Contraseña</button>
  </div>
</div>

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

<!-- Modal Actualizar Datos -->
<div class="modal-actualizar" id="modalActualizar">
  <div class="modal-content-actualizar">
    <h3>Editar Información</h3>
    <form id="formActualizar">
      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

      <label for="telefono">Teléfono:</label>
      <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" required>

      <label for="departamento">Departamento:</label>
      <input type="text" id="departamento" name="departamento" value="<?= htmlspecialchars($usuario['departamento']) ?>" required>

      <label for="municipio">Municipio:</label>
      <input type="text" id="municipio" name="municipio" value="<?= htmlspecialchars($usuario['municipio']) ?>" required>

      <label for="direccion">Dirección:</label>
      <textarea id="direccion" name="direccion" required><?= htmlspecialchars($usuario['direccion']) ?></textarea>

      <label for="punto_referencia">Punto de Referencia:</label>
      <textarea id="punto_referencia" name="punto_referencia" required><?= htmlspecialchars($usuario['punto_referencia']) ?></textarea>

      <p id="mensajeExito" style="display:none; color: green; text-align: center; font-weight: bold;">
        ¡Datos actualizados correctamente!
      </p>

      <div class="botones-modal">
        <button type="submit" class="btn-guardar">Guardar</button>
        <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Cambiar Contraseña -->
<div class="modal-password" id="modalPassword">
  <div class="modal-content-password">
    <h3>Cambiar Contraseña</h3>
    <form id="formPassword">
      <label for="clave_actual">Contraseña Actual:</label>
      <input type="password" id="clave_actual" name="clave_actual" required>

      <label for="nueva_clave">Nueva Contraseña:</label>
      <input type="password" id="nueva_clave" name="nueva_clave" required>

      <label for="confirmar_clave">Confirmar Nueva Contraseña:</label>
      <input type="password" id="confirmar_clave" name="confirmar_clave" required>

      <p id="mensajeClave" style="display:none; color: green; text-align: center; font-weight: bold;"></p>

      <div class="botones-modal">
        <button type="submit" class="btn-guardar">Guardar</button>
        <button type="button" class="btn-cancelar" onclick="cerrarModalClave()">Cancelar</button>
      </div>
    </form>
  </div>
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

<script src="../js/script.js"></script>
<script src="../js/categorias.js"></script>
<script src="../js/carrito.js"></script>
<script src="../js/mi-cuenta.js"></script>
</body>
</html>
