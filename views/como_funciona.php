<?php
extract($data);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cómo Funciona - MGwebs</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos específicos para la página de cómo funciona */
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

        .how-it-works-container {
            max-width: 1200px;
            margin: 8rem auto 4rem;
            padding: 0 2rem;
        }

        .how-it-works-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .how-it-works-header h1 {
            color: white;
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .how-it-works-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
        }

        .process-timeline {
            position: relative;
            margin: 4rem 0 6rem;
        }

        .process-timeline::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 4px;
            background: linear-gradient(to bottom, #6a11cb, #2575fc);
            transform: translateX(-50%);
        }

        .timeline-item {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 6rem;
            position: relative;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item:nth-child(even) {
            justify-content: flex-end;
        }

        .timeline-content {
            width: 45%;
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .timeline-content::after {
            content: '';
            position: absolute;
            top: 20px;
            width: 20px;
            height: 20px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border-radius: 50%;
        }

        .timeline-item:nth-child(odd) .timeline-content::after {
            right: -60px;
        }

        .timeline-item:nth-child(even) .timeline-content::after {
            left: -60px;
        }

        .timeline-number {
            position: absolute;
            top: 10px;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            z-index: 2;
        }

        .timeline-item:nth-child(odd) .timeline-number {
            right: -80px;
        }

        .timeline-item:nth-child(even) .timeline-number {
            left: -80px;
        }

        .timeline-content h3 {
            color: white;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .timeline-content p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .timeline-image {
            width: 100%;
            height: 200px;
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .timeline-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.7));
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 0 0 1.5rem;
        }

        .features-list li {
            margin-bottom: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: flex-start;
        }

        .features-list li i {
            color: #2575fc;
            margin-right: 0.5rem;
            margin-top: 0.25rem;
        }

        .btn-outline {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border: 1px solid #6a11cb;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-outline:hover {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border-color: transparent;
        }

        .faq-section {
            margin-bottom: 6rem;
        }

        .faq-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .faq-header h2 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .faq-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
        }

        .faq-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .faq-item {
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .faq-item h3 {
            color: white;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .faq-item h3 i {
            color: #6a11cb;
            margin-right: 0.75rem;
            font-size: 1.5rem;
        }

        .faq-item p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        .cta-section {
            text-align: center;
            margin-bottom: 4rem;
            padding: 4rem 2rem;
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .cta-section h2 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }

        .cta-section p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto 2rem;
        }

        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
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
            display: inline-block;
            text-decoration: none;
            font-size: 1.1rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(106, 17, 203, 0.2);
        }

        .btn-secondary {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 1rem 2rem;
            color: white;
            font-weight: 500;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
            font-size: 1.1rem;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
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

        /* Estilos responsivos */
        @media (max-width: 768px) {
            .process-timeline::before {
                left: 20px;
            }

            .timeline-item {
                justify-content: flex-start;
                margin-left: 40px;
            }

            .timeline-item:nth-child(even) {
                justify-content: flex-start;
            }

            .timeline-content {
                width: 100%;
            }

            .timeline-item:nth-child(odd) .timeline-content::after,
            .timeline-item:nth-child(even) .timeline-content::after {
                left: -50px;
            }

            .timeline-item:nth-child(odd) .timeline-number,
            .timeline-item:nth-child(even) .timeline-number {
                left: -60px;
            }

            .how-it-works-header h1 {
                font-size: 2.5rem;
            }

            .cta-section h2 {
                font-size: 2rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                max-width: 300px;
                margin-bottom: 1rem;
            }
        }


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
</head>

<body>
    <main>
        <!-- Particles Canvas -->
        <canvas id="sparkles" class="particles-canvas"></canvas>

        <!-- Main Content -->
        <div class="content-wrapper">
            <!-- Navbar -->
            <?php include 'partials/navbar.php'; ?>

            <!-- Contenido de Cómo Funciona -->
            <div class="how-it-works-container">
                <div class="how-it-works-header">
                    <h1>Cómo Funciona MGwebs</h1>
                    <p>Crear tu sitio web profesional con MGwebs es rápido y sencillo. Sigue estos tres simples pasos
                        para tener tu página web lista en poco tiempo, sin necesidad de conocimientos técnicos.</p>
                </div>

                <!-- Timeline del Proceso -->
                <div class="process-timeline">
                    <!-- Paso 1: Selección de Plantilla -->
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-number">1</div>
                            <div class="timeline-image" style="background-image: url('<?php echo APP_URL; ?>/public/imagenes/seleccion plantilla.jpg')"></div>
                            <h3>Selecciona una Plantilla</h3>
                            <p>El primer paso es elegir la plantilla que mejor se adapte a tus necesidades. Ofrecemos
                                una amplia variedad de diseños profesionales para diferentes sectores y tipos de
                                negocio.</p>
                            <ul class="features-list">
                                <li><i class="fas fa-check"></i> Plantillas optimizadas para diferentes sectores</li>
                                <li><i class="fas fa-check"></i> Diseños responsivos para todos los dispositivos</li>
                                <li><i class="fas fa-check"></i> Vista previa en tiempo real</li>
                                <li><i class="fas fa-check"></i> Filtros por categoría y funcionalidad</li>
                            </ul>
                            <a href="<?php echo APP_URL; ?>/productos" class="btn-outline">Ver Plantillas Disponibles</a>
                        </div>
                    </div>

                    <!-- Paso 2: Personalización -->
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-number">2</div>
                            <div class="timeline-image" style="background-image: url('<?php echo APP_URL; ?>/public/imagenes/personaliza tu sitio.png')"></div>
                            <h3>Personaliza tu Sitio</h3>
                            <p>Una vez seleccionada la plantilla, completa un formulario detallado donde nos indicas tus
                                preferencias y necesidades específicas para personalizar tu sitio web.</p>
                            <ul class="features-list">
                                <li><i class="fas fa-check"></i> Personalización de colores y tipografías</li>
                                <li><i class="fas fa-check"></i> Integración de tu logo e imágenes</li>
                                <li><i class="fas fa-check"></i> Configuración de secciones y páginas</li>
                                <li><i class="fas fa-check"></i> Funcionalidades específicas para tu negocio</li>
                            </ul>
                            <p>Nuestro equipo de diseñadores profesionales se encargará de implementar todos los cambios
                                y personalizaciones que solicites, asegurando que tu sitio web refleje la identidad de
                                tu marca.</p>
                        </div>
                    </div>

                    <!-- Paso 3: Pago -->
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-number">3</div>
                            <div class="timeline-image" style="background-image: url('<?php echo APP_URL; ?>/public/imagenes/realiza el pago.webp')"></div>
                            <h3>Realiza el Pago</h3>
                            <p>Finalmente, procede al pago de tu sitio web. Ofrecemos diferentes métodos de pago seguros
                                y flexibles para tu comodidad.</p>
                            <ul class="features-list">
                                <li><i class="fas fa-check"></i> Pago con tarjeta de crédito/débito</li>
                                <li><i class="fas fa-check"></i> Transferencia bancaria</li>
                                <li><i class="fas fa-check"></i> PayPal y otros métodos electrónicos</li>
                                <li><i class="fas fa-check"></i> Opciones de pago fraccionado</li>
                            </ul>
                            <p>Una vez completado el pago, nuestro equipo comenzará a trabajar en tu proyecto. Recibirás
                                actualizaciones regulares sobre el progreso y podrás solicitar ajustes durante el
                                proceso de desarrollo.</p>
                        </div>
                    </div>

                    <!-- Paso 4: Entrega -->
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-number">4</div>
                            <div class="timeline-image" style="background-image: url('<?php echo APP_URL; ?>/public/imagenes/recibe tu sitio web.jpeg')"></div>
                            <h3>Recibe tu Sitio Web</h3>
                            <p>En un plazo de 7 a 14 días (dependiendo de la complejidad del proyecto), recibirás tu
                                sitio web completamente funcional y listo para ser publicado.</p>
                            <ul class="features-list">
                                <li><i class="fas fa-check"></i> Sitio web completamente funcional</li>
                                <li><i class="fas fa-check"></i> Optimizado para SEO</li>
                                <li><i class="fas fa-check"></i> Adaptado a todos los dispositivos</li>
                                <li><i class="fas fa-check"></i> Soporte técnico incluido</li>
                            </ul>
                            <p>Además, te proporcionamos acceso a un panel de control intuitivo donde podrás gestionar
                                tu contenido, realizar actualizaciones y monitorizar el rendimiento de tu sitio web.</p>
                        </div>
                    </div>
                </div>

                <!-- Sección de Preguntas Frecuentes -->
                <div class="faq-section">
                    <div class="faq-header">
                        <h2>Preguntas Frecuentes</h2>
                        <p>Resolvemos tus dudas sobre el proceso de creación de tu sitio web con MGwebs.</p>
                    </div>

                    <div class="faq-grid">
                        <div class="faq-item">
                            <h3><i class="fas fa-question-circle"></i> ¿Cuánto tiempo tarda en estar listo mi sitio web?
                            </h3>
                            <p>El tiempo de entrega varía según la complejidad del proyecto. Para sitios web básicos, el
                                plazo es de 7 a 10 días. Para proyectos más complejos como tiendas online o
                                marketplaces, puede tomar entre 14 y 21 días.</p>
                        </div>

                        <div class="faq-item">
                            <h3><i class="fas fa-question-circle"></i> ¿Puedo solicitar cambios después de la entrega?
                            </h3>
                            <p>Sí, ofrecemos un período de revisión de 15 días después de la entrega, durante el cual
                                puedes solicitar ajustes sin costo adicional. Después de este período, los cambios se
                                facturarán según su complejidad.</p>
                        </div>

                        <div class="faq-item">
                            <h3><i class="fas fa-question-circle"></i> ¿Incluye hosting y dominio?</h3>
                            <p>Todos nuestros planes incluyen hosting por un año. El dominio puede adquirirse por
                                separado o transferirse si ya posees uno. También ofrecemos paquetes que incluyen
                                dominio gratuito por el primer año.</p>
                        </div>

                        <div class="faq-item">
                            <h3><i class="fas fa-question-circle"></i> ¿Qué ocurre si no me gusta el diseño final?</h3>
                            <p>Trabajamos en estrecha colaboración contigo durante todo el proceso para asegurar tu
                                satisfacción. Si no estás conforme con el resultado, realizaremos los ajustes necesarios
                                hasta que el diseño cumpla con tus expectativas.</p>
                        </div>

                        <div class="faq-item">
                            <h3><i class="fas fa-question-circle"></i> ¿Ofrecen mantenimiento para el sitio web?</h3>
                            <p>Sí, disponemos de planes de mantenimiento mensuales que incluyen actualizaciones de
                                contenido, copias de seguridad, monitorización de seguridad y soporte técnico
                                prioritario.</p>
                        </div>

                        <div class="faq-item">
                            <h3><i class="fas fa-question-circle"></i> ¿Puedo gestionar yo mismo el contenido?</h3>
                            <p>Absolutamente. Te proporcionamos acceso a un panel de administración intuitivo donde
                                podrás actualizar textos, imágenes y otros contenidos de tu sitio web sin necesidad de
                                conocimientos técnicos.</p>
                        </div>
                    </div>
                </div>

                <!-- Sección de CTA -->
                <div class="cta-section">
                    <h2>¿Listo para crear tu sitio web?</h2>
                    <p>Comienza hoy mismo a construir tu presencia online con MGwebs. Sigue nuestro sencillo proceso y
                        tendrás tu sitio web profesional en poco tiempo.</p>
                    <div class="cta-buttons">
                        <a href="<?php echo APP_URL; ?>/crearpaginaperso" class="btn-primary">Comenzar Ahora</a>
                        <a href="<?php echo APP_URL; ?>/productos" class="btn-secondary">Ver Planes y Precios</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>

    <!-- Scripts -->
    <script src="<?php echo APP_URL; ?>/public/js/menu.js"></script>

    <!-- Script para el menú de usuario y carrito -->
    <script>
        // Asegurarse de que el menú de usuario funcione correctamente
        document.addEventListener('DOMContentLoaded', function () {
            const userMenu = document.querySelector('.user-menu');
            const dropdownMenu = document.querySelector('.dropdown-menu');

            if (userMenu) {
                // Alternar el menú desplegable al hacer clic en el avatar
                userMenu.addEventListener('click', function (e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('active');
                });

                // Cerrar el menú al hacer clic fuera de él
                document.addEventListener('click', function () {
                    if (dropdownMenu.classList.contains('active')) {
                        dropdownMenu.classList.remove('active');
                    }
                });

                // Evitar que el menú se cierre al hacer clic dentro de él
                dropdownMenu.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            }

            // Menú móvil
            const menuButton = document.querySelector('.menu-button');
            const navLinks = document.querySelector('.nav-links');

            if (menuButton) {
                menuButton.addEventListener('click', function () {
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