<?php
extract($data);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MGwebs - <?php echo htmlspecialchars($producto['nombre_producto']); ?></title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/styles.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/css/productos.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/css/reviews.css">
    <style>
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .product-sections {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .product-detail {
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            align-items: center;
        }

        .product-detail-image {
            width: 100%;
            height: 300px;
            border-radius: 10px;
            overflow: hidden;
            background: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-detail-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .product-detail-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .product-detail-title {
            font-size: 1.5rem;
            color: #fff;
            margin: 0;
            margin-bottom: 0.5rem;
        }

        .product-detail-category {
            color: #8b5cf6;
            font-size: 1.1rem;
        }

        .product-detail-description {
            color: #ccc;
            font-size: 1rem;
            line-height: 1.5;
            margin: 0.5rem 0;
        }

        .product-detail-price {
            font-size: 1.5rem;
            color: #8b5cf6;
            font-weight: bold;
            margin: 0.75rem 0;
        }

        .product-detail-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn-add-cart {
            background: #8b5cf6;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-add-cart:hover {
            background: #7c3aed;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .product-detail {
                grid-template-columns: 1fr;
                margin: 1rem;
                padding: 1rem;
            }

            .product-detail-image {
                height: 300px;
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
            <?php include 'partials/navbar.php'; ?>

            <!-- Contenido del Producto -->
            <div class="container">
                <div class="product-sections">
                    <!-- Detalles del producto -->
                    <div class="product-detail">
                    <div class="product-detail-image">
                    <?php
                        $image_src = '';
                        if (!empty($producto['imagenes'])) {
                            $decoded = json_decode($producto['imagenes'], true);
                            if (json_last_error() === JSON_ERROR_NONE && !empty($decoded)) {
                                $image_src = is_array($decoded) ? reset($decoded) : $decoded;
                            } else {
                                $image_src = $producto['imagenes'];
                            }
                            
                            // Limpiar y ajustar la ruta
                            $image_src = trim($image_src);
                            if (strpos($image_src, 'public/imagenes/') !== 0) {
                                $image_src = 'public/imagenes/' . basename($image_src);
                            }
                            
                            $full_image_path = __DIR__ . '/../' . ltrim($image_src, '/');
                            $image_exists = file_exists($full_image_path);
                        }
                    ?>
                    <?php if (!empty($image_src) && $image_exists): ?>
                        <img src="<?php echo APP_URL . '/' . $image_src; ?>" 
                             alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                             onerror="this.onerror=null; this.classList.add('error'); this.parentElement.classList.add('error');">
                    <?php else: ?>
                        <div class="product-image-placeholder">
                            <span>Imagen no disponible</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="product-detail-info">
                    <h1 class="product-detail-title"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h1>
                    <p class="product-detail-category"><?php echo htmlspecialchars($producto['nombre_categoria']); ?></p>
                    <p class="product-detail-description"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    <p class="product-detail-price">€<?php echo number_format($producto['precio'], 2); ?></p>
                    <div class="product-detail-actions">
                        <button class="btn-add-cart" onclick="addToCart(<?php echo $producto['id_producto']; ?>)">
                            Añadir al carrito
                        </button>
                    </div>
                    </div>
                </div>

                <!-- Columna derecha: Reseñas -->
                <div class="reviews-section">
                    <h2 class="reviews-title">Reseñas</h2>
                    
                    <!-- Formulario para añadir reseña -->
                    <?php if ($isLoggedIn): ?>
                    <div class="add-review-form">
                        <h3 class="add-review-title">Añadir una reseña</h3>
                        <form action="<?php echo APP_URL; ?>/agregar-resena" method="POST" class="review-form">
                            <input type="hidden" name="producto_id" value="<?php echo $producto['id_producto']; ?>">
                            
                            <div class="rating-input">
                                <?php for($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>">
                                <label for="star<?php echo $i; ?>">★</label>
                                <?php endfor; ?>
                            </div>
                            
                            <textarea name="comentario" class="review-textarea" 
                                      placeholder="Escribe tu reseña aquí..."></textarea>
                            
                            <button type="submit" class="btn-submit-review">Publicar reseña</button>
                        </form>
                    </div>
                    <?php else: ?>
                    <div class="login-prompt">
                        <p>Debes <a href="<?php echo APP_URL; ?>/login">iniciar sesión</a> para dejar una reseña.</p>
                    </div>
                    <?php endif; ?>

                    <!-- Lista de reseñas -->
                    <div class="reviews-list">
                        <?php if (!empty($resenas)): ?>
                            <?php foreach ($resenas as $resena): ?>
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="review-author">
                                            <?php echo htmlspecialchars($resena['nombre_usuario']); ?>
                                        </div>
                                        <div class="review-rating">
                                            <?php echo str_repeat('★', $resena['rating']); ?>
                                        </div>
                                    </div>
                                    <div class="review-date">
                                        <?php echo date('d M, Y', strtotime($resena['fecha_creacion'])); ?>
                                    </div>
                                    <div class="review-content">
                                        <?php echo nl2br(htmlspecialchars($resena['comentario'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-reviews">
                                <p>No hay reseñas todavía. ¡Sé el primero en dejar una!</p>
                            </div>
                        <?php endif; ?>
                    </div>
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
    <script src="<?php echo APP_URL; ?>/public/js/reviews.js"></script>
    <script>
        function addToCart(productId) {
            // Implementar la lógica del carrito aquí
            alert('Funcionalidad de carrito en desarrollo');
        }
    </script>
</body>
</html>
