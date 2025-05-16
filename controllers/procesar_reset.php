<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar que se hayan enviado todos los campos necesarios
    if (!isset($_POST['token']) || !isset($_POST['password']) || !isset($_POST['confirm_password'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
        exit;
    }
    
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Verificar que las contraseñas coincidan
    if ($password !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden']);
        exit;
    }
    
    // Validar requisitos de contraseña
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula y un número']);
        exit;
    }
    
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        // Verificar si el token existe y no ha expirado
        $query = "SELECT id_usuario FROM recuperacion_password WHERE token = ? AND expiracion > NOW()";
        $stmt = $conn->prepare($query);
        $stmt->execute([$token]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$resultado) {
            echo json_encode(['success' => false, 'message' => 'El enlace ha expirado o no es válido']);
            exit;
        }
        
        $idUsuario = $resultado['id_usuario'];
        
        // Actualizar la contraseña del usuario
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE usuario SET contraseña = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$hashedPassword, $idUsuario]);
        
        // Eliminar el token de recuperación
        $query = "DELETE FROM recuperacion_password WHERE id_usuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$idUsuario]);
        
        echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente. Serás redirigido a la página de inicio de sesión.']);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>

