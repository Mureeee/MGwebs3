<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/ProductoModel.php'; // Incluir el modelo de Producto

class AdminController {
    private $db;
    private $productoModel;
    private $usuarioController;

    public function __construct() {
        // Asegurar que la sesión esté iniciada (ya debería estar en index.php)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Incluir archivos necesarios
        require_once __DIR__ . '/../config/database.php';
        require_once __DIR__ . '/../config/app.php'; // Para SITE_PATH si se usa
        require_once __DIR__ . '/../models/ProductoModel.php'; // Incluir el modelo de Producto

        // Instanciar la base de datos
        $database = new Database();
        $this->db = $database->getConnection();
        $this->productoModel = new ProductoModel(); // Instanciar el modelo de Producto

        // Instanciar el controlador de usuario (usar la conexión a la BD)
        // Asegurarse de que la clase UsuarioController exista antes de instanciar
        if (!class_exists('UsuarioController')) {
             require_once __DIR__ . '/UsuarioController.php'; // Incluir solo si no está definida
        }
        $this->usuarioController = new UsuarioController($this->db); 
    }

    public function index() {
        if (!$this->usuarioController->isLoggedIn() || !$this->usuarioController->isAdmin()) {
            header('Location: ' . APP_URL . '/login');
            exit();
        }

        // Obtener la lista de productos
        $productos = $this->productoModel->getAllProductos();
        
        // Obtener la lista de categorías para el formulario
        $categorias = $this->productoModel->getAllCategorias();

        $data = [
            'isLoggedIn' => $this->usuarioController->isLoggedIn(),
            'nombreUsuario' => isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : '',
            'rolUsuario' => isset($_SESSION['usuario_rol']) ? $_SESSION['usuario_rol'] : '',
            'primeraLetra' => strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)),
            'productos' => $productos->fetchAll(PDO::FETCH_ASSOC), // Pasar los productos a la vista
            'categorias' => $categorias // Pasar las categorías a la vista
        ];

        extract($data);

        require_once __DIR__ . '/../views/admin_panel.php';
    }

    // Método para mostrar el formulario de añadir producto (opcional si se hace en la misma página)
    // public function addProductForm() {
    //     $usuarioController = new UsuarioController($this->db);
    //     if (!$usuarioController->isLoggedIn() || !$usuarioController->isAdmin()) {
    //         header('Location: ' . APP_URL . '/login');
    //         exit();
    //     }
    //     // Obtener categorías para el dropdown
    //     $categorias = $this->productoModel->getAllCategorias();
    //     $data = ['categorias' => $categorias];
    //     extract($data);
    //     require __DIR__ . '/../views/add_product_form.php'; // Podría ser una vista separada
    // }

    // Método para manejar la adición de un nuevo producto
    public function handleAddProduct() {
        if (!$this->usuarioController->isLoggedIn() || !$this->usuarioController->isAdmin()) {
            header('Location: ' . APP_URL . '/login');
            exit();
        }

        // Verificar que la petición sea POST y que los campos necesarios existan
        if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['categoria_id']))
         {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $categoria_id = $_POST['categoria_id'];
            $imagen_path = null;

            // Manejar la subida de la imagen si se envió un archivo
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen_path = $this->productoModel->uploadImage($_FILES['imagen']);
                if (!$imagen_path) {
                    // Manejar error de subida de imagen
                    $_SESSION['error_message'] = 'Error al subir la imagen.';
                    header('Location: ' . APP_URL . '/admin'); // Redirigir de vuelta al panel
                    exit();
                }
            }

            // Añadir el producto a la base de datos
            if ($this->productoModel->addProducto($nombre, $descripcion, $precio, $categoria_id, $imagen_path)) {
                $_SESSION['success_message'] = 'Producto añadido con éxito.';
            } else {
                $_SESSION['error_message'] = 'Error al añadir el producto.';
            }
        }

        // Redirigir de vuelta al panel de administración
        header('Location: ' . APP_URL . '/admin');
        exit();
    }

    // Método para mostrar el formulario de editar producto
    public function editProductForm($id) {
        if (!$this->usuarioController->isLoggedIn() || !$this->usuarioController->isAdmin()) {
            header('Location: ' . APP_URL . '/login');
            exit();
        }

        // Obtener los datos del producto a editar
        $producto = $this->productoModel->getProductoById($id);

        if (!$producto) {
            $_SESSION['error_message'] = 'Producto no encontrado.';
            header('Location: ' . APP_URL . '/admin');
            exit();
        }
        
        // Obtener la lista de categorías para el formulario
        $categorias = $this->productoModel->getAllCategorias();

        // === Obtener la lista de todos los productos también ===
        $productos = $this->productoModel->getAllProductos();

        $data = [
            'isLoggedIn' => $this->usuarioController->isLoggedIn(),
            'nombreUsuario' => isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : '',
            'rolUsuario' => isset($_SESSION['usuario_rol']) ? $_SESSION['usuario_rol'] : '',
            'primeraLetra' => strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)),
            'producto' => $producto, // Pasar los datos del producto a editar
            'categorias' => $categorias,
            'productos' => $productos->fetchAll(PDO::FETCH_ASSOC) // Pasar la lista completa de productos
        ];

        extract($data);

        require_once __DIR__ . '/../views/admin_panel.php'; // Reutilizar la vista del panel para editar
    }

    // Método para manejar la actualización de un producto
    public function handleEditProduct($id) {
        if (!$this->usuarioController->isLoggedIn() || !$this->usuarioController->isAdmin()) {
            header('Location: ' . APP_URL . '/login');
            exit();
        }

        // Verificar que la petición sea POST y que los campos necesarios existan
        if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['categoria_id']))
        {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $categoria_id = $_POST['categoria_id'];
            $imagen_path = null;

             // Manejar la subida de una NUEVA imagen si se envió un archivo
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                 $imagen_path = $this->productoModel->uploadImage($_FILES['imagen']);
                 if (!$imagen_path) {
                    // Manejar error de subida de imagen
                    $_SESSION['error_message'] = 'Error al subir la nueva imagen.';
                    header('Location: ' . APP_URL . '/admin/edit/' . $id); // Redirigir de vuelta al formulario de edición
                    exit();
                }
            } else {
                 // Si no se subió una nueva imagen, obtener la ruta de la imagen actual
                 $producto_actual = $this->productoModel->getProductoById($id);
                 if ($producto_actual && !empty($producto_actual['imagenes'])) {
                      $imagenes = json_decode($producto_actual['imagenes'], true);
                      if (is_array($imagenes) && !empty($imagenes)) {
                           $imagen_path = $imagenes[0]; // Mantener la primera imagen existente
                      }
                 }
            }

            // Actualizar el producto en la base de datos
            if ($this->productoModel->updateProducto($id, $nombre, $descripcion, $precio, $categoria_id, $imagen_path)) {
                $_SESSION['success_message'] = 'Producto actualizado con éxito.';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar el producto.';
            }
        }

        // Redirigir de vuelta al panel de administración
        header('Location: ' . APP_URL . '/admin');
        exit();
    }

    // Método para manejar la eliminación de un producto
    public function handleDeleteProduct($id) {
        if (!$this->usuarioController->isLoggedIn() || !$this->usuarioController->isAdmin()) {
            header('Location: ' . APP_URL . '/login');
            exit();
        }

        // Eliminar el producto de la base de datos
        if ($this->productoModel->deleteProducto($id)) {
            $_SESSION['success_message'] = 'Producto eliminado con éxito.';
        } else {
            $_SESSION['error_message'] = 'Error al eliminar el producto.';
        }

        // Redirigir de vuelta al panel de administración
        header('Location: ' . APP_URL . '/admin');
        exit();
    }
} 