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
    <title>MGwebs - Crear tu Página Web</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <nav class="navbar slide-down">
            <a href="index.php" class="logo">
                <svg class="bot-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2 2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" />
                    <path d="M12 8v8" />
                    <path d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5z" />
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
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="9" cy="21" r="1" />
                            <circle cx="20" cy="21" r="1" />
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                        </svg>
                        <?php if ($itemsCarrito > 0): ?>
                            <span class="cart-count"><?php echo $itemsCarrito; ?></span>
                        <?php endif; ?>
                    </a>
                <?php else: ?>
                    <button class="btn btn-ghost" onclick="window.location.href='iniciar_sesion.html'">Iniciar
                        Sesión</button>
                    <button class="btn btn-ghost" onclick="window.location.href='registrarse.html'">Registrate</button>
                <?php endif; ?>
                <button class="btn btn-primary"
                    onclick="window.location.href='crearpaginaperso.php'">Comenzar</button>
            </div>

            <button class="menu-button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16m-16 6h16" />
                </svg>
            </button>
        </nav>

        <div class="personalizacion-container">
            <?php if (!isset($_SESSION['usuario_id'])): ?>
                <br>
                <br>
                <br>
                <div class="step-title">Inicia sesión para continuar</div>
                <div class="step-description">Necesitas iniciar sesión o registrarte para poder crear tu página web
                    personalizada.</div>
                <div style="text-align: center; margin-top: 30px;">
                    <button class="btn btn-primary" onclick="window.location.href='iniciar_sesion.html'">Iniciar
                        Sesión</button>
                        <br> <br>
                    <button class="btn btn-outline" onclick="window.location.href='registrarse.html'">Registrarse</button>
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
                    <button class="btn btn-primary" onclick="window.location.href='index.php'"
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
            <p> 2025 MGwebs. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="js/particles.js"></script>
    <script src="js/menu.js"></script>
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
                    url: 'procesar_personalizacion.php',
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