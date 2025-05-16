<?php
// Incluye la configuración de la aplicación para SITE_PATH
require_once __DIR__ . '/../config/app.php';

// Incluir el archivo de conexión a la base de datos y el controlador de usuario
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/UsuarioController.php';

// Asegura que la sesión esté iniciada
// session_start(); // Esto debería estar en el punto de entrada

// Instanciar la base de datos y obtener la conexión
$database = new Database();
$db = $database->getConnection();

// Instanciar el controlador de usuario
$usuarioController = new UsuarioController($db);

// === CÓDIGO DE DEPURACIÓN ===
echo '<h2>Debugging Session and Admin Check</h2>';
echo '<pre>';
echo '$_SESSION: ';
print_r($_SESSION);
echo '<br>';
echo 'isLoggedIn(): ';
var_dump($usuarioController->isLoggedIn());
echo '<br>';
echo 'isAdmin(): ';
var_dump($usuarioController->isAdmin());
echo '</pre>';
echo '<hr>';
// ============================

// Verificar si el usuario ha iniciado sesión y es administrador
if (!$usuarioController->isLoggedIn() || !$usuarioController->isAdmin()) {
    // Redirigir a la página de inicio de sesión si no está autenticado o no es admin
    header('Location: ' . SITE_PATH . 'login.php'); // Asegúrate de tener login.php o ajusta la ruta
    exit();
}

// Lógica para obtener datos si es necesario (ej: lista de usuarios, productos, etc.)
// ... lógica existente para obtener datos para el panel ...

// Obtener la primera letra del nombre de usuario (necesaria para el navbar en la vista)
$primeraLetra = strtoupper(substr($_SESSION['usuario_nombre'], 0, 1));

// Incluir la vista
include __DIR__ . '/../views/admin_panel.php';
?> 