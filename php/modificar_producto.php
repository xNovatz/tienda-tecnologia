<?php
header('Content-Type: application/json');
include 'conexion.php';

$response = ['exito' => false];

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = floatval($_POST['precio']);
$descuento = intval($_POST['descuento']);
$categoria = $_POST['categoria'];

$precioDescuento = $descuento > 0 ? $precio - ($precio * ($descuento / 100)) : null;
$imagen = $_FILES['imagen']['name'] ?? null;

if ($imagen) {
  $ruta = "../assets/productos/$categoria/";
  if (!is_dir($ruta)) {
    mkdir($ruta, 0777, true);
  }
  $rutaImagen = $ruta . basename($imagen);
  move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen);

  $query = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, descuento = ?, categoria = ?, imagen = ?, precio_descuento = ? WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ssdissdi", $nombre, $descripcion, $precio, $descuento, $categoria, $imagen, $precioDescuento, $id);
} else {
  $query = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, descuento = ?, categoria = ?, precio_descuento = ? WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ssdisdi", $nombre, $descripcion, $precio, $descuento, $categoria, $precioDescuento, $id);
}

if ($stmt->execute()) {
  $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $producto = $result->fetch_assoc();

  $response['exito'] = true;
  $response['producto'] = $producto;
} else {
  $response['mensaje'] = 'Error al actualizar en la base de datos.';
}

echo json_encode($response);
?>
