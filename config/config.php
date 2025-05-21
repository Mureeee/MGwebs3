<?php
// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); // Asegura que los errores de inicio también se muestren

// Configuración de zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de la aplicación
define('APP_NAME', 'MGwebs3');
define('APP_URL', 'http://localhost/MGwebs3');

// Definir la ruta absoluta a la raíz del proyecto
define('ROOT_PATH', __DIR__ . '/..');

// Configuración de rutas
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('MODELS_PATH', ROOT_PATH . '/models');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Configuración de sesión (debe estar antes de session_start())
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS
}

// Función de autoload para cargar clases automáticamente
spl_autoload_register(function ($class) {
    // Buscar en la carpeta de modelos
    $modelFile = MODELS_PATH . '/' . $class . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
        return;
    }
    
    // Buscar en la carpeta de controladores
    $controllerFile = CONTROLLERS_PATH . '/' . $class . '.php';
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        return;
    }
}); 