<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin'])) {
    die('Acceso denegado');
}

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM asistentes WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;
