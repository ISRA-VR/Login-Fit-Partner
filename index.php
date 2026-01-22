<?php
// Configurar cookies de sesión seguras antes de iniciar la sesión
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

session_start();

// Evitar caché globalmente para páginas dinámicas
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

$view = isset($_GET['view']) ? $_GET['view'] : 'login';

$views_bloqueadas = ['login', 'register'];

if (isset($_SESSION['user']) && in_array($view, $views_bloqueadas)) {
    header("Location: index.php?view=dashboard");
    exit;
}

switch ($view) {

    case 'login':
        require 'app/Views/Auth/login.php';
        break;

    case 'register':
        require 'app/Views/Auth/register.php';
        break;

    case 'recover':
        require 'app/Views/Auth/recover.php';
        break;

    case 'dashboard':
        require 'app/Views/Auth/dashboard.php';
        break;

    case 'logout':
        require 'app/Views/Auth/logout.php';
        break;

    default:
        echo "404 - Página no encontrada";
}