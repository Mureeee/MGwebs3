<?php
require_once 'config/database.php';
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

    public function getProductos() {
        try {
            $query = "SELECT p.id_producto, p.nombre_producto, p.descripcion, p.precio, c.nombre_categoria, p.imagenes 
                     FROM producto p 
                     LEFT JOIN categoria c ON p.categoria_id = c.id_categoria";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
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
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MGwebs</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Añadir más espacio entre el navbar y la sección de productos */
        .products-section {
            margin-top: 6rem; /* Aumentar el margen superior */
            padding-top: 2rem; /* Añadir padding superior adicional */
        }
        
        /* Asegurar que el contenido no se solape con el navbar */
        .content-wrapper {
            padding-top: 1rem;
        }
        
        /* Estilo para las tarjetas de productos */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            padding: 0 2rem;
        }
        
        .product-card {
            background: rgba(30, 30, 30, 0.95);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .product-info {
            padding: 1.5rem;
        }
        
        .product-info h3 {
            margin-top: 0;
            color: white;
            font-size: 1.4rem;
        }
        
        .product-category {
            color: #6a11cb;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .product-description {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        
        .product-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: white;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <main>
        <!-- Particles Canvas -->
        <canvas id="sparkles" class="particles-canvas"></canvas>

        <!-- Main Content -->
        <div class="content-wrapper">
            <!-- Navbar -->
            <nav class="navbar slide-down">
                <a href="index.php" class="logo">
                    <svg class="bot-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2 2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
                        <path d="M12 8v8"/>
                        <path d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5z"/>
                    </svg>
                    <span>MGwebs</span>
                </a>

                <div class="nav-links">
                    <a href="caracteristicas.php">Características</a>
                    <a href="como_funciona.php">Cómo Funciona</a>
                    <a href="productos.php">Productos</a>
                    <a href="soporte.php" class="active">Soporte</a>
                    <a href="contactanos.php">Contáctanos</a>
                </div>

                <div class="auth-buttons">
                    <?php if ($isLoggedIn): ?>
                        <div class="user-menu">
                            <div class="user-avatar" title="<?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>">
                                <?php echo $primeraLetra; ?>
                            </div>
                            <div class="dropdown-menu">
                                <div class="dropdown-header">
                                    <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                                </div>
                                <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'administrador'): ?>
                                    <a href="admin_panel.php" class="dropdown-item">Panel Admin</a>
                                <?php endif; ?>
                                <a href="perfil.php" class="dropdown-item">Perfil</a>
                                <a href="cerrar_sesion.php" class="dropdown-item">Cerrar Sesión</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <button class="btn btn-ghost" onclick="window.location.href='iniciar_sesion.html'">Iniciar Sesión</button>
                        <button class="btn btn-ghost" onclick="window.location.href='registrarse.html'">Registrate</button>
                    <?php endif; ?>
                    <button class="btn btn-primary" onclick="window.location.href='crearpaginaperso.php'">Comenzar</button>
                </div>

                <button class="menu-button">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 6h16M4 12h16m-16 6h16"/>
                    </svg>
                </button>
            </nav>

            <!-- Contenido de Productos -->
            <div class="products-section">
                <?php
                $producto = new Producto();
                $productos = $producto->getProductos();
                ?>

                <div class="products-grid">
                    <?php foreach ($productos as $prod): ?>
                        <div class="product-card">
                            <img src="<?php echo htmlspecialchars($prod['imagenes']); ?>" 
                                 alt="<?php echo htmlspecialchars($prod['nombre_producto']); ?>"
                                 class="product-image">
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($prod['nombre_producto']); ?></h3>
                                <p class="product-category"><?php echo htmlspecialchars($prod['nombre_categoria']); ?></p>
                                <p class="product-description"><?php echo htmlspecialchars($prod['descripcion']); ?></p>
                                <p class="product-price">€<?php echo number_format($prod['precio'], 2); ?></p>
                                <a href="detalle_producto.php?id=<?php echo $prod['id_producto']; ?>" class="btn btn-primary">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Sobre MGwebs</h3>
                <p>Tu pagina web <br>de las paginas Webs</p>
            </div>

            <div class="footer-section">
                <h3>Enlaces Útiles</h3>
                <ul class="footer-links">
                    <li><a href="index.html">Inicio</a></li>
                    <li><a href="segunda_mano.php">Segunda Mano</a></li>
                    <li><a href="soporte.html">Soporte</a></li>
                    <li><a href="contacto.html">Contacto</a></li>
                    <li><a href="iniciar_sesion.html">Iniciar Sesión</a></li>
                    <li><a href="registrarse.html">Registrarse</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Síguenos</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h3>Contacto</h3>
                <ul class="footer-links">
                    <li><span>📞 +34 123 456 789</span></li>
                    <li><span>✉️ info@mgwebs.com</span></li>
                    <li><span>📍 Calle Principal 123, Ciudad</span></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p> MGwebs. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="js/menu.js"></script>
    
    <!-- Código de las partículas -->
    <script>
        // Código de las partículas
        const canvas = document.getElementById('sparkles');
        const ctx = canvas.getContext('2d');
        let particles = [];

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        class Particle {
            constructor() {
                this.reset();
            }

            reset() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.vx = (Math.random() - 0.5) * 0.5;
                this.vy = (Math.random() - 0.5) * 0.5;
                this.alpha = Math.random() * 0.5 + 0.2;
                this.size = Math.random() * 1.5 + 0.5;
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;

                if (this.x < 0 || this.x > canvas.width || 
                    this.y < 0 || this.y > canvas.height) {
                    this.reset();
                }
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255, 255, 255, ${this.alpha})`;
                ctx.fill();
            }
        }

        function initParticles() {
            particles = [];
            for (let i = 0; i < 100; i++) {
                particles.push(new Particle());
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(particle => {
                particle.update();
                particle.draw();
            });
            requestAnimationFrame(animate);
        }

        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();
        initParticles();
        animate();
    </script>
</body>
</html>