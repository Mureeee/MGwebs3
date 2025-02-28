<?php
require_once 'config/database.php';

class Producto {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todos los productos
    public function getProductos() {
        $query = "SELECT id_producto, nombre_producto, descripcion, precio, categoria_id FROM producto";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un producto por su id
    public function getProductoById($id) {
        $query = "SELECT id_producto, nombre_producto, descripcion, precio, categoria_id FROM producto WHERE id_producto = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MGwebs</title>
    <link rel="stylesheet" href="styles_login.css">
    
</head>
<body>
    <main class="min-h-screen bg-black">
        <!-- Particles Canvas -->
        <canvas id="sparkles" class="particles-canvas"></canvas>

        <nav class="navbar slide-down">
    <a href="index.html" class="logo">
        <svg class="bot-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2 2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
            <path d="M12 8v8"/>
            <path d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5z"/>
        </svg>
        <span>MGwebs</span>
    </a>

    <div class="nav-links">
        <a href="caracteristicas.html">Características</a>
        <a href="como_funciona.html">Cómo Funciona</a>
        <a href="productos.php">Productos</a>
        <a href="soporte.html">Soporte</a>
        <a href="contactanos.html">Contáctanos</a>
    </div>

    <div class="auth-buttons">
        <button class="btn btn-ghost" onclick="window.location.href='iniciar_sesion.html'">Iniciar Sesión</button>
        <button class="btn btn-ghost" onclick="window.location.href='registrarse.html'">Registrate</button>
        <button class="btn btn-primary" onclick="window.location.href='productos.html'">Comenzar</button>
    </div>

    <button class="menu-button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 6h16M4 12h16m-16 6h16"/>
        </svg>
    </button>
</nav>

<div class="container">
    <header>
        <h1>Nuestros Productos</h1>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - MGwebs</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="productos.css">
    </header>
<br>
    <main>
        <div class="productos-grid">
            <?php
            require_once 'Producto.php';
            $producto = new Producto();
            $productos = $producto->getProductos();
            
            foreach ($productos as $item):
            ?>
            <div class="producto-card">
                <div class="producto-imagen">
                    <img src="<?php echo htmlspecialchars(isset($item['imagen']) ? $item['imagen'] : 'img/default-product.jpg'); ?>" 
                         alt="<?php echo htmlspecialchars($item['nombre_producto']); ?>">
                </div>
                <div class="producto-info">
                    <h2><?php echo htmlspecialchars($item['nombre_producto']); ?></h2>
                    <p class="descripcion"><?php echo htmlspecialchars($item['descripcion']); ?></p>
                    <div class="precio-container">
                        <span class="precio">$<?php echo number_format($item['precio'], 2); ?></span>
                        <button class="btn-comprar">Ver Detalles</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<script src="public/js/script.js"></script>
                   
    </main>
    <script src="script.js"></script>
</body>
</div>
</html>


