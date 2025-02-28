<?php
require_once __DIR__ . '/../config/database.php';

// Conectar a la base de datos
function conectarDB() {
    try {
        $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

// Mostrar productos con filtros
function mostrarProductos() {
    $db = conectarDB();
    $where = [];
    $params = [];

    // Filtros
    if (!empty($_GET['nombre'])) {
        $where[] = "nombre_producto LIKE :nombre";
        $params['nombre'] = '%' . $_GET['nombre'] . '%';
    }
    if (!empty($_GET['precio_min'])) {
        $where[] = "precio >= :precio_min";
        $params['precio_min'] = $_GET['precio_min'];
    }
    if (!empty($_GET['precio_max'])) {
        $where[] = "precio <= :precio_max";
        $params['precio_max'] = $_GET['precio_max'];
    }
    if (!empty($_GET['categoria'])) {
        $where[] = "categoria_id = :categoria";
        $params['categoria'] = $_GET['categoria'];
    }

    // Construir la consulta
    $sql = "SELECT id_producto, nombre_producto, descripcion, precio FROM producto";
    if ($where) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    try {
        $query = $db->prepare($sql);
        $query->execute($params);
        $productos = $query->fetchAll(PDO::FETCH_ASSOC);
        include 'views/productos/listado.php'; // Incluir la vista de productos
    } catch (Exception $e) {
        echo "Error al obtener los productos: " . $e->getMessage();
    }
}

// Mostrar el panel de administración
function mostrarPanelAdmin() {
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrador') {
        header('Location: /index.php?error=Acceso denegado');
        exit();
    }

    $db = conectarDB();
    $query = $db->prepare("SELECT id_producto, nombre_producto, descripcion, precio FROM producto");
    $query->execute();
    $productos = $query->fetchAll(PDO::FETCH_ASSOC);
    include 'views/admin/dashboard.php';
}

// Mostrar el formulario de producto
function mostrarFormularioProducto($id = null) {
    if ($id) {
        $db = conectarDB();
        $query = $db->prepare("SELECT * FROM producto WHERE id_producto = :id");
        $query->execute(['id' => $id]);
        $producto = $query->fetch(PDO::FETCH_ASSOC);
    }
    include 'views/admin/product-form.php';
}

// Agregar un producto
function agregarProducto($datos) {
    try {
        $db = conectarDB();
        $query = $db->prepare("INSERT INTO producto (nombre_producto, descripcion, precio) VALUES (:nombre, :descripcion, :precio)");
        $result = $query->execute([
            'nombre' => $datos['nombre_producto'],
            'descripcion' => $datos['descripcion'],
            'precio' => $datos['precio']
        ]);
        $redirectUrl = $result ? '/index.php?page=admin&success=Producto agregado correctamente' : '/index.php?page=admin&error=Error al agregar el producto';
        header('Location: ' . $redirectUrl);
    } catch (Exception $e) {
        header('Location: /index.php?page=admin&error=' . urlencode($e->getMessage()));
    }
}

// Actualizar un producto
function actualizarProducto($datos) {
    try {
        $db = conectarDB();
        $query = $db->prepare("UPDATE producto SET nombre_producto = :nombre, descripcion = :descripcion, precio = :precio WHERE id_producto = :id");
        $result = $query->execute([
            'id' => $datos['id_producto'],
            'nombre' => $datos['nombre_producto'],
            'descripcion' => $datos['descripcion'],
            'precio' => $datos['precio']
        ]);
        $redirectUrl = $result ? '/index.php?page=admin&success=Producto actualizado correctamente' : '/index.php?page=admin&error=Error al actualizar el producto';
        header('Location: ' . $redirectUrl);
    } catch (Exception $e) {
        header('Location: /index.php?page=admin&error=' . urlencode($e->getMessage()));
    }
}

// Eliminar un producto
function eliminarProducto($id) {
    try {
        $db = conectarDB();
        $query = $db->prepare("DELETE FROM producto WHERE id_producto = :id");
        $result = $query->execute(['id' => $id]);
        $redirectUrl = $result ? '/index.php?page=admin&success=Producto eliminado correctamente' : '/index.php?page=admin&error=Error al eliminar el producto';
        header('Location: ' . $redirectUrl);
    } catch (Exception $e) {
        header('Location: /index.php?page=admin&error=' . urlencode($e->getMessage()));
    }
}

// Manejo de la petición
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add-product':
            agregarProducto($_POST);
            break;
        case 'update-product':
            actualizarProducto($_POST);
            break;
        case 'delete-product':
            eliminarProducto($_GET['id']);
            break;
        case 'listar':
            mostrarProductos();
            break;
    }
}

// Función para obtener categorías
function obtenerCategorias() {
    $db = conectarDB();
    $query = $db->query("SELECT id_categoria, nombre_categoria FROM categoria");
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Funciones de login, registro, etc.
?>  