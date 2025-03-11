<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        // Buscar usuario por correo
        $query = "SELECT id_usuario, nombre, contraseña, rol FROM usuario WHERE correo = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($password, $usuario['contraseña'])) {
            // Inicio de sesión exitoso
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_correo'] = $correo;
            $_SESSION['usuario_rol'] = $usuario['rol'];
            
            echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al conectar con la base de datos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
