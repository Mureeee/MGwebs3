<?php
extract($data);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MGwebs - Productos</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/styles.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/css/productos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos específicos para la página de productos */
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

        .products-section {
            max-width: 1400px;
            margin: 8rem auto 4rem;
            padding: 0 2rem;
            display: flex;
            gap: 2rem;
        }
        
        /* Estilo para el panel de filtros */
        .filters-panel {
            width: 300px;
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 100px;
            height: fit-content;
            margin-left: 0;
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
            width: 100%;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            width: 100%;
        }
        
        /* Responsive para tablets y móviles */
        @media (max-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .product-card {
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background-color: #333;
        }
        
        .product-info {
            padding: 1.5rem;
            flex-grow: 1; /* Para que la info ocupe el espacio restante */
            display: flex;
            flex-direction: column;
        }
        
        .product-info h3 {
            margin-top: 0;
            color: white;
            font-size: 1.4rem;
            margin-bottom: 0.5rem; /* Espacio debajo del título */
        }
        
        .product-category {
            color: #6a11cb;
            font-size: 0.9rem;
            margin-bottom: 1rem; /* Espacio debajo de la categoría */
        }
        
        .product-description {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1.5rem;
            line-height: 1.5;
            flex-grow: 1; /* Para que la descripción empuje el precio y botón hacia abajo */
        }
        
        .product-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: white;
            margin-bottom: 1.5rem;
            margin-top: auto; /* Empuja el precio y botón hacia abajo */
        }
        
        .product-card .btn-primary {
            display: block; /* Hacer el botón un bloque para ocupar ancho */
            width: 100%;
            text-align: center; /* Centrar texto del botón */
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            padding: 0.75rem 1.5rem; /* Ajustar padding */
            color: white;
            font-weight: 500;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none; /* Quitar subrayado */
            font-size: 1rem; /* Ajustar tamaño de fuente */
        }
        
        .product-card .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(106, 17, 203, 0.2);
        }
        
        .no-products {
            text-align: center;
            padding: 2rem;
            color: white;
            font-size: 1.2rem;
            grid-column: 1 / -1; /* Centrar en el grid */
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .products-section {
                flex-direction: column;
                align-items: center; /* Centrar contenido en columna */
            }
            
            .filters-panel {
                width: 90%; /* Ajustar ancho del panel de filtros */
                max-width: 400px; /* Limitar ancho máximo */
                margin: 0 auto 2rem; /* Centrar y añadir margen inferior */
                position: static;
                top: auto;
            }
            
            .products-container {
                width: 100%; /* Ajustar ancho del contenedor de productos */
                padding: 0 2rem; /* Añadir padding lateral */
            }
        }
        
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1.5rem; /* Reducir gap */
            }
        }
        
        @media (max-width: 576px) {
            .products-section {
                 padding: 0 1rem; /* Reducir padding en pantallas muy pequeñas */
            }
            .products-grid {
                grid-template-columns: 1fr;
                gap: 1rem; /* Reducir gap */
            }
             .filters-panel {
                width: 95%;
                margin: 0 auto 1.5rem;
                padding: 1rem;
            }
        }

        /* Estilos para el botón de volver arriba */
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

            <!-- Contenido de Productos -->
            <div class="products-section">

                <!-- Panel de Filtros -->
                <div class="filters-panel">
                    <h2 class="filters-title">Filtros</h2>
                    <form action="<?php echo APP_URL; ?>/productos" method="GET" id="filtrosForm">
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
                             <?php if (isset($precioMin, $precioMax)): ?>
                                <input type="range" id="precio_slider" class="price-range" min="<?php echo $precioMin; ?>" max="<?php echo $precioMax; ?>" step="1" value="<?php echo $filtros['precio_max'] ?? $precioMax; ?>">
                                <div style="display: flex; justify-content: space-between; color: white; font-size: 0.8rem;">
                                    <span><?php echo number_format($precioMin, 0); ?>€</span>
                                    <span><?php echo number_format($precioMax, 0); ?>€</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="filter-button">Aplicar Filtros</button>
                        
                        <div class="reset-filters">
                            <a href="<?php echo APP_URL; ?>/productos">Limpiar filtros</a>
                        </div>
                    </form>
                </div>
                
                <!-- Contenedor de Productos -->
                <div class="products-grid">
                        <?php if (empty($productos)): ?>
                            <div class="no-products">
                                <p>No se encontraron productos que coincidan con los filtros seleccionados.</p>
                            </div>
                        <?php else: ?>
                    <?php foreach ($productos as $prod): ?>
                        <div class="product-card">
                            <?php
                                // Obtener la ruta de la imagen
                                $image_src = '';
                                $debug_info = '';
                                
                                if (!empty($prod['imagenes'])) {
                                    $debug_info .= "Valor original: " . $prod['imagenes'] . "<br>";
                                    
                                    // Intentar decodificar JSON si es necesario
                                    $decoded = json_decode($prod['imagenes'], true);
                                    if (json_last_error() === JSON_ERROR_NONE && !empty($decoded)) {
                                        $debug_info .= "Es JSON válido<br>";
                                        // Si es un array, tomar el primer elemento
                                        $image_src = is_array($decoded) ? reset($decoded) : $decoded;
                                    } else {
                                        $debug_info .= "No es JSON válido, usando valor directo<br>";
                                        $image_src = $prod['imagenes'];
                                    }
                                    
                                    // Limpiar y ajustar la ruta
                                    $image_src = trim($image_src);
                                    $base_name = basename($image_src);
                                    
                                    // Directorio de imágenes
                                    $images_dir = __DIR__ . '/../public/imagenes/';
                                    $debug_info .= "Buscando en directorio: " . $images_dir . "<br>";
                                    
                                    // Buscar el archivo real (ignorando acentos)
                                    $found_file = false;
                                    if ($handle = opendir($images_dir)) {
                                        while (($file = readdir($handle)) !== false) {
                                            // Normalizar ambos nombres para comparación
                                            $normalized_file = strtolower(str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], $file));
                                            $normalized_base = strtolower(str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], $base_name));
                                            
                                            if ($normalized_file === $normalized_base) {
                                                $image_src = 'public/imagenes/' . $file;
                                                $found_file = true;
                                                break;
                                            }
                                        }
                                        closedir($handle);
                                    }
                                    
                                    $debug_info .= "Archivo encontrado: " . ($found_file ? 'Sí' : 'No') . "<br>";
                                    $debug_info .= "Ruta final: " . $image_src . "<br>";
                                }
                            ?>
                            <div class="product-image-container" data-product-name="<?php echo htmlspecialchars($prod['nombre_producto']); ?>">
                                <?php if (!empty($image_src)): ?>
                                    <?php
                                        // Construir la ruta completa de la imagen
                                        $full_image_path = __DIR__ . '/../' . ltrim($image_src, '/');
                                        $image_exists = file_exists($full_image_path);
                                        $debug_info .= "Ruta completa: " . $full_image_path . "<br>";
                                        $debug_info .= "Archivo existe: " . ($image_exists ? 'Sí' : 'No') . "<br>";
                                    ?>
                                    <?php if ($image_exists): ?>
                                        <img src="<?php echo APP_URL . '/' . $image_src; ?>" 
                                             alt="<?php echo htmlspecialchars($prod['nombre_producto']); ?>"
                                             class="product-image"
                                             onerror="this.onerror=null; this.classList.add('error'); this.parentElement.classList.add('error');">
                                    <?php else: ?>
                                        <div class="product-image-placeholder">
                                            <span>Imagen no disponible</span>
                                            <div class="debug-info" style="font-size: 10px; color: #999; margin-top: 10px;">
                                                <?php echo $debug_info; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="product-image-placeholder">
                                        <span>Imagen no disponible</span>
                                        <div class="debug-info" style="font-size: 10px; color: #999; margin-top: 10px;">
                                            <?php echo $debug_info; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($prod['nombre_producto']); ?></h3>
                                <p class="product-category"><?php echo htmlspecialchars($prod['nombre_categoria']); ?></p>
                                <p class="product-description"><?php echo htmlspecialchars($prod['descripcion']); ?></p>
                                <p class="product-price">€<?php echo number_format($prod['precio'], 2); ?></p>
                                <a href="<?php echo APP_URL; ?>/detalle-producto/<?php echo $prod['id_producto']; ?>" class="btn btn-primary">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>

    <!-- Scripts -->
    <script src="<?php echo APP_URL; ?>/public/js/menu.js"></script>
    <script src="<?php echo APP_URL; ?>/public/js/particles.js"></script>
    <script src="<?php echo APP_URL; ?>/public/js/price-slider.js"></script>
    <script src="<?php echo APP_URL; ?>/public/js/scroll-to-top.js"></script>
    <script src="<?php echo APP_URL; ?>/public/js/user-menu.js"></script>
            // Función para verificar la posición de scroll y mostrar/ocultar el botón
            function checkScrollPosition() {
                if (window.scrollY > 300) { // Usar un umbral similar al de caracteristicas
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

    <!-- Botón para volver arriba -->
    <button id="scrollToTopBtn" aria-label="Volver arriba" title="Volver arriba">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>
</body>
</html> 