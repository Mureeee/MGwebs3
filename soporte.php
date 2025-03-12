<?php
require_once 'config/database.php';
session_start();

// Verificar si el usuario está logueado
$isLoggedIn = isset($_SESSION['usuario_id']);
$primeraLetra = '';
$nombreUsuario = '';
$correoUsuario = '';
$rolUsuario = '';
$itemsCarrito = 0;

// Si está logueado, obtener información del usuario
if ($isLoggedIn) {
    $primeraLetra = strtoupper(substr($_SESSION['usuario_nombre'], 0, 1));
    $nombreUsuario = $_SESSION['usuario_nombre'];
    $correoUsuario = isset($_SESSION['usuario_correo']) ? $_SESSION['usuario_correo'] : '';
    $rolUsuario = isset($_SESSION['usuario_rol']) ? $_SESSION['usuario_rol'] : '';
    
    // Calcular items en el carrito
    if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
        $itemsCarrito = array_sum($_SESSION['carrito']);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte - MGwebs</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos específicos para la página de soporte */
        .particles-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        main {
            min-height: 100vh;
            background-color: rgba(0, 0, 0, 0.96);
            position: relative;
            overflow: hidden;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
            padding-top: 1rem;
        }

        .support-container {
            max-width: 1200px;
            margin: 8rem auto 4rem;
            padding: 0 2rem;
        }

        .support-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .support-header h1 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .support-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
        }

        .support-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .support-card {
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .support-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        }

        .support-card h3 {
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .support-card h3 i {
            margin-right: 0.75rem;
            color: #6a11cb;
            font-size: 1.75rem;
        }

        .support-card p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .support-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .support-card ul li {
            margin-bottom: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: flex-start;
        }

        .support-card ul li i {
            color: #2575fc;
            margin-right: 0.5rem;
            margin-top: 0.25rem;
        }

        .faq-section {
            margin-bottom: 4rem;
        }

        .faq-section h2 {
            color: white;
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .faq-item {
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .faq-question {
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            font-weight: 500;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }

        .faq-question:hover {
            background-color: rgba(50, 50, 50, 0.95);
        }

        .faq-question i {
            transition: transform 0.3s ease;
        }

        .faq-answer {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }

        .faq-answer p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
            padding: 0 1.5rem 1.5rem;
        }

        .contact-form {
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            margin-bottom: 4rem;
        }

        .contact-form h2 {
            color: white;
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: white;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #6a11cb;
            box-shadow: 0 0 0 2px rgba(106, 17, 203, 0.2);
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            padding: 1rem 2rem;
            color: white;
            font-weight: 500;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: block;
            width: 100%;
            font-size: 1.1rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(106, 17, 203, 0.2);
        }

        /* Estilos para el navbar y menú de usuario */
        .user-menu {
            position: relative;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .user-avatar:hover {
            transform: scale(1.05);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            width: 200px;
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 100;
        }

        .user-menu:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .dropdown-item {
            display: block;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        /* Estilos para el carrito */
        .cart-icon {
            position: relative;
            margin-left: 1rem;
            margin-right: 1rem;
            color: white;
            text-decoration: none;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            font-size: 0.7rem;
            font-weight: bold;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .support-grid {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .support-header h1 {
                font-size: 2rem;
            }
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
                            <div class="user-avatar" title="<?php echo htmlspecialchars($nombreUsuario); ?>">
                                <?php echo $primeraLetra; ?>
                            </div>
                            <div class="dropdown-menu">
                                <div class="dropdown-header">
                                    <?php echo htmlspecialchars($nombreUsuario); ?>
                                </div>
                                <?php if ($rolUsuario === 'administrador'): ?>
                                    <a href="admin_panel.php" class="dropdown-item">Panel Admin</a>
                                <?php endif; ?>
                                <a href="perfil.php" class="dropdown-item">Perfil</a>
                                <a href="cerrar_sesion.php" class="dropdown-item">Cerrar Sesión</a>
                            </div>
                        </div>
                        
                        <!-- Icono del carrito (solo para usuarios logueados) -->
                        <a href="carrito.php" class="cart-icon">
                            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"/>
                                <circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>
                            <?php if ($itemsCarrito > 0): ?>
                                <span class="cart-count"><?php echo $itemsCarrito; ?></span>
                            <?php endif; ?>
                        </a>
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

            <!-- Contenido de Soporte -->
            <div class="support-container">
                <div class="support-header">
                    <h1>Centro de Soporte</h1>
                    <p>Estamos aquí para ayudarte con cualquier duda o problema que tengas con nuestros servicios. Encuentra respuestas rápidas o contacta con nuestro equipo de soporte.</p>
                </div>

                <!-- Tarjetas de Soporte -->
                <div class="support-grid">
                    <div class="support-card">
                        <h3><i class="fas fa-question-circle"></i> Ayuda Rápida</h3>
                        <p>Encuentra respuestas a las preguntas más comunes sobre nuestros servicios y productos.</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Consulta nuestras preguntas frecuentes</li>
                            <li><i class="fas fa-check"></i> Guías de uso y tutoriales</li>
                            <li><i class="fas fa-check"></i> Solución de problemas comunes</li>
                        </ul>
                    </div>

                    <div class="support-card">
                        <h3><i class="fas fa-envelope"></i> Contacto Directo</h3>
                        <p>Ponte en contacto con nuestro equipo de soporte para resolver tus dudas específicas.</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Formulario de contacto</li>
                            <li><i class="fas fa-check"></i> Correo electrónico: soporte@mgwebs.com</li>
                            <li><i class="fas fa-check"></i> Teléfono: +34 123 456 789</li>
                        </ul>
                    </div>

                    <div class="support-card">
                        <h3><i class="fas fa-book"></i> Recursos</h3>
                        <p>Accede a nuestra biblioteca de recursos para aprender más sobre nuestros servicios.</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Documentación técnica</li>
                            <li><i class="fas fa-check"></i> Vídeos tutoriales</li>
                            <li><i class="fas fa-check"></i> Blog con consejos y trucos</li>
                        </ul>
                    </div>
                </div>

                <!-- Sección de Preguntas Frecuentes -->
                <div class="faq-section">
                    <h2>Preguntas Frecuentes</h2>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            ¿Cómo puedo crear mi primera página web con MGwebs?
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Crear tu primera página web con MGwebs es muy sencillo. Solo tienes que seguir estos pasos:</p>
                            <p>1. Regístrate en nuestra plataforma o inicia sesión si ya tienes una cuenta.<br>
                               2. Haz clic en el botón "Comenzar" en la página principal.<br>
                               3. Selecciona una plantilla que se adapte a tus necesidades.<br>
                               4. Personaliza tu página con tu contenido, imágenes y colores.<br>
                               5. Publica tu página cuando estés satisfecho con el resultado.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            ¿Qué planes y precios ofrece MGwebs?
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>MGwebs ofrece diferentes planes adaptados a las necesidades de cada cliente:</p>
                            <p>- <strong>Plan Básico:</strong> Ideal para pequeños negocios o proyectos personales. Incluye hosting, dominio y plantillas básicas.<br>
                               - <strong>Plan Profesional:</strong> Para empresas en crecimiento. Incluye más funcionalidades, SEO básico y soporte prioritario.<br>
                               - <strong>Plan Premium:</strong> Para empresas establecidas. Incluye todas las funcionalidades, SEO avanzado, soporte 24/7 y más.</p>
                            <p>Puedes consultar los precios detallados en nuestra sección de productos.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            ¿Necesito conocimientos técnicos para usar MGwebs?
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>No, no necesitas conocimientos técnicos para usar MGwebs. Nuestra plataforma está diseñada para ser intuitiva y fácil de usar, incluso para principiantes.</p>
                            <p>Ofrecemos un editor visual que te permite arrastrar y soltar elementos, cambiar colores, fuentes y más sin necesidad de escribir código. Si tienes conocimientos de HTML, CSS o JavaScript, también puedes utilizarlos para personalizar aún más tu página.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            ¿Cómo puedo modificar mi página web después de publicarla?
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Puedes modificar tu página web en cualquier momento, incluso después de publicarla. Para hacerlo:</p>
                            <p>1. Inicia sesión en tu cuenta de MGwebs.<br>
                               2. Ve a tu panel de control.<br>
                               3. Selecciona la página que deseas modificar.<br>
                               4. Realiza los cambios necesarios en el editor.<br>
                               5. Guarda los cambios y vuelve a publicar la página.</p>
                            <p>Los cambios se reflejarán inmediatamente en tu sitio web publicado.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            ¿MGwebs ofrece dominio y hosting?
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Sí, MGwebs ofrece dominio y hosting incluidos en todos nuestros planes. Al crear tu página web con nosotros, no tendrás que preocuparte por contratar estos servicios por separado.</p>
                            <p>También ofrecemos la posibilidad de usar un dominio que ya poseas, si lo prefieres. Nuestro equipo de soporte puede ayudarte a configurar tu dominio existente con tu nueva página web de MGwebs.</p>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Contacto -->
                
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
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="segunda_mano.php">Segunda Mano</a></li>
                    <li><a href="soporte.php">Soporte</a></li>
                    <li><a href="contactanos.html">Contacto</a></li>
                    <?php if (!$isLoggedIn): ?>
                        <li><a href="iniciar_sesion.html">Iniciar Sesión</a></li>
                        <li><a href="registrarse.html">Registrarse</a></li>
                    <?php else: ?>
                        <li><a href="perfil.php">Mi Perfil</a></li>
                        <li><a href="carrito.php">Mi Carrito</a></li>
                    <?php endif; ?>
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
    
    <!-- Script para el menú de usuario y carrito -->
    <script>
        // Asegurarse de que el menú de usuario funcione correctamente
        document.addEventListener('DOMContentLoaded', function() {
            const userMenu = document.querySelector('.user-menu');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            if (userMenu) {
                // Alternar el menú desplegable al hacer clic en el avatar
                userMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('active');
                });
                
                // Cerrar el menú al hacer clic fuera de él
                document.addEventListener('click', function() {
                    if (dropdownMenu.classList.contains('active')) {
                        dropdownMenu.classList.remove('active');
                    }
                });
                
                // Evitar que el menú se cierre al hacer clic dentro de él
                dropdownMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
            
            // Menú móvil
            const menuButton = document.querySelector('.menu-button');
            const navLinks = document.querySelector('.nav-links');
            
            if (menuButton) {
                menuButton.addEventListener('click', function() {
                    navLinks.classList.toggle('active');
                });
            }
        });
    </script>
    
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

    <!-- Script para las preguntas frecuentes -->
    <script>
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const faqItem = question.parentElement;
                faqItem.classList.toggle('active');
            });
        });
    </script>
</body>
</html>