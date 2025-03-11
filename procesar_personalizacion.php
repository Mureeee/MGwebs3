<?php
session_start();
require_once 'config/database.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión para realizar un pedido']);
    exit;
}

// Verificar si se recibieron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $categoria_id = filter_var($_POST['categoria_id'], FILTER_SANITIZE_NUMBER_INT);
    $nombre_proyecto = filter_var($_POST['nombre_proyecto'], FILTER_SANITIZE_STRING);
    $descripcion = filter_var($_POST['descripcion'], FILTER_SANITIZE_STRING);
    $usuario_id = $_SESSION['usuario_id'];
    
    // Validar la categoría
    if (!in_array($categoria_id, range(1, 10))) {
        echo json_encode(['success' => false, 'message' => 'Categoría no válida']);
        exit;
    }
    
    // Mapeo de categorías (para referencia)
    $categorias = [
        1 => 'Tienda Online',
        2 => 'Marketplace',
        3 => 'Blog y Noticias',
        4 => 'Recetas y Eventos',
        5 => 'Páginas Corporativas',
        6 => 'Consultoría y Coaching',
        7 => 'Deportes y Fotografía',
        8 => 'Música y Juegos',
        9 => 'Streaming y Reservas',
        10 => 'Foro Online'
    ];
    
    try {
        // Conectar a la base de datos
        $database = new Database();
        $conn = $database->getConnection();
        
        // Preparar la consulta SQL para insertar el pedido
        $query = "INSERT INTO pedidos (usuario_id, categoria_id, nombre_proyecto, descripcion, fecha_pedido, estado) 
                  VALUES (?, ?, ?, ?, NOW(), 'pendiente')";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$usuario_id, $categoria_id, $nombre_proyecto, $descripcion]);
        
        // Obtener el ID del pedido insertado
        $pedido_id = $conn->lastInsertId();
        
        if ($pedido_id) {
            // Éxito al guardar el pedido
            echo json_encode([
                'success' => true, 
                'message' => 'Su pedido ha sido registrado correctamente',
                'pedido_id' => $pedido_id,
                'categoria' => $categorias[$categoria_id]
            ]);
        } else {
            // Error al guardar el pedido
            echo json_encode(['success' => false, 'message' => 'Error al registrar el pedido']);
        }
        
    } catch (PDOException $e) {
        // Error de base de datos
        echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
    }
} else {
    // Método no permitido
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
