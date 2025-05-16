<?php
session_start();
require_once '../config/database.php';

$isLoggedIn = isset($_SESSION['usuario_id']);
$primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';

// Obtener el ID del producto de la URL
$id_producto = isset($_GET['id']) ? (int) $_GET['id'] : 0;

try {
    $database = new Database();
    $conn = $database->getConnection();

    // Obtener detalles del producto
    $query = "SELECT p.*, c.nombre_categoria 
             FROM producto p 
             LEFT JOIN categoria c ON p.categoria_id = c.id_categoria 
             WHERE p.id_producto = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id_producto]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        header('Location: productos.php');
        exit;
    }
    
    // Obtener reseñas del producto
    $query_resenas = "SELECT r.*, u.nombre as nombre_usuario 
                     FROM resenas r 
                     LEFT JOIN usuario u ON r.usuario_id = u.id_usuario 
                     WHERE r.producto_id = ? 
                     ORDER BY r.fecha_creacion DESC";
    $stmt_resenas = $conn->prepare($query_resenas);
    $stmt_resenas->execute([$id_producto]);
    $resenas = $stmt_resenas->fetchAll(PDO::FETCH_ASSOC);
    
    // Calcular promedio de valoraciones
    $query_promedio = "SELECT AVG(valoracion) as promedio FROM resenas WHERE producto_id = ?";
    $stmt_promedio = $conn->prepare($query_promedio);
    $stmt_promedio->execute([$id_producto]);
    $promedio = $stmt_promedio->fetch(PDO::FETCH_ASSOC);
    $valoracion_promedio = $promedio['promedio'] ? round($promedio['promedio'], 1) : 0;

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Función para agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isLoggedIn) {
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    if (isset($_POST['agregar_carrito'])) {
        $cantidad = isset($_POST['cantidad']) ? (int) $_POST['cantidad'] : 1;
        if (isset($_SESSION['carrito'][$id_producto])) {
            $_SESSION['carrito'][$id_producto] += $cantidad;
        } else {
            $_SESSION['carrito'][$id_producto] = $cantidad;
        }
        header('Location: carrito.php');
        exit;
    }
    
    // Procesar nueva reseña
    if (isset($_POST['enviar_resena']) && isset($_POST['valoracion']) && isset($_POST['comentario'])) {
        $valoracion = (int)$_POST['valoracion'];
        $comentario = trim($_POST['comentario']);
        
        if ($valoracion >= 1 && $valoracion <= 5 && !empty($comentario)) {
            try {
                $query_insert = "INSERT INTO resenas (producto_id, usuario_id, valoracion, comentario, fecha_creacion) 
                                VALUES (?, ?, ?, ?, NOW())";
                $stmt_insert = $conn->prepare($query_insert);
                $stmt_insert->execute([$id_producto, $_SESSION['usuario_id'], $valoracion, $comentario]);
                
                // Recargar la página para mostrar la nueva reseña
                header("Location: detalle_producto.php?id=$id_producto&resena_enviada=1");
                exit;
            } catch (PDOException $e) {
                $error_resena = "Error al guardar la reseña: " . $e->getMessage();
            }
        } else {
            $error_resena = "Por favor, proporciona una valoración válida y un comentario.";
        }
    }
}

// Incluir la vista
require '../views/detalle_producto.php';

?>