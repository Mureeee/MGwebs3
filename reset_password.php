<?php
session_start();
require_once 'config/database.php';

// Verificar si se proporcionó un token
if (!isset($_GET['token']) || empty($_GET['token'])) {
    header('Location: iniciar_sesion.html');
    exit;
}

$token = $_GET['token'];
$tokenValido = false;
$tokenExpirado = false;
$nombreUsuario = '';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Verificar si el token existe y no ha expirado
    $query = "SELECT r.id_usuario, r.expiracion, r.creado, u.nombre 
              FROM recuperacion_password r 
              JOIN usuario u ON r.id_usuario = u.id_usuario 
              WHERE r.token = ? AND r.expiracion > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->execute([$token]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($resultado) {
        $tokenValido = true;
        $nombreUsuario = $resultado['nombre'];
    } else {
        // Verificar si el token existe pero ha expirado
        $query = "SELECT r.expiracion 
                  FROM recuperacion_password r 
                  WHERE r.token = ? AND r.expiracion <= NOW()";
        $stmt = $conn->prepare($query);
        $stmt->execute([$token]);
        
        if ($stmt->fetch()) {
            $tokenExpirado = true;
        }
    }
} catch (PDOException $e) {
    // Error en la base de datos
    $tokenValido = false;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MGwebs - Restablecer Contraseña</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .message {
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .success {
            background-color: rgba(0, 255, 0, 0.2);
            color: #00ff00;
        }
        .error {
            background-color: rgba(255, 0, 0, 0.2);
            color: #ff0000;
        }
        .info {
            background-color: rgba(0, 0, 255, 0.2);
            color: #0000ff;
        }
        .password-requirements {
            font-size: 0.8rem;
            margin-top: 5px;
            color: #ddd;
        }
    </style>
</head>
<body>
    <main>
        <!-- Particles Canvas -->
        <canvas id="sparkles" class="particles-canvas"></canvas>

        <!-- Navbar -->
        <nav class="navbar slide-down">
            <a href="index.html" class="logo">
                <svg class="bot-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2 2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
                    <path d="M12 8v8"/>
                    <path d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5z"/>
                </svg>
                <span>MGwebs</span>
            </a>

            <div class="nav-links">
                <a href="caracteristicas.html">Características</a>
                <a href="como_funciona.html">Cómo Funciona</a>
                <a href="productos.php">Productos</a>
                <a href="soporte.html">Soporte</a>
                <a href="contactanos.html">Contáctanos</a>
            </div>

            <div class="auth-buttons">
                <button class="btn btn-ghost" onclick="window.location.href='iniciar_sesion.html'">Iniciar Sesión</button>
                <button class="btn btn-ghost" onclick="window.location.href='registrarse.html'">Registrate</button>
                <button class="btn btn-primary" onclick="window.location.href='productos.php'">Comenzar</button>
            </div>

            <button class="menu-button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16m-16 6h16"/>
                </svg>
            </button>
        </nav>

        <div class="login-section">
            <div class="login-container">
                <h1>Restablecer Contraseña</h1>
                
                <?php if ($tokenExpirado): ?>
                    <div class="message error">
                        <p>El enlace ha expirado. Por favor, solicita un nuevo enlace de recuperación.</p>
                        <p><a href="recuperar_password.html" style="color: #2575fc;">Solicitar nuevo enlace</a></p>
                    </div>
                <?php elseif (!$tokenValido): ?>
                    <div class="message error">
                        <p>El enlace no es válido. Por favor, verifica que hayas copiado correctamente la URL del correo.</p>
                        <p><a href="recuperar_password.html" style="color: #2575fc;">Solicitar nuevo enlace</a></p>
                    </div>
                <?php else: ?>
                    <p>Hola <?php echo htmlspecialchars($nombreUsuario); ?>, establece tu nueva contraseña a continuación:</p>
                    
                    <form id="resetForm" action="procesar_reset.php" method="POST">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        
                        <div class="form-group">
                            <label for="password">Nueva Contraseña:</label>
                            <input type="password" id="password" name="password" required>
                            <p class="password-requirements">La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula y un número.</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirmar Contraseña:</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                        <p class="message" id="resetMessage"></p>
                    </form>
                <?php endif; ?>
                
                <p class="register-link">¿Recordaste tu contraseña? <a href="iniciar_sesion.html">Iniciar sesión</a></p>
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
            <p> 2025 MGwebs. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="js/particles.js"></script>
    <script src="js/menu.js"></script>
    <script>
        <?php if ($tokenValido): ?>
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const messageElement = document.getElementById('resetMessage');
            
            // Validar contraseña
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
            
            if (!passwordRegex.test(password)) {
                messageElement.textContent = 'La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula y un número.';
                messageElement.className = 'message error';
                return;
            }
            
            if (password !== confirmPassword) {
                messageElement.textContent = 'Las contraseñas no coinciden.';
                messageElement.className = 'message error';
                return;
            }
            
            // Mostrar mensaje de carga
            messageElement.textContent = 'Procesando...';
            messageElement.className = 'message info';
            
            const formData = new FormData(this);
            
            fetch('procesar_reset.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageElement.textContent = data.message;
                    messageElement.className = 'message success';
                    
                    // Redirigir después de 3 segundos
                    setTimeout(function() {
                        window.location.href = 'iniciar_sesion.html';
                    }, 3000);
                } else {
                    messageElement.textContent = data.message;
                    messageElement.className = 'message error';
                }
            })
            .catch(error => {
                messageElement.textContent = 'Error al conectar con el servidor';
                messageElement.className = 'message error';
            });
        });
        <?php endif; ?>
    </script>
</body>
</html>

