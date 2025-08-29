<?php
include "../conexion.php";

$token = $_GET['token'] ?? null;
$mensaje = "";
$exito = false;

if ($token) {
    $sql = "SELECT id FROM usuarios WHERE token_confirmacion = ? AND confirmado = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $usuario = $res->fetch_assoc();
        $id = $usuario['id'];

        $update = $conn->prepare("UPDATE usuarios SET confirmado = 1, token_confirmacion = NULL WHERE id = ?");
        $update->bind_param("i", $id);
        $update->execute();

        $mensaje = "✅ Tu cuenta ha sido confirmada correctamente.";
        $exito = true;
    } else {
        $mensaje = "❌ Token inválido o cuenta ya confirmada.";
    }
} else {
    $mensaje = "❌ Token no proporcionado.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Confirmación de Cuenta</title>
  <link rel="stylesheet" href="../../php/usuarios/mensajes.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
  <div class="mensaje-contenedor">
    <h2><?php echo $mensaje; ?></h2>
    <?php if ($exito): ?>
      <p>Ahora puedes iniciar sesión en tu cuenta y disfrutar de nuestros productos.</p>
      <a href="../../login.html" class="btn"><i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión</a>
    <?php else: ?>
      <p>Si el problema persiste, puedes volver a registrarte.</p>
      <a href="../../registro.html" class="btn"><i class="fa-solid fa-user-plus"></i> Registrarme</a>
    <?php endif; ?>
  </div>
</body>
</html>
