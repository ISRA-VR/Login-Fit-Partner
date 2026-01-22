<?php
require_once __DIR__ . "/../../../app/controller/AuthController.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth = new AuthController();
    $auth->login();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fit Partner - Iniciar sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="app/Views/assets/img/logos/favi.png" type="image/x-icon">
    <link rel="stylesheet" href="app/Views/assets/css/login.css">
    <link rel="stylesheet" href="app/Views/assets/css/loading.css">
</head>
<body>
    <div class="login-container">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">
                <?= $_GET['error'] ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'registered'): ?>
            <div class="alert success">
                Cuenta creada correctamente. Inicia sesión.
            </div>
        <?php endif; ?>
        <img src="app/Views/assets/img/logos/logogym.png" alt="Fit Partner">

        <h2>Bienvenido a Fit Partner<br>
            <p>Inicia sesión para continuar</p>
        </h2>

        <form id="login-form" action="index.php?view=login" method="POST">

            <label>Correo electrónico</label>
            <div class="input-group">
                <input type="email" name="email" placeholder="nombre@ejemplo.com" required>
            </div>

            <label>Contraseña</label>
            <div class="input-group password-box">
                <input type="password" id="password" name="password" placeholder="********" required>
                <i class="bi bi-eye-slash toggle-pass" id="togglePassword"></i>
            </div>

            <button type="submit" class="btn-login" id="btn-login">Ingresar</button>
            <br><br>
            <div class="links">
                <p>¿Olvidaste tu contraseña? <a href="index.php?view=recover">Click aqui</a></p>
                <p>¿No tienes una cuenta? <a href="index.php?view=register">Registrate</a></p>
            </div>

        </form>

    </div>

    <div class="footer">© 2025 Fit Partner · Todos los derechos reservados</div>

    <div id="loading-overlay" class="loading-overlay" aria-live="polite" aria-busy="true">
        <div class="spinner"></div>
        <p class="dots">Iniciando sesión</p>
    </div>

    <script src="app/Views/assets/js/login.js"></script>

</body>

</html>