<?php

require_once ROOT_PATH . '/config/database.php';

class ProductosController {
    
    private $productoModel;

    public function __construct() {
        $this->productoModel = new Producto(); // Asume que la clase Producto está definida en otro archivo incluido por index.php o autocargada
    }

    public function index() {
        // Lógica para obtener datos del usuario (ya la manejamos en index.php, la pasaremos via $data)
        // $isLoggedIn, $primeraLetra, etc. vendrán del index.php a través de $data

        // Obtener categorías para el filtro
        $categorias = $this->productoModel->getCategorias();

        // Obtener rango de precios
        $precioRango = $this->productoModel->getPrecioMinMax();
        $precioMin = $precioRango['min_precio'] ?? 0;
        $precioMax = $precioRango['max_precio'] ?? 1000;

        // Procesar filtros
        $filtros = [];
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!empty($_GET['nombre'])) {
                $filtros['nombre'] = $_GET['nombre'];
            }
            if (!empty($_GET['categoria'])) {
                $filtros['categoria'] = $_GET['categoria'];
            }
            if (isset($_GET['precio_min']) && $_GET['precio_min'] !== '') {
                $filtros['precio_min'] = $_GET['precio_min'];
            }
            if (isset($_GET['precio_max']) && $_GET['precio_max'] !== '') {
                $filtros['precio_max'] = $_GET['precio_max'];
            }
        }

        // Obtener productos filtrados
        $productos = $this->productoModel->getProductos($filtros);

        // Preparar los datos para la vista
         $data = [
            'isLoggedIn' => isset($_SESSION['usuario_id']),
            'primeraLetra' => isset($_SESSION['usuario_nombre']) ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '',
             'nombreUsuario' => isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : '',
             'correoUsuario' => isset($_SESSION['usuario_correo']) ? $_SESSION['usuario_correo'] : '',
             'rolUsuario' => isset($_SESSION['usuario_rol']) ? $_SESSION['usuario_rol'] : '',
             'itemsCarrito' => isset($_SESSION['carrito']) && is_array($_SESSION['carrito']) ? array_sum($_SESSION['carrito']) : 0,
            'productos' => $productos,
            'categorias' => $categorias,
            'precioMin' => $precioMin,
            'precioMax' => $precioMax,
            'filtros' => $filtros // Pasar los filtros para mantener el estado en la vista
        ];

        // Cargar la vista, pasando los datos
        require ROOT_PATH . '/views/productos.php';
    }

    public function detalleProducto($id) {
        // Obtener los detalles del producto
        $producto = $this->productoModel->getProductoById($id);
        
        if (!$producto) {
            header("HTTP/1.0 404 Not Found");
            require ROOT_PATH . '/views/404.php';
            return;
        }

        // Obtener las reseñas del producto
        $resenas = $this->productoModel->getResenasByProductId($id);

        // Preparar los datos para la vista
        $data = [
            'isLoggedIn' => isset($_SESSION['usuario_id']),
            'primeraLetra' => isset($_SESSION['usuario_nombre']) ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '',
            'nombreUsuario' => isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : '',
            'correoUsuario' => isset($_SESSION['usuario_correo']) ? $_SESSION['usuario_correo'] : '',
            'rolUsuario' => isset($_SESSION['usuario_rol']) ? $_SESSION['usuario_rol'] : '',
            'itemsCarrito' => isset($_SESSION['carrito']) && is_array($_SESSION['carrito']) ? array_sum($_SESSION['carrito']) : 0,
            'producto' => $producto,
            'resenas' => $resenas
        ];

        // Cargar la vista de detalles
        require ROOT_PATH . '/views/detalle-producto.php';
    }

    public function agregarResena() {
        if (!isset($_SESSION['usuario_id'])) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['error' => 'Debes iniciar sesión para dejar una reseña']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            return;
        }

        $producto_id = $_POST['producto_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $comentario = $_POST['comentario'] ?? '';

        if (!$producto_id || !$rating) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Faltan datos requeridos']);
            return;
        }

        $result = $this->productoModel->agregarResena(
            $producto_id,
            $_SESSION['usuario_id'],
            $rating,
            $comentario
        );

        if ($result) {
            header('Location: ' . APP_URL . '/detalle-producto/' . $producto_id);
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error al guardar la reseña']);
        }
    }
}

// Mover la clase Producto a un archivo Modelo/Producto.php si se desea mayor separación.
class Producto {
    private $conn;

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConnection();
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getProductos($filtros = []) {
        try {
            $query = "SELECT p.id_producto, p.nombre_producto, p.descripcion, p.precio, c.nombre_categoria, c.id_categoria, p.imagenes 
                     FROM producto p 
                     LEFT JOIN categoria c ON p.categoria_id = c.id_categoria";
            
            $condiciones = [];
            $params = [];
            
            // Filtrar por nombre
            if (!empty($filtros['nombre'])) {
                $condiciones[] = "p.nombre_producto LIKE ?";
                $params[] = '%' . $filtros['nombre'] . '%';
            }
            
            // Filtrar por categoría
            if (!empty($filtros['categoria'])) {
                $condiciones[] = "c.id_categoria = ?";
                $params[] = $filtros['categoria'];
            }
            
            // Filtrar por precio mínimo
            if (isset($filtros['precio_min']) && $filtros['precio_min'] !== '') {
                $condiciones[] = "p.precio >= ?";
                $params[] = $filtros['precio_min'];
            }
            
            // Filtrar por precio máximo
            if (isset($filtros['precio_max']) && $filtros['precio_max'] !== '') {
                $condiciones[] = "p.precio <= ?";
                $params[] = $filtros['precio_max'];
            }
            
            // Añadir condiciones a la consulta
            if (!empty($condiciones)) {
                $query .= " WHERE " . implode(" AND ", $condiciones);
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }

    public function getProductoById($id) {
        try {
            $query = "SELECT p.*, c.nombre_categoria 
                     FROM producto p 
                     LEFT JOIN categoria c ON p.categoria_id = c.id_categoria 
                     WHERE p.id_producto = ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener producto: " . $e->getMessage());
            return null;
        }
    }

    public function getResenasByProductId($id) {
        try {
            $query = "SELECT r.*, u.nombre as nombre_usuario 
                     FROM resenas r 
                     LEFT JOIN usuarios u ON r.usuario_id = u.id 
                     WHERE r.producto_id = ? 
                     ORDER BY r.fecha_creacion DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener reseñas: " . $e->getMessage());
            return [];
        }
    }

    public function agregarResena($producto_id, $usuario_id, $rating, $comentario) {
        try {
            $query = "INSERT INTO resenas (producto_id, usuario_id, rating, comentario) 
                     VALUES (?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$producto_id, $usuario_id, $rating, $comentario]);
        } catch (PDOException $e) {
            error_log("Error al agregar reseña: " . $e->getMessage());
            return false;
        }
    }

    public function getCategorias() {
        try {
            $query = "SELECT id_categoria, nombre_categoria FROM categoria ORDER BY nombre_categoria";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las categorías: " . $e->getMessage());
        }
    }
    
    public function getPrecioMinMax() {
        try {
            $query = "SELECT MIN(precio) as min_precio, MAX(precio) as max_precio FROM producto";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los precios: " . $e->getMessage());
        }
    }
}

?> 