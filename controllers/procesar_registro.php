<?php
ob_start(); // Iniciar el buffer de salida
session_start();
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../config/database.php'; // Necesitamos esto para la conexión inicial en el modelo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $correo = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $contraseña = $_POST['password'];
    $direccion_envio = filter_var($_POST['direccion'], FILTER_SANITIZE_STRING);
    
    $userModel = new UserModel();
    
    // Verificar si el correo ya existe usando el Modelo
    if ($userModel->getUserByCorreo($correo)) {
        ob_clean(); // Limpiar el buffer antes de enviar JSON
        echo json_encode(['success' => false, 'message' => 'El correo ya está registrado']);
        exit;
    }
    
    // Hash de la contraseña
    $contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);
    
    // Insertar nuevo usuario usando el Modelo
    $userId = $userModel->createUser($nombre, $correo, $contraseñaHash, $direccion_envio);
    
    if ($userId) {
        // Iniciar sesión automáticamente (esto podría considerarse parte del controlador o un servicio de autenticación)
        $_SESSION['usuario_id'] = $userId;
        $_SESSION['usuario_nombre'] = $nombre;
        $_SESSION['usuario_correo'] = $correo; // Usar 'correo' como en la base de datos y modelo
        $_SESSION['usuario_rol'] = 'cliente'; // Asignar rol por defecto
        
        ob_clean(); // Limpiar el buffer antes de enviar JSON
        echo json_encode(['success' => true, 'message' => 'Registro exitoso']);
    } else {
        // Log del error (el modelo debería manejar errores internos o lanzarlos)
        error_log("Error al crear usuario en la base de datos");
        ob_clean(); // Limpiar el buffer antes de enviar JSON
        echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario.']);
    }
    
} else {
    ob_clean(); // Limpiar el buffer antes de enviar JSON
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

ob_end_clean(); // Finalizar y limpiar el buffer si no se salió antes
?>