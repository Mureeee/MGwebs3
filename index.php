<?php
session_start();
$isLoggedIn = isset($_SESSION['usuario_id']);
$primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MGwebs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-black">
    <!-- Particles Canvas -->
    <canvas id="sparkles" class="particles-canvas"></canvas>

    <!-- Navbar -->
    <nav class="navbar slide-down">
        <div class="logo">
            <svg class="bot-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2 2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
                <path d="M12 8v8"/>
                <path d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5z"/>
            </svg>
            <span>MGwebs</span>
        </div>

        <div class="nav-links">
            <a href="caracteristicas.html">Características</a>
            <a href="como_funciona.html">Cómo Funciona</a>
            <a href="productos.php">Productos</a>
            <a href="soporte.html">Soporte</a>
            <a href="contactanos.html">Contáctanos</a>
        </div>

        <div class="auth-buttons">
            <?php if ($isLoggedIn): ?>
                <div class="user-avatar" title="<?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>">
                    <?php echo $primeraLetra; ?>
                </div>
                <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'administrador'): ?>
                    <button class="btn btn-primary" onclick="window.location.href='admin_panel.php'">Panel Admin</button>
                <?php endif; ?>
            <?php else: ?>
                <button class="btn btn-ghost" onclick="window.location.href='iniciar_sesion.html'">Iniciar Sesión</button>
                <button class="btn btn-ghost" onclick="window.location.href='registrarse.html'">Registrate</button>
            <?php endif; ?>
            <button class="btn btn-primary" onclick="window.location.href='productos.php'">Comenzar</button>
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
            <button class="btn btn-primary" onclick="window.location.href='productos.php'">
                Pagina mas Vendida
            </button>
            <button class="btn btn-outline" onclick="window.location.href='productos.php'">
                Ver mas Páginas
            </button>
        </div>
    </div>

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