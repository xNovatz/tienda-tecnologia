<?php
header('Content-Type: application/json');
include __DIR__ . '/conexion.php';

$response = ['exito' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'];
  $descripcion = $_POST['descripcion'];
  $precio = floatval($_POST['precio']);
  $categoria = $_POST['categoria'];
  $descuento = isset($_POST['descuento']) ? intval($_POST['descuento']) : 0;

  $precioDescuento = $descuento > 0 ? $precio - ($precio * ($descuento / 100)) : null;

  $imagenNombre = $_FILES['imagen']['name'];
  $imagenTmp = $_FILES['imagen']['tmp_name'];
  $ruta = "../assets/productos/$categoria/";

  if (!is_dir($ruta)) {
    mkdir($ruta, 0777, true);
  }

  $destino = $ruta . $imagenNombre;

  if (move_uploaded_file($imagenTmp, $destino)) {
    $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen, categoria, descuento, precio_descuento) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssis", $nombre, $descripcion, $precio, $imagenNombre, $categoria, $descuento, $precioDescuento);

    if ($stmt->execute()) {
      $id = $conn->insert_id;

      $response['exito'] = true;
      $response['producto'] = [
        'id' => $id,
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'precio' => $precio,
        'precio_descuento' => $precioDescuento,
        'imagen' => $imagenNombre,
        'categoria' => $categoria,
        'descuento' => $descuento
      ];
    } else {
      $response['mensaje'] = 'Error al guardar en base de datos.';
    }
    $stmt->close();
  } else {
    $response['mensaje'] = 'Error al subir la imagen.';
  }
} else {
  $response['mensaje'] = 'Método no permitido.';
}

echo json_encode($response);
?>
