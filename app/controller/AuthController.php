<?php
require_once __DIR__ . "/../models/User.php";

class AuthController
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $name     = trim($_POST['name'] ?? '');
        $age      = trim($_POST['age'] ?? '');
        $height   = trim($_POST['height'] ?? '');
        $weight   = trim($_POST['weight'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $errors = [];

        if (strlen($age) > 2) $errors[] = "La edad no es válida.";
        if (strlen($height) > 3) $errors[] = "La estatura no es válida.";
        if (strlen($weight) > 3) $errors[] = "El peso no es válido.";

        $regexStrong = '/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/';
        if (!preg_match($regexStrong, $password)) {
            $errors[] = "La contraseña debe tener mínimo 8 caracteres, mayúscula, número y símbolo.";
        }

        if ($password !== $password2) {
            $errors[] = "Las contraseñas no coinciden.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El correo electrónico no es válido.";
        }

        if (!empty($errors)) {
            $errorText = urlencode(implode("<br>", $errors));
            header("Location: index.php?view=register&error={$errorText}");
            exit;
        }

        $user = new User();
        if ($user->emailExists($email)) {
            header("Location: index.php?view=register&error=El correo ya está registrado.");
            exit;
        }

        $data = [
            'nombre'   => $name,
            'edad'     => $age,
            'estatura' => $height,
            'peso'     => $weight,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        if ($user->register($data)) {
            header("Location: index.php?view=login&msg=registered");
            exit;
        } else {
            header("Location: index.php?view=register&error=Error al crear la cuenta.");
            exit;
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: index.php?view=login&error=Correo no válido");
            exit;
        }

        $user = new User();
        $result = $user->login($email);

        if ($result && password_verify($password, $result['password'])) {

            session_start();
            // Regenerar ID de sesión para prevenir fijación de sesión
            if (function_exists('session_regenerate_id')) {
                session_regenerate_id(true);
            }
            $_SESSION['user'] = [
                "id"      => $result['id'],
                "nombre"  => $result['nombre'],
                "email"   => $result['email'],
                "role"    => $result['role'] ?? 'user'
            ];


            header("Location: index.php?view=dashboard");
            exit;
        } else {
            header("Location: index.php?view=login&error=Credenciales incorrectas");
            exit;
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        // Borrar cookie de sesión del navegador si existe
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        // Forzar que el navegador no guarde caché
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        header("Location: index.php?view=login");
        exit;
    }
}
