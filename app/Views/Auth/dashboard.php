<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php?view=login");
    exit;
}

$name = $_SESSION['user']['nombre'] ?? "Usuario";
$role = $_SESSION['user']['role'] ?? 'user';

// Evitar que el navegador cachee esta página protegida
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fit Partner - Inicio</title>
    <link rel="shortcut icon" href="app/Views/assets/img/logos/favi.png" type="image/x-icon">
    <link rel="stylesheet" href="app/Views/assets/css/loading.css">
</head>
<body>

<h2>
    <?php if ($role === 'admin'): ?>
        Hola, Administrador <?= htmlspecialchars($name) ?> 👋
    <?php else: ?>
        Hola, <?= htmlspecialchars($name) ?> 👋
    <?php endif; ?>
</h2>

<p>
    <?php if ($role === 'admin'): ?>
        Tienes acceso administrativo. Puedes gestionar usuarios y configuraciones.
    <?php else: ?>
        Bienvenido a tu panel fitness.
    <?php endif; ?>
</p>

<a href="index.php?view=logout">
    <button>Cerrar sesión</button>
</a>

<div id="loading-overlay" class="loading-overlay" aria-live="polite" aria-busy="true">
    <div class="spinner"></div>
    <p class="dots">Cerrando sesión</p>
    </div>

<script src="app/Views/assets/js/logout.js"></script>
</body>
</html>
