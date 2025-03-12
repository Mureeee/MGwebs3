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
    <title>Contáctanos - MGwebs</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .contact-section {
            padding: 6rem 2rem 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-top: 2rem;
        }
        
        .contact-info {
            background: rgba(30, 30, 30, 0.95);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .contact-form {
            background: rgba(30, 30, 30, 0.95);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .contact-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-align: center;
            background: linear-gradient(to right, #a78bfa, #ec4899);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .contact-subtitle {
            text-align: center;
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        
        .info-icon {
            font-size: 1.5rem;
            color: #a78bfa;
            margin-right: 1rem;
            min-width: 30px;
        }
        
        .info-text {
            color: white;
        }
        
        .info-text h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.2rem;
        }
        
        .info-text p {
            margin: 0;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.5;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: #a78bfa;
            transform: translateY(-3px);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: white;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            font-family: inherit;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #a78bfa;
        }
        
        .btn-submit {
            background: linear-gradient(to right, #a78bfa, #ec4899);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(167, 139, 250, 0.4);
        }
        
        .map-container {
            margin-top: 3rem;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            height: 400px;
        }
        
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .success-message,
        .error-message {
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            display: none;
        }
        
        .success-message {
            background: rgba(72, 187, 120, 0.1);
            color: #48bb78;
            border: 1px solid rgba(72, 187, 120, 0.2);
        }
        
        .error-message {
            background: rgba(245, 101, 101, 0.1);
            color: #f56565;
            border: 1px solid rgba(245, 101, 101, 0.2);
        }
        
        @media (max-width: 768px) {
            .contact-container {
                grid-template-columns: 1fr;
            }
            
            .contact-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <main>
        <!-- Particles Canvas -->
        <canvas id="sparkles" class="particles-canvas"></canvas>

        <!-- Navbar -->
        <nav class="navbar slide-down">
            <!-- CORREGIDO: Cambiado de index.html a index.php -->
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
                <a href="contactanos.php" class="active">Contáctanos</a>
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
                            <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'administrador'): ?>
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

        <!-- Contact Section -->
        <section class="contact-section">
            <h1 class="contact-title">Contáctanos</h1>
            <p class="contact-subtitle">Estamos aquí para ayudarte. Ponte en contacto con nosotros y te responderemos lo antes posible.</p>
            
            <div class="contact-container">
                <div class="contact-info">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-text">
                            <h3>Dirección</h3>
                            <p>Centro de Estudios Roca - Grupo Escolar Roca</p>
                            <p>Av. Meridiana, 263, 08027 Barcelona</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="info-text">
                            <h3>Teléfono</h3>
                            <p>+34 123 456 789</p>
                            <p>+34 987 654 321</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-text">
                            <h3>Email</h3>
                            <p>info@mgwebs.com</p>
                            <p>soporte@mgwebs.com</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-text">
                            <h3>Horario de Atención</h3>
                            <p>Lunes a Viernes: 9:00 AM - 6:00 PM</p>
                            <p>Sábados: 10:00 AM - 2:00 PM</p>
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="contact-form">
                    <form id="contactForm">
                        <div class="form-group">
                            <label for="nombre">Nombre Completo</label>
                            <input type="text" id="nombre" name="nombre" required value="<?php echo $isLoggedIn ? htmlspecialchars($nombreUsuario) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" id="email" name="email" required value="<?php echo $isLoggedIn ? htmlspecialchars($correoUsuario) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="asunto">Asunto</label>
                            <input type="text" id="asunto" name="asunto" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="mensaje">Mensaje</label>
                            <textarea id="mensaje" name="mensaje" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn-submit">Enviar Mensaje</button>
                        
                        <div class="success-message" id="successMessage">
                            ¡Mensaje enviado con éxito! Nos pondremos en contacto contigo pronto.
                        </div>
                        
                        <div class="error-message" id="errorMessage">
                            Ha ocurrido un error al enviar el mensaje. Por favor, inténtalo de nuevo.
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Google Maps -->
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2991.9636953957!2d2.1861737!3d41.421901!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12a4bcd4df52e8df%3A0xf5c866dc9d618114!2sCentro%20de%20Estudios%20Roca%20-%20Grupo%20Escolar%20Roca!5e0!3m2!1ses!2ses!4v1710789012345!5m2!1ses!2ses" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>
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
                    <!-- CORREGIDO: Cambiado todos los enlaces .html a .php -->
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="segunda_mano.php">Segunda Mano</a></li>
                    <li><a href="soporte.php">Soporte</a></li>
                    <li><a href="contactanos.php">Contacto</a></li>
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
                    <li><span>📍 Centro de Estudios Roca, Barcelona</span></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© MGwebs. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/menu.js"></script>
    <script>
        $(document).ready(function() {
            // Manejar el envío del formulario
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();
                
                // Aquí normalmente enviarías los datos a un servidor
                // Simulamos una respuesta exitosa
                $('#successMessage').fadeIn();
                
                // Limpiar el formulario
                this.reset();
                
                // Ocultar el mensaje después de 5 segundos
                setTimeout(function() {
                    $('#successMessage').fadeOut();
                }, 5000);
            });
            
            // Código para las partículas
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
        });
    </script>
</body>
</html>