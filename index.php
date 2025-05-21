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

// Eliminar la barra inicial para la división
$path_segments = explode('/', ltrim($path, '/'));

// Inicializar variables de acción e ID
$productId = null;
$action = null; // Variable para la acción de detalle de producto
$adminAction = null;
$adminId = null;

// Extraer ID y acción para rutas dinámicas antes del switch

// Patrón para capturar /detalle-producto/{id} o /detalle-producto/{id}/{action}
if (isset($path_segments[0]) && $path_segments[0] === 'detalle-producto') {
    if (isset($path_segments[1]) && is_numeric($path_segments[1])) {
        $productId = $path_segments[1];
        if (isset($path_segments[2])) {
            $action = $path_segments[2];
        }
    }
     // Normalizar la ruta a /detalle-producto para que coincida con el caso del switch
    $path = '/detalle-producto';
} elseif (isset($path_segments[0]) && $path_segments[0] === 'admin') {
    // Extraer acción y ID para rutas de admin
    if (isset($path_segments[1])) {
        $adminAction = $path_segments[1];
    }
    if (isset($path_segments[2])) {
        $adminId = $path_segments[2];
    }
    // Normalizar la ruta para el switch a solo /admin
    $path = '/admin';
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
                // Esta es la ruta GET /admin/edit/{id}
                $controller->editProductForm($adminId);
            }
        } elseif ($adminAction === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
             // Manejar solicitud POST a /admin/delete (sin ID en URL)
            $controller->handleDeleteProduct(); // Obtiene el ID de $_POST
        } elseif ($adminAction === 'delete' && $adminId !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
            // Esta rama manejaría POST a /admin/delete/{id}. Mantenemos la llamada a handleDeleteProduct.
            $controller->handleDeleteProduct(); // Obtiene el ID de $_POST
        } else {
            // Acción por defecto: mostrar el panel (lista de productos)
            // Esto también maneja /admin (GET)
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
        // Asegurarse de que hay un ID de producto extraído
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