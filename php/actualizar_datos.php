<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['usuario_id'];

$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$departamento = $_POST['departamento'];
$municipio = $_POST['municipio'];
$direccion = $_POST['direccion'];
$punto_referencia = $_POST['punto_referencia'];

$stmt = $conn->prepare("UPDATE usuarios SET nombre=?, telefono=?, departamento=?, municipio=?, direccion=?, punto_referencia=? WHERE id=?");
$stmt->bind_param("ssssssi", $nombre, $telefono, $departamento, $municipio, $direccion, $punto_referencia, $id);
$stmt->execute();

header("Location: ../paginas/mi-cuenta.php?actualizado=1");
exit();
