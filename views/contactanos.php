<?php
extract($data);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contáctanos - MGwebs</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/styles.css">
    <style>
        .contact-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            color: white;
        }

        .contact-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .contact-header h1 {
            font-size: 2.5rem;
            color: #8b5cf6;
            margin-bottom: 1rem;
        }

        .contact-header p {
            font-size: 1.1rem;
            color: #ccc;
            max-width: 600px;
            margin: 0 auto;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .contact-method {
            background: rgba(40, 40, 40, 0.5);
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
        }

        .contact-method i {
            font-size: 2rem;
            color: #8b5cf6;
            margin-bottom: 1rem;
        }

        .contact-method h3 {
            color: white;
            margin-bottom: 0.5rem;
        }

        .contact-method p {
            color: #ccc;
        }

        .contact-form {
            max-width: 600px;
            margin: 0 auto;
            background: rgba(40, 40, 40, 0.5);
            padding: 2rem;
            border-radius: 15px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: white;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            background: rgba(30, 30, 30, 0.95);
            border: 1px solid #444;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .submit-btn {
            background: #8b5cf6;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background: #7c3aed;
        }

        .success-message {
            background: rgba(5, 150, 105, 0.2);
            color: #34d399;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <main>
        <!-- Main Content -->
        <div class="content-wrapper">
            <!-- Navbar -->
            <?php include 'partials/navbar.php'; ?>

            <div class="contact-container">
                <div class="contact-header">
                    <h1>Contáctanos</h1>
                    <p>¿Tienes alguna pregunta o necesitas ayuda? Estamos aquí para ayudarte.
                       Ponte en contacto con nosotros y te responderemos lo antes posible.</p>
                </div>

                <?php if (isset($_GET['success'])): ?>
                <div class="success-message">
                    Tu mensaje ha sido enviado correctamente. Nos pondremos en contacto contigo pronto.
                </div>
                <?php endif; ?>

                <div class="contact-info">
                    <div class="contact-method">
                        <i class="fas fa-envelope"></i>
                        <h3>Email</h3>
                        <p>soporte@mgwebs.com</p>
                    </div>

                    <div class="contact-method">
                        <i class="fas fa-phone"></i>
                        <h3>Teléfono</h3>
                        <p>+34 900 123 456</p>
                    </div>

                    <div class="contact-method">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3>Ubicación</h3>
                        <p>Madrid, España</p>
                    </div>
                </div>

                <div class="contact-form">
                    <form action="<?php echo APP_URL; ?>/contactanos/enviar" method="POST">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" required
                                   value="<?php echo $isLoggedIn ? htmlspecialchars($nombreUsuario) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required
                                   value="<?php echo $isLoggedIn ? htmlspecialchars($correoUsuario) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="asunto">Asunto</label>
                            <input type="text" id="asunto" name="asunto" required>
                        </div>

                        <div class="form-group">
                            <label for="mensaje">Mensaje</label>
                            <textarea id="mensaje" name="mensaje" required></textarea>
                        </div>

                        <button type="submit" class="submit-btn">Enviar Mensaje</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>

    <!-- Scripts -->
    <script src="<?php echo APP_URL; ?>/public/js/menu.js"></script>
    <script src="<?php echo APP_URL; ?>/public/js/user-menu.js"></script>
</body>
</html>
