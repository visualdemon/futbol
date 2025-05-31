<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/helpers.php';

$PASS = 'futbol2024'; // cámbiala luego

// Validar login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $PASS) {
        $_SESSION['admin'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = "Contraseña incorrecta";
    }
}

if (!isset($_SESSION['admin'])) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: sans-serif; background: #111; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { background: #222; padding: 2rem; border-radius: 8px; }
        input { padding: 0.5rem; width: 100%; margin-bottom: 1rem; }
        button { padding: 0.5rem; width: 100%; background: #66bb6a; border: none; color: #000; font-weight: bold; }
    </style>
</head>
<body>
    <form method="post">
        <h2>Acceso Administrador</h2>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <input type="password" name="password" placeholder="Contraseña">
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
<?php
exit;
}

// Mostrar lista
$fechaPartido = proximoJueves();
$stmt = $pdo->prepare("SELECT * FROM asistentes WHERE fecha_partido = ?");
$stmt->execute([$fechaPartido]);
$asistentes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrador - Fútbol</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: sans-serif; background: #0f1f0f; color: #fff; padding: 1rem; }
        h1 { text-align: center; color: #66bb6a; }
        ul { list-style: none; padding: 0; }
        li { padding: 0.5rem; border-bottom: 1px solid #333; display: flex; justify-content: space-between; align-items: center; }
        form { margin: 0; }
        button { background: #aa2e25; color: #fff; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Asistentes - <?php echo traducirFecha($fechaPartido); ?></h1>
    <ul>
        <?php foreach ($asistentes as $asistente): ?>
            <li>
                <?php echo htmlspecialchars($asistente['nombre']); ?>
                <form method="post" action="eliminar.php" onsubmit="return confirm('¿Eliminar a este jugador?');">
                    <input type="hidden" name="id" value="<?php echo $asistente['id']; ?>">
                    <button>Eliminar</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <h3>Modo prueba (registro libre):</h3>
    <form method="post" action="modo.php" style="margin-bottom: 20px;">
        <button type="submit" name="estado" value="<?php echo registroHabilitadoManual() ? 'off' : 'on'; ?>">
            <?php echo registroHabilitadoManual() ? '🔒 Desactivar registro libre' : '🔓 Activar registro libre'; ?>
        </button>
    </form>
</body>
</html>
