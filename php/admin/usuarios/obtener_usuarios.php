<?php
include '../../conexion.php';

$result = $conn->query("SELECT id, nombre, correo, rol, telefono FROM usuarios ORDER BY id ASC");

$usuarios = [];

while ($row = $result->fetch_assoc()) {
  $usuarios[] = $row;
}

header('Content-Type: application/json');
echo json_encode($usuarios);
