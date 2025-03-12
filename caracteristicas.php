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
    <title>Características - MGwebs</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos específicos para la página de características */
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

        .features-container {
            max-width: 1200px;
            margin: 8rem auto 4rem;
            padding: 0 2rem;
        }

        .features-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .features-header h1 {
            color: white;
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .features-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .feature-card {
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .feature-card h3 {
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1.5rem;
            line-height: 1.6;
            flex-grow: 1;
        }

        .feature-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-card ul li {
            margin-bottom: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: flex-start;
        }

        .feature-card ul li i {
            color: #2575fc;
            margin-right: 0.5rem;
            margin-top: 0.25rem;
        }

        .feature-card .btn-outline {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border: 1px solid #6a11cb;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            text-align: center;
            margin-top: auto;
        }

        .feature-card .btn-outline:hover {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border-color: transparent;
        }

        .showcase-section {
            margin-bottom: 6rem;
        }

        .showcase-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .showcase-header h2 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .showcase-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
        }

        .showcase-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .showcase-item {
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .showcase-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        }

        .showcase-image {
            width: 100%;
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .showcase-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.7));
        }

        .showcase-content {
            padding: 1.5rem;
        }

        .showcase-content h3 {
            color: white;
            font-size: 1.3rem;
            margin-bottom: 0.75rem;
        }

        .showcase-content p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .showcase-content .btn-text {
            color: #2575fc;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .showcase-content .btn-text i {
            margin-left: 0.5rem;
            transition: transform 0.3s ease;
        }

        .showcase-content .btn-text:hover i {
            transform: translateX(5px);
        }

        .comparison-section {
            margin-bottom: 6rem;
        }

        .comparison-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .comparison-header h2 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .comparison-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .comparison-table th,
        .comparison-table td {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .comparison-table th {
            background: rgba(20, 20, 20, 0.95);
            color: white;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .comparison-table td {
            color: rgba(255, 255, 255, 0.8);
        }

        .comparison-table tr:last-child td {
            border-bottom: none;
        }

        .comparison-table .feature-name {
            text-align: left;
            font-weight: 500;
            color: white;
        }

        .comparison-table .check {
            color: #2575fc;
            font-size: 1.2rem;
        }

        .comparison-table .cross {
            color: #ff4757;
            font-size: 1.2rem;
        }

        .comparison-table .highlight {
            background: linear-gradient(135deg, rgba(106, 17, 203, 0.1), rgba(37, 117, 252, 0.1));
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

        @media (max-width: 768px) {
            .features-header h1 {
                font-size: 2.5rem;
            }
            
            .cta-section h2 {
                font-size: 2rem;
            }
            
            .comparison-table {
                font-size: 0.9rem;
            }
            
            .comparison-table th,
            .comparison-table td {
                padding: 1rem 0.5rem;
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
                    <a href="caracteristicas.php" class="active">Características</a>
                    <a href="como_funciona.php">Cómo Funciona</a>
                    <a href="productos.php">Productos</a>
                    <a href="soporte.php">Soporte</a>
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

            <!-- Contenido de Características -->
            <div class="features-container">
                <div class="features-header">
                    <h1>Características de MGwebs</h1>
                    <p>Descubre por qué MGwebs es la plataforma ideal para crear tu presencia online. Ofrecemos herramientas potentes y fáciles de usar para que puedas construir el sitio web de tus sueños sin necesidad de conocimientos técnicos.</p>
                </div>

                <!-- Características Principales -->
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-paint-brush"></i>
                        </div>
                        <h3>Diseño Intuitivo</h3>
                        <p>Crea sitios web profesionales sin necesidad de conocimientos técnicos. Nuestra interfaz de arrastrar y soltar te permite diseñar páginas atractivas en minutos.</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Editor visual de arrastrar y soltar</li>
                            <li><i class="fas fa-check"></i> Plantillas profesionales personalizables</li>
                            <li><i class="fas fa-check"></i> Adaptable a todos los dispositivos</li>
                        </ul>
                        <a href="como_funciona.html" class="btn-outline">Saber más</a>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3>Rendimiento Optimizado</h3>
                        <p>Todos nuestros sitios web están optimizados para cargar rápidamente y ofrecer la mejor experiencia a tus visitantes, mejorando tu posicionamiento en buscadores.</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Hosting de alta velocidad</li>
                            <li><i class="fas fa-check"></i> Optimización automática de imágenes</li>
                            <li><i class="fas fa-check"></i> Certificado SSL gratuito</li>
                        </ul>
                        <a href="productos.php" class="btn-outline">Ver planes</a>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>100% Responsive</h3>
                        <p>Todos los sitios web creados con MGwebs se adaptan automáticamente a cualquier dispositivo, ofreciendo una experiencia perfecta en móviles, tablets y ordenadores.</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Diseño adaptable automático</li>
                            <li><i class="fas fa-check"></i> Vista previa en diferentes dispositivos</li>
                            <li><i class="fas fa-check"></i> Optimización para pantallas táctiles</li>
                        </ul>
                        <a href="#" class="btn-outline">Ver ejemplos</a>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>SEO Avanzado</h3>
                        <p>Mejora tu visibilidad en los motores de búsqueda con nuestras herramientas de SEO integradas, que te ayudarán a posicionar tu sitio web en los primeros resultados.</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Análisis de palabras clave</li>
                            <li><i class="fas fa-check"></i> Metadatos personalizables</li>
                            <li><i class="fas fa-check"></i> Estructura de URL optimizada</li>
                        </ul>
                        <a href="#" class="btn-outline">Aprender más</a>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3>E-Commerce Integrado</h3>
                        <p>Vende tus productos online con nuestra solución de comercio electrónico integrada. Gestiona inventario, procesa pagos y envía pedidos desde un solo lugar.</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Carrito de compras personalizable</li>
                            <li><i class="fas fa-check"></i> Múltiples métodos de pago</li>
                            <li><i class="fas fa-check"></i> Gestión de inventario</li>
                        </ul>
                        <a href="productos.php" class="btn-outline">Ver planes</a>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>Soporte Premium</h3>
                        <p>Nuestro equipo de soporte está disponible para ayudarte en cada paso del camino. Resolvemos tus dudas y problemas para que puedas centrarte en tu negocio.</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Soporte 24/7</li>
                            <li><i class="fas fa-check"></i> Tutoriales y guías detalladas</li>
                            <li><i class="fas fa-check"></i> Asistencia personalizada</li>
                        </ul>
                        <a href="soporte.php" class="btn-outline">Contactar soporte</a>
                    </div>
                </div>

                <!-- Sección de Showcase -->
                <div class="showcase-section">
                    <div class="showcase-header">
                        <h2>Plantillas Profesionales</h2>
                        <p>Elige entre cientos de plantillas diseñadas por profesionales para diferentes sectores y personalízalas según tus necesidades.</p>
                    </div>

                    <div class="showcase-grid">
                        <div class="showcase-item">
                            <div class="showcase-image" style="background-image: url('/placeholder.svg?height=400&width=600')"></div>
                            <div class="showcase-content">
                                <h3>Plantillas para Negocios</h3>
                                <p>Diseños profesionales para empresas de todos los tamaños, con secciones para servicios, equipo y testimonios.</p>
                                <a href="#" class="btn-text">Ver plantillas <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="showcase-item">
                            <div class="showcase-image" style="background-image: url('/placeholder.svg?height=400&width=600')"></div>
                            <div class="showcase-content">
                                <h3>Tiendas Online</h3>
                                <p>Plantillas optimizadas para e-commerce con catálogos de productos, carrito de compras y proceso de pago.</p>
                                <a href="#" class="btn-text">Ver plantillas <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="showcase-item">
                            <div class="showcase-image" style="background-image: url('/placeholder.svg?height=400&width=600')"></div>
                            <div class="showcase-content">
                                <h3>Portfolios Creativos</h3>
                                <p>Muestra tu trabajo de forma elegante con nuestras plantillas para fotógrafos, diseñadores y artistas.</p>
                                <a href="#" class="btn-text">Ver plantillas <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Comparación -->
                <div class="comparison-section">
                    <div class="comparison-header">
                        <h2>MGwebs vs. Competencia</h2>
                        <p>Descubre por qué MGwebs es la mejor opción para crear tu sitio web profesional.</p>
                    </div>

                    <table class="comparison-table">
                        <thead>
                            <tr>
                                <th>Características</th>
                                <th>MGwebs</th>
                                <th>Competencia A</th>
                                <th>Competencia B</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="feature-name">Editor Drag & Drop</td>
                                <td><i class="fas fa-check check"></i></td>
                                <td><i class="fas fa-check check"></i></td>
                                <td><i class="fas fa-check check"></i></td>
                            </tr>
                            <tr>
                                <td class="feature-name">Plantillas Profesionales</td>
                                <td><i class="fas fa-check check"></i></td>
                                <td><i class="fas fa-check check"></i></td>
                                <td><i class="fas fa-times cross"></i></td>
                            </tr>
                            <tr class="highlight">
                                <td class="feature-name">Dominio Personalizado</td>
                                <td><i class="fas fa-check check"></i></td>
                                <td><i class="fas fa-check check"></i></td>
                                <td><i class="fas fa-check check"></i></td>
                            </tr>
                            <tr>
                                <td class="feature-name">SSL Gratuito</td>
                                <td><i class="fas fa-check check"></i></td>
                                <td><i class="fas fa-times cross"></i></td>
                                <td><i class="fas fa-check check"></i></td>
                            </tr>
                            <tr class="highlight">
                                <td class="feature-name">Soporte 24/7</td>
                                <td><i class="fas fa-check check"></i></td>
                                <td><i class="fas fa-times cross"></i></td>
                                <td><i class="fas fa-times cross"></i></td>
                            </tr>
                            <tr>
                                <td class="feature-name">E-Commerce Integrado</td>
                                <td><i class="fas fa-check check"></i></td>
                                <td><i class="fas fa-check check"></i></td>
                                <td><i class="fas fa-times cross"></i></td>
                            </tr>
                            <tr class="highlight">
                                <td class="feature-name">Sin Publicidad</td>
                                <td><i class="fas fa-check check"></i></td>
                                <td><i class="fas fa-times cross"></i></td>
                                <td><i class="fas fa-times cross"></i></td>
                            </tr>
                            <tr>
                                <td class="feature-name">Herramientas SEO</td>
                                <td><i class="fas fa-check check"></i></td>
                                <td><i class="fas fa-check check"></i></td>
                                <td><i class="fas fa-times cross"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Sección de CTA -->
                <div class="cta-section">
                    <h2>¿Listo para crear tu sitio web?</h2>
                    <p>Comienza hoy mismo a construir tu presencia online con MGwebs. Elige el plan que mejor se adapte a tus necesidades y empieza a diseñar tu sitio web en minutos.</p>
                    <div class="cta-buttons">
                        <a href="crearpaginaperso.php" class="btn-primary">Comenzar Ahora</a>
                        <a href="productos.php" class="btn-secondary">Ver Planes y Precios</a>
                    </div>
                </div>
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
                    <li><span>📍 Calle Diagonal 123, Barcelona</span></li>
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
</body>
</html>