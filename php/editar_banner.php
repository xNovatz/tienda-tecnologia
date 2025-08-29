<?php
$conexion = new mysqli("localhost", "root", "", "tienda_db");

$id = $_POST['producto'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

$nombreArchivo = null;
if (!empty($_FILES['imagen']['name'])) {
  $archivo = $_FILES['imagen']['tmp_name'];
  $nombreArchivo = 'banner_' . time() . '_' . $_FILES['imagen']['name'];
  move_uploaded_file($archivo, "../assets/" . $nombreArchivo);
}

// Verifica si ya existe
$verificar = $conexion->query("SELECT * FROM banners WHERE producto_id = $id");
if ($verificar->num_rows > 0) {
  $sql = "UPDATE banners SET nombre='$nombre', descripcion='$descripcion'";
  if ($nombreArchivo) {
    $sql .= ", imagen='$nombreArchivo'";
  }
  $sql .= " WHERE producto_id=$id";
} else {
  $sql = "INSERT INTO banners (producto_id, nombre, descripcion, imagen)
          VALUES ($id, '$nombre', '$descripcion', '$nombreArchivo')";
}

$conexion->query($sql);
echo "Producto actualizado correctamente";
?>
