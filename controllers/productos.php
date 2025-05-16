<?php
require_once '../config/database.php';
session_start();
$isLoggedIn = isset($_SESSION['usuario_id']);
$primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';

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
            $query = "SELECT p.id_producto, p.nombre_producto, p.descripcion, p.precio, c.nombre_categoria 
                     FROM producto p 
                     LEFT JOIN categoria c ON p.categoria_id = c.id_categoria 
                     WHERE p.id_producto = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el producto: " . $e->getMessage());
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

$producto = new Producto();

// Obtener categorías para el filtro
$categorias = $producto->getCategorias();

// Obtener rango de precios
$precioRango = $producto->getPrecioMinMax();
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
$productos = $producto->getProductos($filtros);

// Incluir la vista
require '../views/productos.php';

?>
