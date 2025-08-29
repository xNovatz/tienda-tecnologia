<?php
include 'conexion.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Obtener nombre de imagen antes de borrar
    $stmt = $conn->prepare("SELECT imagen, categoria FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
        $imagen = $producto['imagen'];
        $categoria = $producto['categoria'];

        // Eliminar archivo de imagen
        $rutaImagen = "../assets/productos/$categoria/$imagen";
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }

        // Borrar de base de datos
        $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(["exito" => true]);
            exit;
        }
    }
}

echo json_encode(["exito" => false]);
