<?php
// Cargar la configuración antes de iniciar la sesión
require_once 'config/config.php';
require_once 'config/database.php';

// Incluir todas las clases de controladores y modelos al inicio con require_once
require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/InfoController.php';
require_once 'controllers/CaracteristicasController.php';
require_once 'controllers/ProductosController.php';
require_once 'controllers/DetalleProductoController.php';
require_once 'controllers/CarritoController.php';
require_once 'controllers/CrearPaginaPersoController.php';
require_once 'controllers/SoporteController.php';
require_once 'controllers/ContactanosController.php';
require_once 'controllers/PerfilController.php';
require_once 'controllers/RegisterController.php';
// Asegúrate de incluir aquí cualquier otro modelo o clase necesaria que defina clases.

// Iniciar la sesión después de la configuración
session_start();

// Definir la ruta base
define('BASE_PATH', __DIR__);

// Obtener la ruta solicitada
$request = $_SERVER['REQUEST_URI'];
$basePath = dirname($_SERVER['SCRIPT_NAME']);
$path = substr($request, strlen($basePath));

// Limpiar la ruta de posibles barras finales
$path = rtrim($path, '/');

// Extraer ID y acción de la URL para rutas dinámicas antes del switch
$productId = null;
$action = null; // Variable para la acción (agregar-carrito, enviar-resena, etc.)

// Patrón para capturar /detalle-producto/{id} o /detalle-producto/{id}/{action}
if (preg_match('/\/detalle-producto\/(\d+)(?:\/([a-zA-Z0-9_-]+))?/', $path, $matches)) {
    $productId = $matches[1];
    if (isset($matches[2])) {
        $action = $matches[2];
    }
    // Re-normalizar la ruta a /detalle-producto para que coincida con el caso del switch
    $path = '/detalle-producto';
} else {
    // Extraer ID y acción para rutas de admin antes del switch
    $adminAction = null;
    $adminId = null;
    // Verificar si la ruta comienza con /admin
    if (isset($segments[1]) && $segments[1] === 'admin') {
        // Capturar la acción (ej: add, edit, delete)
        if (isset($segments[2])) {
            $adminAction = $segments[2];
        }
        // Capturar el ID si existe (ej: para edit/123)
        if (isset($segments[3])) {
            $adminId = $segments[3];
        }
        // Normalizar la ruta para el switch a solo /admin
        $path = '/admin';
    }
}

// Extraer el ID del producto de la URL si existe para /detalle-producto
$productId = null;
$action = null; // Variable para la acción (agregar-carrito, enviar-resena, etc.)

// Patrón para capturar /detalle-producto/{id} o /detalle-producto/{id}/{action}
if ($path === '/detalle-producto' && preg_match('/\/detalle-producto\/(\d+)(?:\/([a-zA-Z0-9_-]+))?/', $request, $matches)) {
    $productId = $matches[1];
    if (isset($matches[2])) {
        $action = $matches[2];
    }
    // $path ya está normalizado a '/detalle-producto'
}

// === Enrutamiento ===
switch ($path) {
    case '/':
    case '':
        $controller = new HomeController();
        $controller->index();
        break;
    
    case '/login':
        $controller = new AuthController();
        $controller->login();
        break;

    case '/admin':
        // La clase AdminController ya está incluida con require_once al inicio
        $controller = new AdminController();

        // Manejar acciones específicas del panel de administración
        if ($adminAction === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->handleAddProduct();
        } elseif ($adminAction === 'edit' && $adminId !== null) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->handleEditProduct($adminId);
            } else {
                $controller->editProductForm($adminId);
            }
        } elseif ($adminAction === 'delete' && $adminId !== null) {
             if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                 $controller->handleDeleteProduct($adminId);
             } else {
                  $_SESSION['error_message'] = 'La eliminación debe ser por método POST.';
                  header('Location: ' . APP_URL . '/admin');
                  exit();
             }
        } else {
            // Acción por defecto: mostrar el panel (lista de productos)
            $controller->index();
        }
        break;

    case '/como-funciona':
        $controller = new InfoController();
        $controller->comoFunciona();
        break;
    case '/caracteristicas':
        $controller = new CaracteristicasController();
        $controller->index();
        break;
    case '/productos':
        $controller = new ProductosController();
        $controller->index();
        break;
    case '/detalle-producto':
        // Asegurarse de que hay un ID de producto extraído por el preg_match anterior
        if ($productId === null) {
             header("HTTP/1.0 400 Bad Request");
             echo "Error: ID de producto no especificado.";
             exit;
        }
        $controller = new DetalleProductoController();
        if ($action === 'agregar-carrito') {
            $controller->agregarAlCarrito($productId);
        } elseif ($action === 'enviar-resena') {
             $controller->enviarResena($productId);
        } else {
            // Acción por defecto: mostrar detalle del producto
            $controller->index($productId);
        }
        break;
    case '/agregar-resena':
        // Esta ruta parece redundante si enviarResena se maneja en /detalle-producto/{id}/enviar-resena
        // Considera eliminarla si ya no se usa.
        $controller = new ProductosController();
        $controller->agregarResena(); // O redirigir si ya no es necesario
        break;
    case '/carrito':
        $controller = new CarritoController();
        $controller->index();
        break;
    case '/carrito/update':
        $controller = new CarritoController();
        $controller->actualizarCantidad();
        break;
    case '/carrito/delete':
        $controller = new CarritoController();
        $controller->eliminarProducto();
        break;
    case '/crearpaginaperso':
        $controller = new CrearPaginaPersoController();
        $controller->index();
        break;
    case '/soporte':
        $controller = new SoporteController();
        $controller->index();
        break;
    case '/contactanos':
        $controller = new ContactanosController();
        $controller->index();
        break;
    case '/contactanos/enviar':
        $controller = new ContactanosController();
        $controller->enviar();
        break;
    case '/logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    case '/perfil':
        $controller = new PerfilController();
        $controller->index();
        break;
    case '/registrarse':
        $controller = new RegisterController();
        // Mostrar el formulario solo para peticiones GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
             $controller->index();
        } else {
            // Para otros métodos en /registrarse, devolver método no permitido
             header("HTTP/1.0 405 Method Not Allowed");
             echo "Método no permitido para esta ruta.";
             exit();
        }
        break;
    case '/registrarse/process':
        $controller = new RegisterController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Esta es la ruta correcta para procesar el registro POST via AJAX
            $controller->process();
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
            echo "Método no permitido. Solo POST es aceptado para esta ruta.";
            exit();
        }
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        require 'views/404.php';
        break;
}

?>