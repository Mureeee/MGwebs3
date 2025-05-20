<?php
// Cargar la configuración antes de iniciar la sesión
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'controllers/ProductosController.php';

// Iniciar la sesión después de la configuración
session_start();

// Definir la ruta base
define('BASE_PATH', __DIR__);

// Obtener la ruta solicitada
$request = $_SERVER['REQUEST_URI'];
$basePath = dirname($_SERVER['SCRIPT_NAME']);
$path = substr($request, strlen($basePath));

// Extraer el ID del producto de la URL si existe
$productId = null;
if (preg_match('/\/detalle-producto\/(\d+)/', $path, $matches)) {
    $productId = $matches[1];
    $path = '/detalle-producto';
}

// Enrutamiento básico
switch ($path) {
    case '/':
    case '':
        require 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
    case '/login':
        require 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;
    case '/admin':
        require 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->index();
        break;
    case '/como-funciona':
        require 'controllers/InfoController.php';
        $controller = new InfoController();
        $controller->comoFunciona();
        break;
    case '/caracteristicas':
        require 'controllers/CaracteristicasController.php';
        $controller = new CaracteristicasController();
        $controller->index();
        break;
    case '/productos':
        $controller = new ProductosController();
        $controller->index();
        break;
    case '/detalle-producto':
        $controller = new ProductosController();
        $controller->detalleProducto($productId);
        break;
    case '/agregar-resena':
        $controller = new ProductosController();
        $controller->agregarResena();
        break;
    case '/soporte':
        require 'controllers/soporte.php';
        break;
    case '/contactanos':
        require 'controllers/contactanos.php';
        break;
    case '/logout':
        require 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;
    case '/perfil':
        require 'controllers/PerfilController.php';
        $controller = new PerfilController();
        $controller->index();
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        require 'views/404.php';
        break;
}

?>