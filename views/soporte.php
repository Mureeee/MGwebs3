<?php
extract($data);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte - MGwebs</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/styles.css">
    <style>
        /* Estilos específicos para la página de soporte */
        .support-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            color: white;
        }

        .support-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .support-header h1 {
            font-size: 2.5rem;
            color: #8b5cf6;
            margin-bottom: 1rem;
        }

        .support-header p {
            font-size: 1.1rem;
            color: #ccc;
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-section {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-section h2 {
            font-size: 2rem;
            color: #8b5cf6;
            margin-bottom: 2rem;
            text-align: center;
        }

        .faq-item {
            background: rgba(40, 40, 40, 0.5);
            border-radius: 10px;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .faq-question {
            padding: 1.5rem;
            font-size: 1.1rem;
            color: white;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .faq-question:hover {
            background: rgba(50, 50, 50, 0.5);
        }

        .faq-answer {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            color: #ccc;
        }

        .faq-item.active .faq-answer {
            padding: 1.5rem;
            max-height: 500px;
        }

        .faq-item.active .fa-chevron-down {
            transform: rotate(180deg);
        }

        .contact-form {
            max-width: 600px;
            margin: 4rem auto;
            padding: 2rem;
            background: rgba(40, 40, 40, 0.5);
            border-radius: 15px;
        }

        .contact-form h2 {
            color: #8b5cf6;
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
    </style>
</head>
<body>
    <main>
        <!-- Particles Canvas -->
        <canvas id="sparkles" class="particles-canvas"></canvas>

        <!-- Main Content -->
        <div class="content-wrapper">
            <!-- Navbar -->
            <?php include 'partials/navbar.php'; ?>

            <div class="support-container">
                <div class="support-header">
                    <h1>Centro de Soporte</h1>
                    <p>Estamos aquí para ayudarte con cualquier duda o problema que tengas con nuestros servicios.
                        Encuentra respuestas rápidas o contacta con nuestro equipo de soporte.</p>
                </div>

                <div class="faq-section">
                    <h2>Preguntas Frecuentes</h2>

                    <div class="faq-item">
                        <div class="faq-question">
                            ¿Cómo puedo empezar con MGwebs?
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            Empezar con MGwebs es muy sencillo. Solo necesitas registrarte en nuestra plataforma,
                            elegir el plan que mejor se adapte a tus necesidades y seguir nuestro proceso guiado
                            para crear tu sitio web.
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            ¿Qué métodos de pago aceptan?
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            Aceptamos múltiples métodos de pago, incluyendo tarjetas de crédito/débito,
                            PayPal y transferencias bancarias. Todos nuestros pagos son seguros y están
                            protegidos.
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            ¿MGwebs ofrece dominio y hosting?
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            Sí, todos nuestros planes incluyen hosting y puedes registrar o transferir
                            tu dominio directamente con nosotros. Nos encargamos de toda la gestión
                            técnica para que tú te centres en tu negocio.
                        </div>
                    </div>
                </div>

                <div class="contact-form">
                    <h2>Contáctanos</h2>
                    <form action="<?php echo APP_URL; ?>/enviar-consulta" method="POST">
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
    <script src="<?php echo APP_URL; ?>/public/js/particles.js"></script>
    <script src="<?php echo APP_URL; ?>/public/js/user-menu.js"></script>
    <script>
        // Manejar las FAQs
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const faqItem = question.parentElement;
                faqItem.classList.toggle('active');
            });
        });
    </script>
</body>
</html>
