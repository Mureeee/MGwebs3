<?php
session_start();
require_once 'config/database.php';

$isLoggedIn = isset($_SESSION['usuario_id']);
$primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Obtener los productos del carrito
function obtenerProductosCarrito($conn)
{
    if (empty($_SESSION['carrito'])) {
        return [];
    }

    $ids = array_keys($_SESSION['carrito']);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';

    $query = "SELECT id_producto, nombre_producto, precio, imagenes FROM producto WHERE id_producto IN ($placeholders)";
    $stmt = $conn->prepare($query);
    $stmt->execute($ids);

    $productos = [];
    while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $producto['cantidad'] = $_SESSION['carrito'][$producto['id_producto']];
        $productos[] = $producto;
    }

    return $productos;
}

try {
    $database = new Database();
    $conn = $database->getConnection();
    $productosCarrito = obtenerProductosCarrito($conn);
} catch (PDOException $e) {
    $error = "Error de conexión: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - MGwebs</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        .carrito-container {
            position: relative;
            z-index: 1;
            padding: 6rem 2rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            color: white;
        }

        .carrito-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .carrito-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }

        .carrito-info {
            flex: 1;
        }

        .carrito-acciones {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .cantidad-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cantidad-control button {
            padding: 0.25rem 0.5rem;
            border: none;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .carrito-total {
            margin-top: 2rem;
            text-align: right;
            font-size: 1.2rem;
        }

        .carrito-vacio {
            text-align: center;
            padding: 2rem;
            color: rgba(255, 255, 255, 0.6);
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
                <path d="M12 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2 2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" />
                <path d="M12 8v8" />
                <path d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5z" />
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
                        <a href="cerrar_sesion.php" class="dropdown-item">Cerrar Sesión</a>
                    </div>
                </div>

                <!-- Icono del carrito (solo para usuarios logueados) -->
                <a href="carrito.php" class="cart-icon">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
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

    <div class="carrito-container">
        <h1>Tu Carrito</h1>

        <?php if (empty($productosCarrito)): ?>
            <div class="carrito-vacio">
                <p>Tu carrito está vacío</p>
                <button class="btn btn-primary" onclick="window.location.href='productos.php'">Ver Productos</button>
            </div>
        <?php else: ?>
            <?php foreach ($productosCarrito as $producto): ?>
                <div class="carrito-item">
                    <img src="<?php echo htmlspecialchars($producto['imagenes']); ?>"
                        alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">

                    <div class="carrito-info">
                        <h3><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>
                        <p>€<?php echo number_format($producto['precio'], 2); ?></p>
                    </div>

                    <div class="carrito-acciones">
                        <div class="cantidad-control">
                            <button onclick="actualizarCantidad(<?php echo $producto['id_producto']; ?>, 'restar')">-</button>
                            <span><?php echo $producto['cantidad']; ?></span>
                            <button onclick="actualizarCantidad(<?php echo $producto['id_producto']; ?>, 'sumar')">+</button>
                        </div>
                        <button class="btn btn-danger" onclick="eliminarProducto(<?php echo $producto['id_producto']; ?>)">
                            Eliminar
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="carrito-total">
                <p>Total: €<?php
                $total = array_reduce($productosCarrito, function ($carry, $item) {
                    return $carry + ($item['precio'] * $item['cantidad']);
                }, 0);
                echo number_format($total, 2);
                ?></p>
                <button class="btn btn-primary" onclick="procesarCompra()">Proceder al Pago</button>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function actualizarCantidad(id, accion) {
            fetch('actualizar_carrito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: id,
                    accion: accion
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
        }

        function eliminarProducto(id) {
            if (confirm('¿Estás seguro de que quieres eliminar este producto del carrito?')) {
                fetch('actualizar_carrito.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: id,
                        accion: 'eliminar'
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            }
        }

        function procesarCompra() {
            // Aquí iría la lógica para procesar la compra
            alert('Funcionalidad de pago en desarrollo');
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