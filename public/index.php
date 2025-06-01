<?php
require_once '../includes/db.php';
require_once '../includes/helpers.php';

$fechaPartido = proximoJueves();
$fechaBonita = traducirFecha($fechaPartido);

// Obtener asistentes
$stmt = $pdo->prepare("SELECT * FROM asistentes WHERE fecha_partido = ?");
$stmt->execute([$fechaPartido]);
$asistentes = $stmt->fetchAll();
$total = count($asistentes);

// Validación de tiempo: solo martes a jueves antes de las 9pm
$ahora = new DateTime();
$diaSemana = (int)$ahora->format('N'); // 1 = Lunes, 7 = Domingo
$horaActual = (int)$ahora->format('H');

// Determinar si el registro está habilitado manualmente o por horario
if (registroHabilitadoManual()) {
    $registroPermitido = true;
} else {
    $registroPermitido = ($diaSemana >= 2 && $diaSemana <= 4 && !($diaSemana === 4 && $horaActual >= 21));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fútbol Jueves</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container">
        <h1>Fútbol<br><?php echo $fechaBonita; ?></h1>

        <div id="alertas">
            <?php
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
            ?>
        </div>

        <div id="mensajeError" style="margin-top: 10px;"></div>

        <!-- Spinner visual con texto -->
        <div id="loader" style="display: none; text-align: center; margin: 15px 0;">
            <div class="spinner" style="margin: 0 auto;"></div>
            <p style="color: #66bb6a; font-weight: bold;">⏳ Registrando...</p>
        </div>

        <?php if (!$registroPermitido): ?>
            <div class="alerta">⛔ El registro se habilita de martes a jueves antes de las 9:00 PM</div>
        <?php elseif ($total < 12): ?>
            <form id="registroForm" class="formulario">
                <input type="text" name="nombre" placeholder="Tu nombre completo" required maxlength="100">
                <input type="hidden" name="fecha" value="<?php echo $fechaPartido; ?>">
                <button type="submit" id="btnRegistrar">Confirmar asistencia</button>
            </form>
        <?php endif; ?>

        <button id="btnActualizarLista" style="margin: 10px auto; display:block;">
            🔄 Actualizar lista de Asistentes confirmados...
        </button>

        <div id="lista">
            <h2>Asistentes confirmados (<?php echo $total; ?>/12)</h2>
            <ul class="lista">
                <?php foreach ($asistentes as $i => $asistente): ?>
                    <li class="<?php echo $i < 10 ? 'titular' : 'suplente'; ?>">
                        <?php echo htmlspecialchars($asistente['nombre']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="scripts.js"></script>
</body>
</html>
