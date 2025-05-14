<?php
require_once 'config/database.php';
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: iniciar_sesion.html');
    exit();
}

// Variables para el navbar
$isLoggedIn = isset($_SESSION['usuario_id']);
$primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';

$mensaje = '';
$error = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $conn = $database->getConnection();

    $usuario_id = $_SESSION['usuario_id'];
    $nuevo_nombre = trim($_POST['nombre']);
    $nuevo_email = trim($_POST['correo']);
    $nueva_password = trim($_POST['nueva_password']);
    $confirmar_password = trim($_POST['confirmar_password']);

    // Verificar si el correo ya existe para otro usuario
    if ($nuevo_email !== $_SESSION['usuario_correo']) {
        $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE correo = ? AND id_usuario != ?");
        $stmt->execute([$nuevo_email, $usuario_id]);
        if ($stmt->rowCount() > 0) {
            $error = "El correo electrónico ya está en uso.";
        }
    }

    if (empty($error)) {
        try {
            // Iniciar transacción
            $conn->beginTransaction();

            // Actualizar nombre y correo
            $sql = "UPDATE usuario SET nombre = ?, correo = ? WHERE id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nuevo_nombre, $nuevo_email, $usuario_id]);

            // Si se proporcionó una nueva contraseña
            if (!empty($nueva_password)) {
                if ($nueva_password === $confirmar_password) {
                    $password_hash = password_hash($nueva_password, PASSWORD_DEFAULT);
                    $sql = "UPDATE usuario SET contraseña = ? WHERE id_usuario = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$password_hash, $usuario_id]);
                } else {
                    throw new Exception("Las contraseñas no coinciden");
                }
            }

            $conn->commit();
            
            // Actualizar datos de sesión
            $_SESSION['usuario_nombre'] = $nuevo_nombre;
            $_SESSION['usuario_correo'] = $nuevo_email;
            
            $mensaje = "Perfil actualizado correctamente";
        } catch (Exception $e) {
            $conn->rollBack();
            $error = "Error al actualizar el perfil: " . $e->getMessage();
        }
    }
}

// Obtener datos actuales del usuario
$database = new Database();
$conn = $database->getConnection();
$stmt = $conn->prepare("SELECT nombre, correo, direccion_envio FROM usuario WHERE id_usuario = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - MGwebs</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-container {
            max-width: 600px;
            margin: 8rem auto 2rem;
            padding: 2rem;
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
        }

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

        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: bold;
            color: #fff;
            font-size: 0.9rem;
        }

        .form-group input {
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #6a11cb;
            box-shadow: 0 0 0 2px rgba(106, 17, 203, 0.2);
        }

        .message {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .success {
            background: rgba(0, 255, 0, 0.1);
            color: #00ff00;
        }

        .error {
            background: rgba(255, 0, 0, 0.1);
            color: #ff0000;
        }

        h2 {
            color: #fff;
            margin-bottom: 2rem;
            font-size: 1.8rem;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            padding: 1rem;
            color: white;
            font-weight: 500;
            transition: transform 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(106, 17, 203, 0.2);
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

            <div class="profile-container">
                <h2>Mi Perfil</h2>
                
                <?php if (!empty($mensaje)): ?>
                    <div class="message success"><?php echo htmlspecialchars($mensaje); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="message error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form class="profile-form" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre de Usuario</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="direccion_envio">Dirección de Envío</label>
                        <input type="text" id="direccion_envio" name="direccion_envio" value="<?php echo htmlspecialchars($usuario['direccion_envio']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="nueva_password">Nueva Contraseña (dejar en blanco para mantener la actual)</label>
                        <input type="password" id="nueva_password" name="nueva_password">
                    </div>

                    <div class="form-group">
                        <label for="confirmar_password">Confirmar Nueva Contraseña</label>
                        <input type="password" id="confirmar_password" name="confirmar_password">
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
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
                    <li><a href="index.php">Inicio</a></li>
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

    <script src="js/particles.js"></script>
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