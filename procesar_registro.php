<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $correo = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $contraseña = $_POST['password'];
    $direccion_envio = filter_var($_POST['direccion'], FILTER_SANITIZE_STRING);
    $rol = 'cliente'; // Definimos el rol como cliente
    
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        // Verificar si el correo ya existe
        $checkQuery = "SELECT id_usuario FROM usuario WHERE correo = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->execute([$correo]);
        
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'El correo ya está registrado']);
            exit;
        }
        
        // Hash de la contraseña
        $contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);
        
        // Insertar nuevo usuario
        $query = "INSERT INTO usuario (nombre, correo, contraseña, direccion_envio, rol) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$nombre, $correo, $contraseñaHash, $direccion_envio, $rol]);
        
        // Obtener el ID del usuario recién creado
        $userId = $conn->lastInsertId();
        
        // Iniciar sesión automáticamente
        $_SESSION['usuario_id'] = $userId;
        $_SESSION['usuario_nombre'] = $nombre;
        $_SESSION['usuario_correo'] = $correo;
        $_SESSION['usuario_rol'] = $rol;
        
        echo json_encode(['success' => true, 'message' => 'Registro exitoso']);
    } catch (PDOException $e) {
        // Log del error para depuración (en un entorno de producción, no mostrar detalles del error)
        error_log("Error en registro: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

// Eliminamos el código de inserción del administrador que estaba causando problemas
?>