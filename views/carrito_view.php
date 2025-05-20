<?php
// Este archivo ahora actúa como una vista para el carrito y es incluido por CarritoController
// La sesión, $isLoggedIn, $primeraLetra, $nombreUsuario, $rolUsuario y $productosCarrito
// se esperan que estén disponibles desde CarritoController->index().
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - MGwebs</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        .carrito-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 1rem;
        }

        .carrito-header h1 {
            font-size: 2.5rem;
            background: linear-gradient(to right, #a78bfa, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }

        .carrito-header .seguir-comprando {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #a78bfa;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .carrito-header .seguir-comprando:hover {
            transform: translateX(-5px);
        }

        .carrito-content {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2rem;
        }

        @media (max-width: 992px) {
            .carrito-content {
                grid-template-columns: 1fr;
            }
        }

        .carrito-items {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .carrito-item {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            background: rgba(30, 30, 30, 0.95);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .carrito-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, #a78bfa, #ec4899);
            border-radius: 4px 0 0 4px;
        }

        .carrito-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        }

        .carrito-item img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .carrito-item:hover img {
            transform: scale(1.05);
        }

        .carrito-info {
            flex: 1;
        }

        .carrito-info h3 {
            font-size: 1.4rem;
            margin: 0 0 0.5rem 0;
            color: white;
        }

        .carrito-precio {
            font-size: 1.5rem;
            font-weight: bold;
            color: #a78bfa;
            margin: 0.5rem 0;
        }

        .carrito-subtotal {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .carrito-acciones {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .cantidad-control {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 0.25rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .cantidad-control button {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.2s ease;
        }

        .cantidad-control button:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .cantidad-control span {
            width: 40px;
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .btn-eliminar {
            background: none;
            border: none;
            color: #f87171;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-eliminar:hover {
            background: rgba(248, 113, 113, 0.1);
        }

        .carrito-resumen {
            background: rgba(30, 30, 30, 0.95);
            border-radius: 12px;
            padding: 1.5rem;
            position: sticky;
            top: 100px;
            height: fit-content;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .resumen-titulo {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: white;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 0.5rem;
        }

        .resumen-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .resumen-total {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
        }

        .btn-checkout {
            display: block;
            width: 100%;
            padding: 1rem;
            margin-top: 1.5rem;
            background: linear-gradient(to right, #a78bfa, #ec4899);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(167, 139, 250, 0.4);
        }

        .metodos-pago {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .metodos-pago i {
            font-size: 1.8rem;
            color: rgba(255, 255, 255, 0.6);
            transition: color 0.3s ease;
        }

        .metodos-pago i:hover {
            color: white;
        }

        .carrito-vacio {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(30, 30, 30, 0.95);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .carrito-vacio svg {
            width: 120px;
            height: 120px;
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, 0.3);
        }

        .carrito-vacio h2 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: white;
        }

        .carrito-vacio p {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        .btn-productos {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(to right, #a78bfa, #ec4899);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-productos:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(167, 139, 250, 0.4);
        }

        .btn-productos i {
            font-size: 1.2rem;
        }

        /* Animaciones */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .carrito-item {
            animation: fadeIn 0.5s ease forwards;
        }

        .carrito-item:nth-child(2) {
            animation-delay: 0.1s;
        }

        .carrito-item:nth-child(3) {
            animation-delay: 0.2s;
        }

        .carrito-item:nth-child(4) {
            animation-delay: 0.3s;
        }

        .carrito-item:nth-child(5) {
            animation-delay: 0.4s;
        }

        /* Botón scroll arriba */
        #scrollToTopBtn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: #a78bfa;
            color: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s, opacity 0.3s;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
        }

        #scrollToTopBtn.visible {
            opacity: 1;
            pointer-events: auto;
        }

        #scrollToTopBtn:hover {
            background-color: #8b5cf6;
            transform: scale(1.1);
        }

        #scrollToTopBtn svg {
            width: 24px;
            height: 24px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .carrito-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .carrito-item img {
                width: 100%;
                height: 200px;
            }

            .carrito-acciones {
                width: 100%;
                justify-content: space-between;
                margin-top: 1rem;
            }
        }
    </style>
</head>

<body class="bg-black">
    <!-- Particles Canvas -->
    <canvas id="sparkles" class="particles-canvas"></canvas>

    <!-- Navbar -->
    <nav class="navbar slide-down">
        <a href="<?php echo APP_URL; ?>" class="logo">
            <svg class="bot-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2 2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" />
                <path d="M12 8v8" />
                <path d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5z" />
            </svg>
            <span>MGwebs</span>
        </a>

        <div class="nav-links">
            <a href="<?php echo APP_URL; ?>/caracteristicas">Características</a>
            <a href="<?php echo APP_URL; ?>/como-funciona">Cómo Funciona</a>
            <a href="<?php echo APP_URL; ?>/productos">Productos</a>
            <a href="<?php echo APP_URL; ?>/soporte">Soporte</a>
            <a href="<?php echo APP_URL; ?>/contactanos">Contáctanos</a>
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
                            <a href="<?php echo APP_URL; ?>/admin" class="dropdown-item">Panel Admin</a>
                        <?php endif; ?>
                        <a href="<?php echo APP_URL; ?>/logout" class="dropdown-item">Cerrar Sesión</a>
                    </div>
                </div>

                <!-- Icono del carrito (solo para usuarios logueados) -->
                <a href="<?php echo APP_URL; ?>/carrito" class="cart-icon">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    <?php
                    $totalCarrito = isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;
                    ?>
                    <?php if ($totalCarrito > 0): ?>
                        <span class="cart-count"><?php echo $totalCarrito; ?></span>
                    <?php endif; ?>
                </a>
            <?php else: ?>
                <button class="btn btn-ghost" onclick="window.location.href='<?php echo APP_URL; ?>/login'">Iniciar Sesión</button>
                <button class="btn btn-ghost" onclick="window.location.href='<?php echo APP_URL; ?>/registrarse'">Registrate</button>
            <?php endif; ?>

            <button class="btn btn-primary" onclick="window.location.href='<?php echo APP_URL; ?>/crearpaginaperso'">Comenzar</button>
        </div>

        <button class="menu-button">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 6h16M4 12h16m-16 6h16"/>
            </svg>
        </button>
    </nav>

    <div class="carrito-container">
        <div class="carrito-header">
            <h1>Tu Carrito</h1>
            <a href="<?php echo APP_URL; ?>/productos" class="seguir-comprando">
                <i class="fas fa-arrow-left"></i> Seguir comprando
            </a>
        </div>

        <?php if (empty($productosCarrito)): ?>
            <div class="carrito-vacio">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2>Tu carrito está vacío</h2>
                <p>Parece que aún no has añadido productos a tu carrito</p>
                <button class="btn-productos" onclick="window.location.href='<?php echo APP_URL; ?>/productos'">
                    <i class="fas fa-shopping-bag"></i> Ver Productos
                </button>
            </div>
        <?php else: ?>
            <div class="carrito-content">
                <div class="carrito-items">
                    <?php foreach ($productosCarrito as $producto): ?>
                        <div class="carrito-item">
                            <img src="<?php echo htmlspecialchars($producto['imagenes']); ?>"
                                alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">

                            <div class="carrito-info">
                                <h3><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>
                                <p class="carrito-precio">€<?php echo number_format($producto['precio'], 2); ?></p>
                                <p class="carrito-subtotal">Subtotal: €<?php echo number_format($producto['precio'] * $producto['cantidad'], 2); ?></p>
                            </div>

                            <div class="carrito-acciones">
                                <div class="cantidad-control">
                                    <button onclick="actualizarCantidad(<?php echo $producto['id_producto']; ?>, -1)">-</button>
                                    <span><?php echo $producto['cantidad']; ?></span>
                                    <button onclick="actualizarCantidad(<?php echo $producto['id_producto']; ?>, 1)">+</button>
                                </div>
                                <button class="btn-eliminar" onclick="eliminarProducto(<?php echo $producto['id_producto']; ?>)">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="carrito-resumen">
                    <h2 class="resumen-titulo">Resumen del pedido</h2>
                    
                    <?php
                    $subtotal = array_reduce($productosCarrito, function ($carry, $item) {
                        return $carry + ($item['precio'] * $item['cantidad']);
                    }, 0);
                    
                    $iva = $subtotal * 0.21; // 21% IVA
                    $total = $subtotal + $iva;
                    ?>
                    
                    <div class="resumen-item">
                        <span>Subtotal</span>
                        <span>€<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    
                    <div class="resumen-item">
                        <span>IVA (21%)</span>
                        <span>€<?php echo number_format($iva, 2); ?></span>
                    </div>
                    
                    <div class="resumen-item">
                        <span>Envío</span>
                        <span>Gratis</span>
                    </div>
                    
                    <div class="resumen-total">
                        <span>Total</span>
                        <span>€<?php echo number_format($total, 2); ?></span>
                    </div>
                    
                    <button class="btn-checkout" onclick="procesarCompra()">
                        Proceder al pago
                    </button>
                    
                    <div class="metodos-pago">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-paypal"></i>
                        <i class="fab fa-cc-apple-pay"></i>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function actualizarCantidad(id, cambio) {
            fetch('<?php echo APP_URL; ?>/carrito/update', { // Usar ruta amigable
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: id,
                    cambio: cambio
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al actualizar la cantidad.');
                });
        }

        function eliminarProducto(id) {
            if (confirm('¿Estás seguro de que quieres eliminar este producto del carrito?')) {
                fetch('<?php echo APP_URL; ?>/carrito/delete', { // Usar ruta amigable
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
        
        // Control del botón para volver arriba
        document.addEventListener('DOMContentLoaded', function () {
            const scrollBtn = document.getElementById('scrollToTopBtn');

            // Función para verificar la posición de scroll y mostrar/ocultar el botón
            function checkScrollPosition() {
                if (window.scrollY > 300) {
                    scrollBtn.classList.add('visible');
                } else {
                    scrollBtn.classList.remove('visible');
                }
            }

            // Verificar al cargar la página
            checkScrollPosition();

            // Verificar al hacer scroll
            window.addEventListener('scroll', checkScrollPosition);

            // Acción al hacer clic en el botón
            scrollBtn.addEventListener('click', function () {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
    <button id="scrollToTopBtn" aria-label="Volver arriba" title="Volver arriba">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>
</body>

</html> 