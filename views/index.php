<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MGwebs</title>
    <link rel="stylesheet" href="public/styles.css">
</head>

<body class="bg-black">
    <!-- Particles Canvas -->
    <canvas id="sparkles" class="particles-canvas"></canvas>

    <!-- Navbar -->
    <nav class="navbar slide-down">
        <div class="logo">
            <svg class="bot-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2 2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" />
                <path d="M12 8v8" />
                <path d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5z" />
            </svg>
            <span>MGwebs</span>
        </div>

        <div class="nav-links">
            <a href="controllers/caracteristicas.php">Características</a>
            <a href="controllers/como_funciona.php">Cómo Funciona</a>
            <a href="controllers/productos.php">Productos</a>
            <a href="controllers/soporte.php">Soporte</a>
            <a href="controllers/contactanos.php">Contáctanos</a>
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
                            <a href="controllers/admin_panel.php" class="dropdown-item">Panel Admin</a>
                        <?php endif; ?>
                        <a href="controllers/perfil.php" class="dropdown-item">Perfil</a>
                        <a href="controllers/cerrar_sesion.php" class="dropdown-item">Cerrar Sesión</a>

                    </div>
                </div>

                <!-- Icono del carrito (solo para usuarios logueados) -->
                <a href="controllers/carrito.php" class="cart-icon">
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
                <button class="btn btn-ghost" onclick="window.location.href='views/login.php'">Iniciar Sesión</button>
                <button class="btn btn-ghost" onclick="window.location.href='registrarse.html'">Registrate</button>
            <?php endif; ?>

            <button class="btn btn-primary" onclick="window.location.href='controllers/crearpaginaperso.php'">Comenzar</button>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="content-center">
        <h1 class="main-title">
            Transforma tu negocio con
            <span class="gradient-text">MGwebs</span>
        </h1>
        <p class="main-subtitle">
            Crea tu página web y deja que nuestro equipo la transforme en una
            herramienta efectiva para tu negocio.
        </p>
        <div class="button-container">
            <button class="btn btn-primary" onclick="window.location.href='controllers/detalle_producto.php?id=16'">
                Pagina mas Vendida
            </button>
            <button class="btn btn-outline" onclick="window.location.href='controllers/productos.php'">
                Ver mas Páginas
            </button>
        </div>
    </div>
    <style>
        #scrollToTopBtn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: #a78bfa;
            /* Color lila */
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
    </style>
    <script src="public/js/script.js"></script>
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