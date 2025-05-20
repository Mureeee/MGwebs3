<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';

class DetalleProductoController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function index($id_producto) {
        // Lógica para obtener detalles del producto y reseñas
        $producto = null;
        $resenas = [];
        $valoracion_promedio = 0;
        $error_resena = null;

        try {
            // Obtener detalles del producto
            $query = "SELECT p.*, c.nombre_categoria 
                     FROM producto p 
                     LEFT JOIN categoria c ON p.categoria_id = c.id_categoria 
                     WHERE p.id_producto = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id_producto]);
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$producto) {
                // Redirigir si el producto no existe
                header('Location: ' . APP_URL . '/productos');
                exit;
            }

            // Obtener reseñas del producto
            $query_resenas = "SELECT r.*, u.nombre as nombre_usuario 
                             FROM resenas r 
                             LEFT JOIN usuario u ON r.usuario_id = u.id_usuario 
                             WHERE r.producto_id = ? 
                             ORDER BY r.fecha_creacion DESC";
            $stmt_resenas = $this->conn->prepare($query_resenas);
            $stmt_resenas->execute([$id_producto]);
            $resenas = $stmt_resenas->fetchAll(PDO::FETCH_ASSOC);

            // Calcular promedio de valoraciones
            $query_promedio = "SELECT AVG(valoracion) as promedio FROM resenas WHERE producto_id = ?";
            $stmt_promedio = $this->conn->prepare($query_promedio);
            $stmt_promedio->execute([$id_producto]);
            $promedio = $stmt_promedio->fetch(PDO::FETCH_ASSOC);
            $valoracion_promedio = $promedio['promedio'] ? round($promedio['promedio'], 1) : 0;

        } catch (PDOException $e) {
            // Manejar error (podrías loggearlo o mostrar un mensaje genérico)
            error_log("Error en DetalleProductoController::index: " . $e->getMessage());
            // Podrías redirigir a una página de error o mostrar un mensaje en la vista
            die("Ocurrió un error al cargar el producto.");
        }

        // Preparar datos para la vista
        $isLoggedIn = isset($_SESSION['usuario_id']);
        $primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';

        $data = [
            'isLoggedIn' => $isLoggedIn,
            'primeraLetra' => $primeraLetra,
            'producto' => $producto,
            'resenas' => $resenas,
            'valoracion_promedio' => $valoracion_promedio,
            'error_resena' => $error_resena // Pasar el error si existe
        ];

        // Incluir la vista
        require __DIR__ . '/../views/detalle_producto.php';
    }

    public function agregarAlCarrito($id_producto) {
        // Asegurarse de que la sesión está iniciada (ya se hace en index.php)
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $cantidad = isset($_POST['cantidad']) ? (int) $_POST['cantidad'] : 1;

        if ($cantidad > 0) {
             if (isset($_SESSION['carrito'][$id_producto])) {
                $_SESSION['carrito'][$id_producto] += $cantidad;
            } else {
                $_SESSION['carrito'][$id_producto] = $cantidad;
            }
        }

        // Redirigir al carrito o de vuelta a la página del producto
        header('Location: ' . APP_URL . '/carrito');
        exit;
    }
    
    public function enviarResena($id_producto) {
         $error_resena = null;
        if (isset($_SESSION['usuario_id']) && isset($_POST['valoracion']) && isset($_POST['comentario'])) {
            $valoracion = (int)$_POST['valoracion'];
            $comentario = trim($_POST['comentario']);

            if ($valoracion >= 1 && $valoracion <= 5 && !empty($comentario)) {
                try {
                    // Verificar si el usuario ya ha reseñado este producto
                    $query_check = "SELECT COUNT(*) FROM resenas WHERE producto_id = ? AND usuario_id = ?";
                    $stmt_check = $this->conn->prepare($query_check);
                    $stmt_check->execute([$id_producto, $_SESSION['usuario_id']]);
                    $count = $stmt_check->fetchColumn();

                    if ($count == 0) {
                        $query_insert = "INSERT INTO resenas (producto_id, usuario_id, valoracion, comentario, fecha_creacion) 
                                        VALUES (?, ?, ?, ?, NOW())";
                        $stmt_insert = $this->conn->prepare($query_insert);
                        $stmt_insert->execute([$id_producto, $_SESSION['usuario_id'], $valoracion, $comentario]);

                        // Redirigir a la página del producto con un indicador de éxito
                        header('Location: ' . APP_URL . '/detalle-producto/' . $id_producto . '?resena_enviada=1');
                        exit;
                    } else {
                        $error_resena = "Ya has enviado una reseña para este producto.";
                    }
                } catch (PDOException $e) {
                    $error_resena = "Error al guardar la reseña: " . $e->getMessage();
                     error_log("Error al guardar reseña: " . $e->getMessage());
                }
            } else {
                $error_resena = "Por favor, proporciona una valoración válida y un comentario.";
            }
        } else {
             $error_resena = "Debes iniciar sesión y proporcionar todos los campos.";
        }
        
         // Si hay un error, volvemos a cargar la página con el mensaje de error
         // Esto implica duplicar parte de la lógica de index, o refactorizar
         // Por ahora, simplemente mostramos el error en la vista si no hay redirección
         // En un sistema real, quizás redirigirías con el error en la URL o sesión
         $this->index($id_producto); // Recargar la vista con el error
    }
}
?> 