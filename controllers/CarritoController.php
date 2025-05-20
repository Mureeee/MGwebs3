<?php

require_once __DIR__ . '/../config/database.php';

class CarritoController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function index() {
        // La sesión ya se inició en el controlador frontal (index.php)

        // Variables necesarias para la vista y el partial de navbar
        $isLoggedIn = isset($_SESSION['usuario_id']);
        $primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';
        $nombreUsuario = $isLoggedIn ? $_SESSION['usuario_nombre'] : '';
        $rolUsuario = $isLoggedIn ? $_SESSION['usuario_rol'] : '';

        // Inicializar el carrito si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Obtener los productos del carrito
        $productosCarrito = $this->obtenerProductosCarrito();

        // Incluir la vista
        require __DIR__ . '/../views/carrito_view.php';
    }

    private function obtenerProductosCarrito() {
        if (empty($_SESSION['carrito'])) {
            return [];
        }

        $ids = array_keys($_SESSION['carrito']);
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';

        $query = "SELECT id_producto, nombre_producto, precio, imagenes FROM producto WHERE id_producto IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($ids);

        $productos = [];
        while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $producto['cantidad'] = $_SESSION['carrito'][$producto['id_producto']];
            $productos[] = $producto;
        }

        return $productos;
    }

    public function actualizarCantidad() {
        // Lógica para actualizar la cantidad del producto en el carrito via AJAX
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['id']) && isset($data['cambio'])) {
                $id = $data['id'];
                $cambio = $data['cambio'];

                // Verificar si el producto está en el carrito
                if (isset($_SESSION['carrito'][$id])) {
                    // Actualizar la cantidad
                    $_SESSION['carrito'][$id] += $cambio;

                    // Asegurarse de que la cantidad no sea menor que 1
                    if ($_SESSION['carrito'][$id] < 1) {
                        $_SESSION['carrito'][$id] = 1; // Mantener al menos 1
                    }

                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Producto no encontrado en el carrito.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
        }
    }

    public function eliminarProducto() {
        // Lógica para eliminar un producto del carrito via AJAX
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['id']) && isset($data['accion']) && $data['accion'] === 'eliminar') {
                $id = $data['id'];
                if (isset($_SESSION['carrito'][$id])) {
                    unset($_SESSION['carrito'][$id]);
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Producto no encontrado en el carrito.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
        }
    }
}

?> 