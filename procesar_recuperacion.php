<?php
// Activar la visualización de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config/database.php';

// Función para generar un token único
function generarToken($longitud = 32) {
    return bin2hex(random_bytes($longitud / 2));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');
    
    try {
        if (empty($_POST['email'])) {
            throw new Exception('Por favor, ingresa tu correo electrónico');
        }
        
        $correo = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Correo electrónico inválido');
        }
        
        $database = new Database();
        $conn = $database->getConnection();
        
        // Verificar si el correo existe
        $query = "SELECT id_usuario, nombre FROM usuario WHERE correo = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$usuario) {
            throw new Exception('No existe una cuenta con este correo electrónico');
        }
        
        // Generar y guardar el token
        $token = generarToken();
        $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $creado = date('Y-m-d H:i:s'); // Fecha y hora actual
        
        // Eliminar tokens anteriores de este usuario
        $query = "DELETE FROM recuperacion_password WHERE id_usuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$usuario['id_usuario']]);
        
        // Guardar el nuevo token
        $query = "INSERT INTO recuperacion_password (id_usuario, token, expiracion, creado) 
                 VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            $usuario['id_usuario'], 
            $token, 
            $expiracion,
            $creado
        ]);
        
        // Generar URL de recuperación
        $resetUrl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset_password.php?token=" . $token;
        
        echo json_encode([
            'success' => true,
            'message' => 'Haz clic en el siguiente enlace para restablecer tu contraseña (válido por 1 hora):',
            'resetUrl' => $resetUrl
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
?>

