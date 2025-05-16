<?php

require_once __DIR__ . '/../config/app.php'; // Incluye el archivo de configuración
require_once __DIR__ . '/../config/database.php';
session_start();

// Verificar si el usuario está logueado
$isLoggedIn = isset($_SESSION['usuario_id']);
$primeraLetra = '';
$nombreUsuario = '';
$correoUsuario = '';
$rolUsuario = '';
$itemsCarrito = 0;

// Si está logueado, obtener información del usuario
if ($isLoggedIn) {
    $primeraLetra = strtoupper(substr($_SESSION['usuario_nombre'], 0, 1));
    $nombreUsuario = $_SESSION['usuario_nombre'];
    $correoUsuario = isset($_SESSION['usuario_correo']) ? $_SESSION['usuario_correo'] : '';
    $rolUsuario = isset($_SESSION['usuario_rol']) ? $_SESSION['usuario_rol'] : '';

    // Calcular items en el carrito
    if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
        $itemsCarrito = array_sum($_SESSION['carrito']);
    }
}

// Incluir la vista
include __DIR__ . '/../views/como_funciona.php';

?>