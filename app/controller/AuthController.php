<?php
require_once __DIR__ . "/../models/User.php";

class AuthController
{
    private function startSession()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    private function redirect($view, $error = null, $params = [])
    {
        $location = "index.php?view=" . rawurlencode($view);
        if ($error !== null) {
            $location .= "&error=" . rawurlencode($error);
        }
        foreach ($params as $key => $value) {
            $location .= "&" . rawurlencode($key) . "=" . rawurlencode($value);
        }

        header("Location: " . $location);
        exit;
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $name     = trim($_POST['name'] ?? '');
        $age      = trim($_POST['age'] ?? '');
        $height   = trim($_POST['height'] ?? '');
        $weight   = trim($_POST['weight'] ?? '');
        $email    = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $errors = [];

        if ($name === '' || mb_strlen($name) < 2 || mb_strlen($name) > 100) {
            $errors[] = "El nombre no es válido.";
        }
        if (!filter_var($age, FILTER_VALIDATE_INT, ['options' => ['min_range' => 13, 'max_range' => 120]])) {
            $errors[] = "La edad no es válida.";
        }
        if (!filter_var($height, FILTER_VALIDATE_INT, ['options' => ['min_range' => 50, 'max_range' => 250]])) {
            $errors[] = "La estatura no es válida.";
        }
        if (!filter_var($weight, FILTER_VALIDATE_INT, ['options' => ['min_range' => 20, 'max_range' => 400]])) {
            $errors[] = "El peso no es válido.";
        }

        $regexStrong = '/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/';
        if (!preg_match($regexStrong, $password)) {
            $errors[] = "La contraseña debe tener mínimo 8 caracteres, mayúscula, número y símbolo.";
        }

        if ($password !== $password2) {
            $errors[] = "Las contraseñas no coinciden.";
        }

        if (empty($_POST['terms'])) {
            $errors[] = "Debes aceptar los términos y condiciones.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El correo electrónico no es válido.";
        }

        if (!empty($errors)) {
            $this->redirect('register', implode("\n", $errors));
        }

        try {
            $user = new User();
            if ($user->emailExists($email)) {
                $this->redirect('register', 'El correo ya está registrado.');
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
                $this->redirect('login', null, ['msg' => 'registered']);
            }
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
        }

        $this->redirect('register', 'No fue posible crear la cuenta. Inténtalo nuevamente.');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        $this->startSession();
        $_SESSION['login_email'] = $email;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            $this->redirect('login', 'Correo o contraseña no válidos.');
        }

        try {
            $user = new User();
            $result = $user->login($email);
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $this->redirect('login', 'No fue posible iniciar sesión. Inténtalo nuevamente.');
        }

        if ($result && password_verify($password, $result['password'])) {

            // Regenerar ID de sesión para prevenir fijación de sesión
            if (function_exists('session_regenerate_id')) {
                session_regenerate_id(true);
            }
            unset($_SESSION['login_email']);
            $_SESSION['user'] = [
                "id"      => $result['id'],
                "nombre"  => $result['nombre'],
                "email"   => $result['email'],
                "role"    => $result['role'] ?? 'user'
            ];


            header("Location: index.php?view=dashboard");
            exit;
        } else {
            $this->redirect('login', 'Credenciales incorrectas.');
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
