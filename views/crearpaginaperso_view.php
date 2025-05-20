<?php
// Este archivo ahora actúa como una vista y es incluido por CrearPaginaPersoController
// La sesión ya se inició en index.php y las variables como $isLoggedIn, $primeraLetra, etc. deberían estar disponibles.
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MGwebs - Crear tu Página Web</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/styles.css">
    <script src="https://code.jquery.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .categoria-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .categoria-card:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-5px);
        }

        .categoria-card.selected {
            border-color: var(--purple-500);
            background: rgba(139, 92, 246, 0.1);
        }

        .categoria-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .categoria-icon {
            font-size: 2rem;
            margin-bottom: 15px;
            color: var(--purple-500);
        }

        .categoria-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .categoria-description {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .personalizacion-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .step-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.8rem;
        }

        .step-description {
            text-align: center;
            margin-bottom: 40px;
            opacity: 0.8;
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .form-group textarea {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: var(--purple-500);
        }

        .success-message {
            text-align: center;
            padding: 20px;
            background: rgba(72, 187, 120, 0.1);
            border-radius: 8px;
            margin-top: 20px;
            display: none;
        }

        .error-message {
            text-align: center;
            padding: 20px;
            background: rgba(245, 101, 101, 0.1);
            border-radius: 8px;
            margin-top: 20px;
            display: none;
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

        <!-- Navbar -->
        <?php // Incluir el partial de la barra de navegación si existe y es reusable ?>
        <?php 
            // Variables necesarias para views/partials/navbar.php si se incluye
            $isLoggedIn = isset($_SESSION['usuario_id']);
            $nombreUsuario = $isLoggedIn ? $_SESSION['usuario_nombre'] : '';
            $primeraLetra = $isLoggedIn ? strtoupper(substr($nombreUsuario, 0, 1)) : '';
            $rolUsuario = $isLoggedIn ? $_SESSION['usuario_rol'] : '';
        ?>
        <?php include __DIR__ . '/partials/navbar.php'; ?>

        <div class="personalizacion-container">
            <?php if (!isset($_SESSION['usuario_id'])): ?>
                <div class="step-title">Inicia sesión para continuar</div>
                <div class="step-description">Necesitas iniciar sesión o registrarte para poder crear tu página web
                    personalizada.</div>
                <div style="text-align: center; margin-top: 30px;">
                    <button class="btn btn-primary" onclick="window.location.href='<?php echo APP_URL; ?>/login'">Iniciar
                        Sesión</button>
                    <button class="btn btn-outline" onclick="window.location.href='<?php echo APP_URL; ?>/registrarse'">Registrarse</button>
                </div>
                <br>

            <?php else: ?>
                <br>
                <br>
                <br>
                <br>
                <form id="personalizacionForm">
                    <div class="step active" id="step1">
                        <h2 class="step-title">Elige el tipo de página web que necesitas</h2>
                        <p class="step-description">Selecciona la categoría que mejor se adapte a tu proyecto</p>

                        <div class="categoria-grid">
                            <div class="categoria-card" data-categoria="1">
                                <div class="categoria-icon">🛒</div>
                                <div class="categoria-title">Tienda Online</div>
                                <div class="categoria-description">Ideal para vender productos físicos o digitales con
                                    carrito de compras.</div>
                            </div>

                            <div class="categoria-card" data-categoria="2">
                                <div class="categoria-icon">🏪</div>
                                <div class="categoria-title">Marketplace</div>
                                <div class="categoria-description">Plataforma para múltiples vendedores y compradores.</div>
                            </div>

                            <div class="categoria-card" data-categoria="3">
                                <div class="categoria-icon">📰</div>
                                <div class="categoria-title">Blog y Noticias</div>
                                <div class="categoria-description">Perfecto para compartir contenido, artículos y noticias.
                                </div>
                            </div>

                            <div class="categoria-card" data-categoria="4">
                                <div class="categoria-icon">🍽️</div>
                                <div class="categoria-title">Recetas y Eventos</div>
                                <div class="categoria-description">Ideal para restaurantes, chefs o planificadores de
                                    eventos.</div>
                            </div>

                            <div class="categoria-card" data-categoria="5">
                                <div class="categoria-icon">🏢</div>
                                <div class="categoria-title">Páginas Corporativas</div>
                                <div class="categoria-description">Sitios web profesionales para empresas y negocios.</div>
                            </div>

                            <div class="categoria-card" data-categoria="6">
                                <div class="categoria-icon">💼</div>
                                <div class="categoria-title">Consultoría y Coaching</div>
                                <div class="categoria-description">Para profesionales que ofrecen servicios de asesoría.
                                </div>
                            </div>

                            <div class="categoria-card" data-categoria="7">
                                <div class="categoria-icon">📸</div>
                                <div class="categoria-title">Deportes y Fotografía</div>
                                <div class="categoria-description">Perfecto para fotógrafos, deportistas o clubes
                                    deportivos.</div>
                            </div>

                            <div class="categoria-card" data-categoria="8">
                                <div class="categoria-icon">🎮</div>
                                <div class="categoria-title">Música y Juegos</div>
                                <div class="categoria-description">Para artistas, músicos o desarrolladores de juegos.</div>
                            </div>

                            <div class="categoria-card" data-categoria="9">
                                <div class="categoria-icon">📺</div>
                                <div class="categoria-title">Streaming y Reservas</div>
                                <div class="categoria-description">Plataformas de contenido en streaming o sistemas de
                                    reservas.</div>
                            </div>

                            <div class="categoria-card" data-categoria="10">
                                <div class="categoria-icon">💬</div>
                                <div class="categoria-title">Foro Online</div>
                                <div class="categoria-description">Comunidades de discusión y foros temáticos.</div>
                            </div>
                        </div>

                        <input type="hidden" id="categoria_id" name="categoria_id" value="">

                        <div class="navigation-buttons">
                            <div></div> <!-- Espacio vacío para alinear -->
                            <button type="button" class="btn btn-primary" id="nextStep" disabled>Siguiente</button>
                        </div>
                    </div>

                    <div class="step" id="step2">
                        <h2 class="step-title">Cuéntanos más sobre tu proyecto</h2>
                        <p class="step-description">Proporciona detalles para que podamos crear la página web perfecta para
                            ti</p>

                        <div class="form-group">
                            <label for="nombre_proyecto">Nombre de tu proyecto:</label>
                            <input type="text" id="nombre_proyecto" name="nombre_proyecto" required>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Describe tu proyecto y lo que necesitas:</label>
                            <textarea id="descripcion" name="descripcion" required
                                placeholder="Cuéntanos sobre tu negocio, qué funcionalidades necesitas, estilo visual, referencias, etc."></textarea>
                        </div>

                        <div class="navigation-buttons">
                            <button type="button" class="btn btn-ghost" id="prevStep">Anterior</button>
                            <button type="submit" class="btn btn-primary">Enviar solicitud</button>
                        </div>
                    </div>
                </form>

                <div class="success-message" id="successMessage">
                    <h3>¡Solicitud enviada con éxito!</h3>
                    <p>Hemos recibido tu solicitud y nos pondremos en contacto contigo pronto para comenzar a trabajar en tu
                        proyecto.</p>
                    <button class="btn btn-primary" onclick="window.location.href='<?php echo APP_URL; ?>'"
                        style="margin-top: 20px;">Volver al inicio</button>
                </div>

                <div class="error-message" id="errorMessage">
                    <h3>Ha ocurrido un error</h3>
                    <p id="errorText">No se ha podido procesar tu solicitud. Por favor, inténtalo de nuevo más tarde.</p>
                    <button class="btn btn-primary" onclick="location.reload()" style="margin-top: 20px;">Intentar de
                        nuevo</button>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/partials/footer.php'; // Incluir el footer ?>

    <!-- Scripts -->
    <script src="<?php echo APP_URL; ?>/public/js/particles.js"></script>
    <script src="<?php echo APP_URL; ?>/public/js/menu.js"></script>
    <script src="<?php echo APP_URL; ?>/public/js/user-menu.js"></script>
    <script>
        $(document).ready(function () {
            // Selección de categoría
            $('.categoria-card').on('click', function () {
                $('.categoria-card').removeClass('selected');
                $(this).addClass('selected');

                const categoriaId = $(this).data('categoria');
                $('#categoria_id').val(categoriaId);

                // Habilitar botón siguiente
                $('#nextStep').prop('disabled', false);
            });

            // Navegación entre pasos
            $('#nextStep').on('click', function () {
                $('#step1').removeClass('active');
                $('#step2').addClass('active');
            });

            $('#prevStep').on('click', function () {
                $('#step2').removeClass('active');
                $('#step1').addClass('active');
            });

            // Envío del formulario
            $('#personalizacionForm').on('submit', function (e) {
                e.preventDefault();

                const formData = new FormData();
                formData.append('categoria_id', $('#categoria_id').val());
                formData.append('nombre_proyecto', $('#nombre_proyecto').val());
                formData.append('descripcion', $('#descripcion').val());

                $.ajax({
                    url: '<?php echo APP_URL; ?>/procesar_personalizacion.php', // Usar APP_URL aquí
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $('#personalizacionForm').hide();
                            $('#successMessage').show();
                        } else {
                            $('#errorText').text(response.message);
                            $('#errorMessage').show();
                        }
                    },
                    error: function () {
                        $('#errorText').text('Error al conectar con el servidor');
                        $('#errorMessage').show();
                    }
                });
            });
        });
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
    <!-- HTML del botón (añadir justo antes de cerrar el </body>) -->
    <button id="scrollToTopBtn" aria-label="Volver arriba" title="Volver arriba">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>
</body>

</html> 