<?php
require_once 'config/database.php';

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

    public function getProductos() {
        try {
            $query = "SELECT id_producto, nombre_producto, descripcion, precio, categoria_id FROM producto";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }

    public function getProductoById($id) {
        try {
            $query = "SELECT id_producto, nombre_producto, descripcion, precio, categoria_id FROM producto WHERE id_producto = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el producto: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MGwebs - Productos</title>
    <link rel="stylesheet" href="styles_login.css">
    <link rel="stylesheet" href="productos_grid.css">
</head>
<body>
    <main class="min-h-screen bg-black">
        <!-- Particles Canvas -->
        <canvas id="sparkles" class="particles-canvas"></canvas>

        <div class="productos-container">
            <h1 class="productos-titulo">Nuestros Productos</h1>
            <div class="productos-grid">
                <?php
                $producto = new Producto();
                $productos = $producto->getProductos();
                
                foreach ($productos as $item):
                    // Crear el nombre de la imagen basado en el nombre del producto
                    $nombreImagen = strtolower(str_replace(' ', '', $item['nombre_producto'])) . '.png';
                    $rutaImagen = 'imagenes/' . $nombreImagen;
                ?>
                <div class="producto-card">
                    <img class="producto-imagen" 
                         src="<?php echo htmlspecialchars($rutaImagen); ?>" 
                         alt="<?php echo htmlspecialchars($item['nombre_producto']); ?>">
                    <h2 class="producto-titulo"><?php echo htmlspecialchars($item['nombre_producto']); ?></h2>
                    <p class="producto-descripcion"><?php echo htmlspecialchars($item['descripcion']); ?></p>
                    <div class="producto-precio">
                        $<?php echo number_format($item['precio'], 2); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>
