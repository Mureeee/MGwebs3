<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MGwebs - Productos</title>
    <link rel="stylesheet" href="../public/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Añadir más espacio entre el navbar y la sección de productos */
        .products-section {
            margin-top: 6rem; /* Aumentar el margen superior */
            padding-top: 2rem; /* Añadir padding superior adicional */
            display: flex;
            gap: 2rem;
        }
        
        /* Asegurar que el contenido no se solape con el navbar */
        .content-wrapper {
            padding-top: 1rem;
        }
        
        /* Estilo para el panel de filtros */
        .filters-panel {
            width: 300px;
            background: rgba(30, 30, 30, 0.95);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 100px;
            height: fit-content;
            margin-left: 2rem;
        }
        
        .filters-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: white;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 0.5rem;
        }
        
        .filter-group {
            margin-bottom: 1.5rem;
        }
        
        .filter-label {
            display: block;
            margin-bottom: 0.5rem;
            color: white;
            font-weight: bold;
        }
        
        .filter-input {
            width: 100%;
            padding: 0.75rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .filter-select {
            width: 100%;
            padding: 0.75rem;
            border-radius: 8px;
            background: rgba(30, 30, 30, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            margin-bottom: 0.5rem;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
        }
        
        .price-range {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .price-inputs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .price-inputs input {
            width: 50%;
            padding: 0.5rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .filter-button {
            width: 100%;
            padding: 0.75rem;
            border-radius: 8px;
            background: linear-gradient(to right, #a78bfa, #ec4899);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .filter-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(167, 139, 250, 0.4);
        }
        
        .reset-filters {
            text-align: center;
            margin-top: 1rem;
        }
        
        .reset-filters a {
            color: #a78bfa;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .reset-filters a:hover {
            text-decoration: underline;
        }
        
        /* Estilo para las tarjetas de productos */
        .products-container {
            flex: 1;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            padding: 0 2rem;
        }
        
        .product-card {
            background: rgba(30, 30, 30, 0.95);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .product-info {
            padding: 1.5rem;
        }
        
        .product-info h3 {
            margin-top: 0;
            color: white;
            font-size: 1.4rem;
        }
        
        .product-category {
            color: #6a11cb;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .product-description {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        
        .product-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: white;
            margin-bottom: 1.5rem;
        }
        
        .no-products {
            text-align: center;
            padding: 2rem;
            color: white;
            font-size: 1.2rem;
            grid-column: 1 / -1;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .products-section {
                flex-direction: column;
            }
            
            .filters-panel {
                width: auto;
                margin: 0 2rem;
                position: static;
            }
        }
        
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            }
        }
        
        @media (max-width: 576px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
        }

        #scrollToTop {
            transition: opacity 0.3s;
        }

        #scrollToTop:hover {
            background-color: #2575fc; /* Cambiar color al pasar el ratón */
        }

        #scrollToTopBtn {
  position: fixed;
  bottom: 30px;
  right: 30px;
  width: 50px;
  height: 50px;
  background-color: #a78bfa; /* Color lila */
  color: white;
  border: none;
  border-radius: 50%;
  display: flex; /* Cambiado de 'none' a 'flex' para que sea visible por defecto */
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  cursor: pointer;
  transition: background-color 0.3s, transform 0.3s;
  z-index: 9999; /* Aumentado para asegurar que esté por encima de otros elementos */
  opacity: 0; /* Inicialmente transparente */
  pointer-events: none; /* No interactuable cuando está invisible */
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
            <nav class="navbar slide-down">
                <a href="../index.php" class="logo">
                    <svg class="bot-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2 2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
                        <path d="M12 8v8"/>
                        <path d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5z"/>
                    </svg>
                    <span>MGwebs</span>
                </a>

                <div class="nav-links">
                    <a href="<?php echo APP_URL; ?>/caracteristicas">Características</a>
                    <a href="<?php echo APP_URL; ?>/como-funciona">Cómo Funciona</a>
                    <a href="<?php echo APP_URL; ?>/productos">Productos</a>
                    <a href="<?php echo APP_URL; ?>/soporte">Soporte</a>
                    <a href="<?php echo APP_URL; ?>/contactanos">Contáctanos</a>
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
                        <button class="btn btn-ghost" style="padding: 15px 30px; font-size: 1.2rem;" onclick="window.location.href='iniciar_sesion.html'">Iniciar Sesión</button>
                        <button class="btn btn-ghost" onclick="window.location.href='registrarse.html'">Registrate</button>
                    <?php endif; ?>

                    <!-- Icono del carrito: SIEMPRE visible -->
                    <a href="../controllers/carrito.php" class="cart-icon">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"/>
                            <circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        <?php if (!empty($_SESSION['carrito'])): ?>
                            <span class="cart-count"><?php echo array_sum($_SESSION['carrito']); ?></span>
                        <?php endif; ?>
                    </a>
                    <button class="btn btn-primary" onclick="window.location.href='../controllers/crearpaginaperso.php'">Comenzar</button>
                </div>

                <button class="menu-button">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 6h16M4 12h16m-16 6h16"/>
                    </svg>
                </button>
            </nav>

            <!-- Contenido de Productos -->
            <div class="products-section">

                <!-- Panel de Filtros -->
                <div class="filters-panel">
                    <h2 class="filters-title">Filtros</h2>
                    <form action="" method="GET" id="filtrosForm">
                        <div class="filter-group">
                            <label for="nombre" class="filter-label">Buscar por nombre:</label>
                            <input type="text" id="nombre" name="nombre" class="filter-input" placeholder="Nombre del producto" value="<?php echo htmlspecialchars($filtros['nombre'] ?? ''); ?>">
                        </div>
                        
                        <div class="filter-group">
                            <label for="categoria" class="filter-label">Categoría:</label>
                            <select id="categoria" name="categoria" class="filter-select">
                                <option value="">Todas las categorías</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo $cat['id_categoria']; ?>" <?php echo (isset($filtros['categoria']) && $filtros['categoria'] == $cat['id_categoria']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label class="filter-label">Rango de precio:</label>
                            <div class="price-inputs">
                                <input type="number" id="precio_min" name="precio_min" placeholder="Min €" min="0" value="<?php echo htmlspecialchars($filtros['precio_min'] ?? ''); ?>">
                                <input type="number" id="precio_max" name="precio_max" placeholder="Max €" min="0" value="<?php echo htmlspecialchars($filtros['precio_max'] ?? ''); ?>">
                            </div>
                            <input type="range" id="precio_slider" class="price-range" min="<?php echo $precioMin; ?>" max="<?php echo $precioMax; ?>" step="10" value="<?php echo $filtros['precio_max'] ?? $precioMax; ?>">
                            <div style="display: flex; justify-content: space-between; color: white; font-size: 0.8rem;">
                                <span><?php echo number_format($precioMin, 0); ?>€</span>
                                <span><?php echo number_format($precioMax, 0); ?>€</span>
                            </div>
                        </div>
                        
                        <button type="submit" class="filter-button">Aplicar Filtros</button>
                        
                        <div class="reset-filters">
                            <a href="productos.php">Limpiar filtros</a>
                        </div>
                    </form>
                </div>
                
                <!-- Contenedor de Productos -->
                <div class="products-container">
                <div class="products-grid">
                        <?php if (empty($productos)): ?>
                            <div class="no-products">
                                <p>No se encontraron productos que coincidan con los filtros seleccionados.</p>
                            </div>
                        <?php else: ?>
                    <?php foreach ($productos as $prod): ?>
                        <div class="product-card">
                            <img src="../<?php echo htmlspecialchars($prod['imagenes']); ?>" 
                                 alt="<?php echo htmlspecialchars($prod['nombre_producto']); ?>"
                                 class="product-image">
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($prod['nombre_producto']); ?></h3>
                                <p class="product-category"><?php echo htmlspecialchars($prod['nombre_categoria']); ?></p>
                                <p class="product-description"><?php echo htmlspecialchars($prod['descripcion']); ?></p>
                                <p class="product-price">€<?php echo number_format($prod['precio'], 2); ?></p>
                                <a href="../controllers/detalle_producto.php?id=<?php echo $prod['id_producto']; ?>" class="btn btn-primary">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                        <?php endif; ?>
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
                    <li><a href="../index.php">Inicio</a></li>
                    <li><a href="../controllers/segunda_mano.php">Segunda Mano</a></li>
                    <li><a href="soporte.html">Soporte</a></li>
                    <li><a href="../controllers/contactanos.php">Contacto</a></li>
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../public/js/menu.js"></script>
    
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
        
        // Script para el slider de precio
        document.addEventListener('DOMContentLoaded', function() {
            const precioSlider = document.getElementById('precio_slider');
            const precioMax = document.getElementById('precio_max');
            
            if (precioSlider && precioMax) {
                precioSlider.addEventListener('input', function() {
                    precioMax.value = this.value;
                });
                
                precioMax.addEventListener('input', function() {
                    precioSlider.value = this.value;
                });
            }
        });

        // Mostrar el botón cuando se desplaza hacia abajo
        window.onscroll = function() {
            const button = document.getElementById("scrollToTop");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                button.style.display = "block";
            } else {
                button.style.display = "none";
            }
        };

        // Función para hacer scroll hacia arriba
        document.getElementById("scrollToTop").onclick = function() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        };

        // Control del botón para volver arriba
        document.addEventListener('DOMContentLoaded', function() {
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
            scrollBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>

    <!-- Botón para volver arriba -->
    <button id="scrollToTopBtn" aria-label="Volver arriba" title="Volver arriba">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const scrollBtn = document.getElementById('scrollToTopBtn');
        function checkScrollPosition() {
            if (window.scrollY > 200) {
                scrollBtn.classList.add('visible');
            } else {
                scrollBtn.classList.remove('visible');
            }
        }
        checkScrollPosition();
        window.addEventListener('scroll', checkScrollPosition);
        scrollBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
    </script>
</body>
</html> 