<?php
// Este archivo es un controlador antiguo. Redirige a la ruta correcta manejada por el controlador frontal.
require_once __DIR__ . '/../config/config.php'; // Necesario para APP_URL

header('Location: ' . APP_URL . '/perfil');
exit();

// El código antiguo del controlador ya no es necesario aquí.
// require_once __DIR__ . '/../config/database.php';
// session_start();
// require 'UsuarioController.php';
// ... resto del código antiguo ...
?>