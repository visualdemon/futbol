<?php
require_once '../includes/db.php';
require_once '../includes/helpers.php';

$fechaPartido = proximoJueves();
$stmt = $pdo->prepare("SELECT * FROM asistentes WHERE fecha_partido = ?");
$stmt->execute([$fechaPartido]);
$asistentes = $stmt->fetchAll();
$total = count($asistentes);

echo "<h2>Asistentes confirmados ($total/12)</h2>";
echo "<ul class='lista'>";
foreach ($asistentes as $i => $asistente) {
    $clase = $i < 10 ? 'titular' : 'suplente';
    echo "<li class='$clase'>" . htmlspecialchars($asistente['nombre']) . "</li>";
}
echo "</ul>";
