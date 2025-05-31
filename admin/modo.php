<?php
session_start();
require_once '../includes/helpers.php';

if (!isset($_SESSION['admin'])) {
    die('Acceso denegado');
}

if (isset($_POST['estado'])) {
    $nuevoEstado = $_POST['estado'] === 'on';
    cambiarEstadoRegistroManual($nuevoEstado);
}

header('Location: index.php');
exit;
