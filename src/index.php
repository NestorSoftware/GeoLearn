<?php
// Iniciar sesión
session_start();

// Cargar configuración de la base de datos
require_once 'config/database.php';

// Obtener la URL de la petición
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Controlador por defecto
$controllerName = isset($url[0]) && $url[0] !== '' ? ucfirst($url[0]) . 'Controller' : 'AuthController';
$method = isset($url[1]) && $url[1] !== '' ? $url[1] : 'index';

// Ruta del controlador
$controllerFile = 'controllers/' . $controllerName . '.php';

// Cargar el controlador correspondiente o mostrar error 404
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();
    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        http_response_code(404);
        echo "Método no encontrado.";
    }
} else {
    // Si no existe el controlador, redirigir al login
    require_once 'controllers/AuthController.php';
    $controller = new AuthController();
    $controller->index();
}