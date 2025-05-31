<?php
require_once '../includes/db.php';
require_once '../includes/helpers.php';

$fechaPartido = proximoJueves();
$stmt = $pdo->prepare("SELECT COUNT(*) FROM asistentes WHERE fecha_partido = ?");
$stmt->execute([$fechaPartido]);
$total = $stmt->fetchColumn();

if ($total === 10) {
    echo '<div class="alerta completo">
            ✅ ¡Ya están listos los 10 para jugar 5 vs 5!<br>
            Si se suman 2 más, ¡jugamos 6 vs 6! ⚽🔥
          </div>';
} elseif ($total === 11) {
    echo '<div class="alerta completo" style="background-color:#ffb300;">
            ⚠️ Ya somos 11. ¡Falta solo 1 para armar los 6 vs 6 completos! 🙌⚽
          </div>';
} elseif ($total >= 12) {
    echo '<div class="alerta completo">
            🎉 ¡Cupo completo! Ya están listos los 12 jugadores para esta semana. Nos vemos en la cancha ⚽🔥
          </div>';
}
