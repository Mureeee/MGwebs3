<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        // Verificar si el correo ya existe
        $query = "SELECT id_usuario FROM usuario WHERE correo = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$email]);
        
        $exists = $stmt->fetch() ? true : false;
        
        echo json_encode([
            'exists' => $exists,
            'valid' => filter_var($email, FILTER_VALIDATE_EMAIL) !== false
        ]);
        
    } catch (PDOException $e) {
        echo json_encode([
            'error' => true,
            'message' => 'Error al verificar el correo'
        ]);
    }
} else {
    echo json_encode([
        'error' => true,
        'message' => 'Método no permitido'
    ]);
}
?> 