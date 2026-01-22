<?php
require_once __DIR__ . "/../../../app/controller/AuthController.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth = new AuthController();
    $auth->register();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fit Partner - Registro</title>
    <link rel="shortcut icon" href="app/Views/assets/img/logos/favi.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="app/Views/assets/css/registro.css">
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
                Registro exitoso. Ahora puedes iniciar sesión.
            </div>
        <?php endif; ?>

        <img src="app/Views/assets/img/logos/logogym.png" alt="Fit Partner" class="logo">

        <h2>Crear cuenta
            <p>Regístrate para comenzar tu viaje fitness</p>
        </h2>

        <form action="index.php?view=register" method="POST">

            <label>Nombre completo</label>
            <input type="text" name="name" placeholder="Tu nombre" required>

            <div class="triple-fields">

                <div class="field">
                    <label>Edad</label>
                    <input type="number" name="age" maxlength="2" oninput="limitDigits(this,2)" placeholder="30" required>
                </div>

                <div class="field">
                    <label>Alt (cm)</label>
                    <input type="number" name="height" maxlength="3" oninput="limitDigits(this,3)" placeholder="175" required>
                </div>

                <div class="field">
                    <label>Peso (kg)</label>
                    <input type="number" name="weight" maxlength="3" oninput="limitDigits(this,3)" placeholder="70" required>
                </div>

            </div>


            <label>Correo electrónico</label>
            <input type="email" name="email" placeholder="nombre@ejemplo.com" required>

            <label>Contraseña</label>
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Crea una contraseña" required>
                <i class="bi bi-eye-slash toggle-pass" id="togglePassword"></i>
            </div>

            <p id="strength"></p>

            <label>Repetir contraseña</label>
            <div class="input-group">
                <input type="password" id="password2" name="password2" placeholder="Repite tu contraseña" required>
                <i class="bi bi-eye-slash toggle-pass" id="togglePassword2"></i>
            </div>

            <label class="terms">
                <input type="checkbox" name="terms" required> Acepto los términos y condiciones
            </label>

            <button type="submit" class="btn-register">Registrarme</button>

            <div class="links">
                ¿Ya tienes cuenta?<a href="index.php?view=login"> INICIA SESIÓN</a>
            </div>

        </form>
    </div>

    <div class="footer">© 2025 Fit Partner - Todos los derechos reservados</div>

    <script src="app/Views/assets/js/registro.js"></script>

</body>

</html>