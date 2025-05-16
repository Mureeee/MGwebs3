<?php
require_once __DIR__ . '/../config/database.php';
session_start();

require 'UsuarioController.php';

$database = new Database();
$db = $database->getConnection();

$usuarioController = new UsuarioController($db);

// Verificar si el usuario ha iniciado sesión
if (!$usuarioController->isLoggedIn()) {
    header('Location: ../index.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$usuario = $usuarioController->getUsuario($usuario_id);

// Si no se encuentra el usuario (esto no debería pasar si isLoggedIn() es true, pero es una medida de seguridad)
if (!$usuario) {
    // Podríamos redirigir o mostrar un error. Por ahora, asignamos un array vacío
    // para evitar errores en la vista.
    $usuario = [
        'nombre' => '',
        'correo' => '',
        'direccion_envio' => ''
    ];
    // Opcional: Cerrar sesión o redirigir si el usuario no existe a pesar de la sesión
    // header('Location: ../cerrar_sesion.php');
    // exit();
}

// Variables para el navbar
$isLoggedIn = isset($_SESSION['usuario_id']);
$primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';

$mensaje = '';
$error = '';

// Procesar la actualización del perfil si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['user_id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($usuarioController->actualizarUsuario($id, $nombre, $email, $password)) {
        // Éxito al actualizar, recargar datos del usuario por si acaso
        $usuario = $usuarioController->getUsuario($id);
        $mensaje = "Perfil actualizado correctamente.";
    } else {
        $error = "Error al actualizar el perfil.";
    }
}

// Incluir la vista
include __DIR__ . '/../views/perfil.php';

?>