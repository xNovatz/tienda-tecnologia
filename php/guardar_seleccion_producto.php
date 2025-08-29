<?php
include 'conexion.php';

$categoria = $_POST['categoriaSeleccion'] ?? '';
$producto_id = $_POST['productoSeleccion'] ?? '';
$tipo = $_POST['tipoSeleccion'] ?? '';

if (!$categoria || !$producto_id || !$tipo) {
  echo "Faltan datos.";
  exit;
}

// Si es oferta especial, ahora solo se guarda el producto_id
if ($tipo === 'oferta') {
  $conn->query("DELETE FROM oferta_especial");
  $stmt = $conn->prepare("INSERT INTO oferta_especial (producto_id) VALUES (?)");
  $stmt->bind_param("i", $producto_id);

} else {
  // Para productos destacados y recomendados
  $stmt = $conn->prepare("DELETE FROM productos_destacados WHERE posicion = ?");
  $stmt->bind_param("s", $tipo);
  $stmt->execute();

  $stmt = $conn->prepare("INSERT INTO productos_destacados (producto_id, posicion) VALUES (?, ?)");
  $stmt->bind_param("is", $producto_id, $tipo);
}

if ($stmt->execute()) {
  echo "Producto asignado correctamente.";
} else {
  echo "Error al guardar.";
}
?>
