<?php
session_start();
require_once 'config/database.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        // Obtener la información de la imagen antes de eliminar
        $query = "SELECT imagenes FROM producto WHERE id_producto = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$_POST['id']]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Eliminar el producto
        $query = "DELETE FROM producto WHERE id_producto = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$_POST['id']]);
        
        // Eliminar la imagen si existe
        if ($producto && $producto['imagenes']) {
            $rutaImagen = 'imagenes/' . $producto['imagenes'];
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?> 