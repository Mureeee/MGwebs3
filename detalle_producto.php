<?php
session_start();
require_once 'config/database.php';

$isLoggedIn = isset($_SESSION['usuario_id']);
$primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';

// Obtener el ID del producto de la URL
$id_producto = isset($_GET['id']) ? (int)$_GET['id'] : 0;

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

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Función para agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isLoggedIn) {
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    
    if (isset($_POST['agregar_carrito'])) {
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;
        if (isset($_SESSION['carrito'][$id_producto])) {
            $_SESSION['carrito'][$id_producto] += $cantidad;
        } else {
            $_SESSION['carrito'][$id_producto] = $cantidad;
        }
        header('Location: carrito.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['nombre_producto']); ?> - MGwebs</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .producto-detalle {
            padding: 6rem 2rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            color: white;
            position: relative;
            z-index: 1;
        }

        .producto-contenido {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .producto-imagen {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .producto-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .producto-categoria {
            display: inline-block;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .producto-precio {
            font-size: 2rem;
            font-weight: bold;
            color: #4CAF50;
            margin: 1rem 0;
        }

        .cantidad-control {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1rem 0;
        }

        .cantidad-control button {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .cantidad-control button:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .cantidad-control span {
            font-size: 1.2rem;
            min-width: 40px;
            text-align: center;
        }

        .btn-agregar {
            background: linear-gradient(90deg, #4CAF50, #45a049);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: transform 0.3s;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-agregar:hover {
            transform: translateY(-2px);
        }

        .descripcion {
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.8);
        }

        @media (max-width: 768px) {
            .producto-contenido {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="bg-black">
    <!-- Particles Canvas -->
    <canvas id="sparkles" class="particles-canvas"></canvas>

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
            <a href="soporte.php">Soporte</a>
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
                        <a href="cerrar_sesion.php" class="dropdown-item">Cerrar Sesión</a>
                    </div>
                </div>
                
                <a href="carrito.php" class="cart-icon">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    <?php if (!empty($_SESSION['carrito'])): ?>
                        <span class="cart-count"><?php echo array_sum($_SESSION['carrito']); ?></span>
                    <?php endif; ?>
                </a>
            <?php else: ?>
                <button class="btn btn-ghost" onclick="window.location.href='iniciar_sesion.html'">Iniciar Sesión</button>
                <button class="btn btn-ghost" onclick="window.location.href='registrarse.html'">Registrate</button>
            <?php endif; ?>
            
            <button class="btn btn-primary" onclick="window.location.href='crearpaginaperso.php'">Comenzar</button>
        </div>
    </nav>

    <div class="producto-detalle">
        <div class="producto-contenido">
            <div class="producto-imagen-container">
                <img src="<?php echo htmlspecialchars($producto['imagenes']); ?>" 
                     alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                     class="producto-imagen">
            </div>
            
            <div class="producto-info">
                <span class="producto-categoria">
                    <?php echo htmlspecialchars($producto['nombre_categoria']); ?>
                </span>
                
                <h1><?php echo htmlspecialchars($producto['nombre_producto']); ?></h1>
                
                <div class="producto-precio">
                    €<?php echo number_format($producto['precio'], 2); ?>
                </div>
                
                <p class="descripcion">
                    <?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?>
                </p>

                <?php if ($isLoggedIn): ?>
                    <form method="POST" class="form-agregar">
                        <div class="cantidad-control">
                            <button type="button" onclick="actualizarCantidad(-1)">-</button>
                            <span id="cantidad">1</span>
                            <button type="button" onclick="actualizarCantidad(1)">+</button>
                            <input type="hidden" name="cantidad" id="cantidad-input" value="1">
                        </div>
                        <button type="submit" name="agregar_carrito" class="btn-agregar">
                            Agregar al Carrito
                        </button>
                    </form>
                <?php else: ?>
                    <button class="btn-agregar" onclick="window.location.href='iniciar_sesion.html'">
                        Iniciar Sesión para Comprar
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Función para actualizar la cantidad
        function actualizarCantidad(cambio) {
            const cantidadSpan = document.getElementById('cantidad');
            const cantidadInput = document.getElementById('cantidad-input');
            let cantidad = parseInt(cantidadSpan.textContent);
            
            cantidad = Math.max(1, cantidad + cambio);
            cantidadSpan.textContent = cantidad;
            cantidadInput.value = cantidad;
        }

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