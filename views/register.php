<?php
// Este archivo es la Vista (View) para el formulario de registro.
// Recibe las variables necesarias del Controlador.
// No debe contener lógica de base de datos o procesamiento de formularios.

// Variables esperadas: $isLoggedIn, $primeraLetra, $message (opcional), $messageClass (opcional)

// Asegurarse de que APP_URL esté definida, aunque debería estarlo en index.php
if (!defined('APP_URL')) {
    error_log("APP_URL no está definida.");
    define('APP_URL', ''); // Fallback
}

// Incluir el parcial del encabezado (asumimos que abre <html> y <head>)
include __DIR__ . '/partials/header.php';

?>

    <title>Registrarse - MGwebs</title>
    <!-- Cargar CSS dentro del head -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Estilos específicos para la página de registro */
        .registration-container {
            max-width: 500px;
            margin: 8rem auto 2rem;
            padding: 2rem;
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            color: #fff;
        }

        .registration-form {
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
            word-break: break-word; /* Evitar desbordamiento en mensajes largos */
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

         /* Estilos para el fondo de partículas y el wrapper de contenido */
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
    </style>
</head>
<body>
    <!-- Particles Canvas - Debe estar en el body, idealmente al inicio -->
    <canvas id="sparkles" class="particles-canvas"></canvas>

    <!-- Navbar - Incluir parcial -->
    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <main>
        <!-- Main Content -->
        <div class="content-wrapper">
            <div class="registration-container">
                <h2>Crear una Cuenta</h2>
                
                <!-- Añadir este div para mostrar mensajes -->
                <div id="message" class="message" style="display: none;"></div>

                <?php if (!empty($message)): ?>
                    <!-- Mantener el div PHP por si se usa desde el controlador directamente -->
                    <div class="message <?php echo $messageClass; ?>"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>

                <form id="registrationForm" class="registration-form" action="<?php echo APP_URL; ?>/registrarse/process" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre de Usuario</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                     <div class="form-group">
                        <label for="direccion">Dirección de Envío</label>
                        <input type="text" id="direccion" name="direccion">
                    </div>

                    <button type="submit" class="btn btn-primary">Registrarse</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer - Incluir parcial -->
    <?php include __DIR__ . '/partials/footer.php'; ?>

    <!-- Scripts - Incluir al final del body -->
    <script src="<?php echo APP_URL; ?>/js/particles.js"></script>
    <script src="<?php echo APP_URL; ?>/js/menu.js"></script>
    <script src="<?php echo APP_URL; ?>/public/js/script.js"></script>

    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            // Usar el nuevo div #message para los mensajes
            const messageDiv = document.getElementById('message');
            
            // Limpiar mensajes anteriores
            messageDiv.style.display = 'none';
            messageDiv.textContent = '';
            messageDiv.className = 'message'; // Reset class

            // === Validación del lado del cliente ===
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const email = emailInput.value;
            const password = passwordInput.value;

            if (email.indexOf('@') === -1) {
                messageDiv.style.display = 'block';
                messageDiv.className = 'message error';
                messageDiv.textContent = 'El correo electrónico debe contener un @.';
                emailInput.focus(); // Enfocar el campo de correo
                return; // Detener el envío del formulario
            }

            if (password.length < 6) {
                messageDiv.style.display = 'block';
                messageDiv.className = 'message error';
                messageDiv.textContent = 'La contraseña debe tener al menos 6 caracteres.';
                passwordInput.focus(); // Enfocar el campo de contraseña
                return; // Detener el envío del formulario
            }
            // === Fin Validación del lado del cliente ===

            // Si la validación del lado del cliente pasa, proceder con el envío AJAX
            const processUrl = '<?php echo APP_URL; ?>/registrarse/process';

            fetch(processUrl, {
                method: form.method,
                body: formData
            })
            .then(response => {
                // Verificar si la respuesta es OK (status 200-299)
                if (!response.ok) {
                    // Si no es OK, intentar leer el cuerpo como texto para depuración
                    return response.text().then(text => {
                         throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                    });
                }
                // Si es OK, intentar leer como JSON
                return response.json();
            })
            .then(data => {
                messageDiv.style.display = 'block';
                if (data.success) {
                    messageDiv.className = 'message success';
                    messageDiv.textContent = data.message + '. Redirigiendo...';
                    // Redirigir después de un registro exitoso
                    setTimeout(() => {
                         window.location.href = '<?php echo APP_URL; ?>/productos'; // Usar ruta amigable
                    }, 2000);
                } else {
                    messageDiv.className = 'message error';
                    messageDiv.textContent = data.message || 'Error desconocido en el registro.';
                }
            })
            .catch(error => {
                messageDiv.style.display = 'block';
                messageDiv.className = 'message error';
                messageDiv.textContent = 'Error al procesar la solicitud: ' + error.message;
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html> 