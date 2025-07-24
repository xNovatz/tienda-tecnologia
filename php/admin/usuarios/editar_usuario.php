<?php
include '../../conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
  $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, correo = ?, rol = ?, telefono = ? WHERE id = ?");
  $stmt->bind_param("ssssi", $data['nombre'], $data['correo'], $data['rol'], $data['telefono'], $data['id']);
  $stmt->execute();

  echo json_encode(["ok" => true]);
} else {
  echo json_encode(["ok" => false]);
}
