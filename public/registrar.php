<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$fecha = $_POST['fecha'] ?? '';

if (empty($nombre) || empty($fecha)) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM asistentes WHERE fecha_partido = ?");
$stmt->execute([$fecha]);
$total = $stmt->fetchColumn();

if ($total >= 12) {
    echo json_encode(['success' => false, 'message' => '⛔ El cupo ya está lleno']);
    exit;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM asistentes WHERE nombre = ? AND fecha_partido = ?");
$stmt->execute([$nombre, $fecha]);
$yaRegistrado = $stmt->fetchColumn();

if ($yaRegistrado > 0) {
    echo json_encode(['success' => false, 'message' => '⚠️ Ya estás registrado para este partido']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO asistentes (nombre, fecha_partido) VALUES (?, ?)");
$stmt->execute([$nombre, $fecha]);

echo json_encode(['success' => true]);
exit;
