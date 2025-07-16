<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['redirigir_a_mi_cuenta'] = true;
    header("Location: ../login.html");
    exit();
} else {
    header("Location: ../paginas/mi-cuenta.php");
    exit();
}
