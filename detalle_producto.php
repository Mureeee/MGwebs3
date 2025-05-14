<?php
session_start();
require_once 'config/database.php';

$isLoggedIn = isset($_SESSION['usuario_id']);
$primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';

// Obtener el ID del producto de la URL
$id_producto = isset($_GET['id']) ? (int) $_GET['id'] : 0;

try {
    $database = new Database();
    $conn = $database->getConnection();

    // Obtener detalles del producto
    $query = "SELECT p.*, c.nombre_categoria 
             FROM producto p 
             LEFT JOIN categoria c ON p.categoria_id = c.id_categoria 
             WHERE p.id_producto = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id_producto]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        header('Location: productos.php');
        exit;
    }
    
    // Obtener reseñas del producto
    $query_resenas = "SELECT r.*, u.nombre as nombre_usuario 
                     FROM resenas r 
                     LEFT JOIN usuario u ON r.usuario_id = u.id_usuario 
                     WHERE r.producto_id = ? 
                     ORDER BY r.fecha_creacion DESC";
    $stmt_resenas = $conn->prepare($query_resenas);
    $stmt_resenas->execute([$id_producto]);
    $resenas = $stmt_resenas->fetchAll(PDO::FETCH_ASSOC);
    
    // Calcular promedio de valoraciones
    $query_promedio = "SELECT AVG(valoracion) as promedio FROM resenas WHERE producto_id = ?";
    $stmt_promedio = $conn->prepare($query_promedio);
    $stmt_promedio->execute([$id_producto]);
    $promedio = $stmt_promedio->fetch(PDO::FETCH_ASSOC);
    $valoracion_promedio = $promedio['promedio'] ? round($promedio['promedio'], 1) : 0;

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Función para agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isLoggedIn) {
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    if (isset($_POST['agregar_carrito'])) {
        $cantidad = isset($_POST['cantidad']) ? (int) $_POST['cantidad'] : 1;
        if (isset($_SESSION['carrito'][$id_producto])) {
            $_SESSION['carrito'][$id_producto] += $cantidad;
        } else {
            $_SESSION['carrito'][$id_producto] = $cantidad;
        }
        header('Location: carrito.php');
        exit;
    }
    
    // Procesar nueva reseña
    if (isset($_POST['enviar_resena']) && isset($_POST['valoracion']) && isset($_POST['comentario'])) {
        $valoracion = (int)$_POST['valoracion'];
        $comentario = trim($_POST['comentario']);
        
        if ($valoracion >= 1 && $valoracion <= 5 && !empty($comentario)) {
            try {
                $query_insert = "INSERT INTO resenas (producto_id, usuario_id, valoracion, comentario, fecha_creacion) 
                                VALUES (?, ?, ?, ?, NOW())";
                $stmt_insert = $conn->prepare($query_insert);
                $stmt_insert->execute([$id_producto, $_SESSION['usuario_id'], $valoracion, $comentario]);
                
                // Recargar la página para mostrar la nueva reseña
                header("Location: detalle_producto.php?id=$id_producto&resena_enviada=1");
                exit;
            } catch (PDOException $e) {
                $error_resena = "Error al guardar la reseña: " . $e->getMessage();
            }
        } else {
            $error_resena = "Por favor, proporciona una valoración válida y un comentario.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['nombre_producto']); ?> - MGwebs</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-y: auto !important;
            overflow-x: hidden;
        }

        body {
            position: relative;
        }

        .particles-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none !important;
            z-index: 0;
        }

        .producto-detalle {
            padding: 6rem 2rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            color: white;
            position: relative;
            z-index: 1;
        }
        
        .producto-contenido {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .producto-imagen {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .producto-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .producto-categoria {
            display: inline-block;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .producto-precio {
            font-size: 2rem;
            font-weight: bold;
            color: #4CAF50;
            margin: 1rem 0;
        }

        .cantidad-control {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1rem 0;
        }

        .cantidad-control button {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .cantidad-control button:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .cantidad-control span {
            font-size: 1.2rem;
            min-width: 40px;
            text-align: center;
        }

        .btn-agregar {
            background: linear-gradient(90deg, #4CAF50, #45a049);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: transform 0.3s;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-agregar:hover {
            transform: translateY(-2px);
        }

        .descripcion {
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Estilos para la sección de reseñas */
        .resenas-seccion {
            margin-top: 3rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .resenas-titulo {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .resenas-promedio {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .resenas-promedio-valor {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .estrellas {
            display: flex;
            gap: 0.25rem;
        }

        .estrella {
            width: 20px;
            height: 20px;
            fill: none;
            stroke: currentColor;
        }

        .estrella.llena {
            fill: #FFD700;
            stroke: #FFD700;
        }

        .estrella.media {
            fill: url(#grad);
            stroke: #FFD700;
        }

        .resenas-lista {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 2rem;
            max-height: 500px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .resena-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #6a11cb;
        }

        .resena-cabecera {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .resena-usuario {
            font-weight: bold;
        }

        .resena-fecha {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .resena-valoracion {
            margin-bottom: 0.5rem;
        }

        .resena-comentario {
            line-height: 1.5;
        }

        .form-resena {
            background: rgba(255, 255, 255, 0.05);
            padding: 1.5rem;
            border-radius: 8px;
        }

        .form-resena-titulo {
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .form-grupo {
            margin-bottom: 1rem;
        }

        .form-grupo label {
            display: block;
            margin-bottom: 0.5rem;
        }

        /* Nuevo sistema de valoración mejorado */
        .valoracion-selector {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            margin-bottom: 1.5rem;
        }

        .valoracion-selector input {
            display: none;
        }

        .valoracion-selector label {
            cursor: pointer;
            width: 35px;
            height: 35px;
            margin: 0 2px;
            position: relative;
            transition: all 0.2s ease;
        }

        .valoracion-selector label svg {
            width: 100%;
            height: 100%;
            fill: transparent;
            stroke: #ccc;
            stroke-width: 1px;
            transition: all 0.2s ease;
        }

        .valoracion-selector input:checked ~ label svg,
        .valoracion-selector label:hover svg,
        .valoracion-selector label:hover ~ label svg {
            fill: #FFD700;
            stroke: #FFD700;
            transform: scale(1.1);
        }

        .valoracion-selector label:hover {
            transform: rotate(-15deg) scale(1.3);
        }

        .form-grupo textarea {
            width: 100%;
            padding: 0.75rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            resize: vertical;
            min-height: 100px;
        }

        .form-grupo textarea:focus {
            outline: none;
            border-color: #6a11cb;
        }

        .btn-enviar-resena {
            background: linear-gradient(90deg, #6a11cb, #a78bfa);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: transform 0.3s;
        }

        .btn-enviar-resena:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(106, 17, 203, 0.2);
        }

        .mensaje-resena {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .mensaje-resena.exito {
            background: rgba(76, 175, 80, 0.2);
            border: 1px solid rgba(76, 175, 80, 0.5);
        }

        .mensaje-resena.error {
            background: rgba(244, 67, 54, 0.2);
            border: 1px solid rgba(244, 67, 54, 0.5);
        }

        .sin-resenas {
            text-align: center;
            padding: 2rem;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Mejora para el scroll */
        .particles-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        @media (max-width: 768px) {
            .producto-contenido {
                grid-template-columns: 1fr;
            }
            
            .valoracion-selector label {
                width: 30px;
                height: 30px;
            }
        }

        .producto-imagen-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%; /* Asegura que ocupe toda la altura de la celda */
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

        /* Estilo para el slider de precio */
        input[type="range"] {
            -webkit-appearance: none; /* Eliminar el estilo predeterminado */
            width: 100%;
            height: 8px; /* Altura del slider */
            background: #a78bfa; /* Color de fondo del slider */
            border-radius: 5px; /* Bordes redondeados */
            outline: none; /* Sin contorno al enfocar */
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none; /* Eliminar el estilo predeterminado */
            appearance: none; /* Eliminar el estilo predeterminado */
            width: 20px; /* Ancho del thumb */
            height: 20px; /* Alto del thumb */
            background: #6a11cb; /* Color del thumb */
            border-radius: 50%; /* Bordes redondeados */
            cursor: pointer; /* Cambiar el cursor al pasar sobre el thumb */
        }

        input[type="range"]::-moz-range-thumb {
            width: 20px; /* Ancho del thumb */
            height: 20px; /* Alto del thumb */
            background: #6a11cb; /* Color del thumb */
            border-radius: 50%; /* Bordes redondeados */
            cursor: pointer; /* Cambiar el cursor al pasar sobre el thumb */
        }
    </style>
</head>

<body class="bg-black">
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
            <a href="soporte.php">Soporte</a>
            <a href="contactanos.php">Contáctanos</a>
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
                        <a href="cerrar_sesion.php" class="dropdown-item">Cerrar Sesión</a>
                    </div>
                </div>

                <a href="carrito.php" class="cart-icon">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    <?php if (!empty($_SESSION['carrito'])): ?>
                        <span class="cart-count"><?php echo array_sum($_SESSION['carrito']); ?></span>
                    <?php endif; ?>
                </a>
            <?php else: ?>
                <button class="btn btn-ghost" onclick="window.location.href='iniciar_sesion.html'">Iniciar Sesión</button>
                <button class="btn btn-ghost" onclick="window.location.href='registrarse.html'">Registrate</button>
            <?php endif; ?>

            <button class="btn btn-primary" onclick="window.location.href='crearpaginaperso.php'">Comenzar</button>
        </div>
    </nav>

    <div class="producto-detalle">
        <div class="producto-contenido">
            <div class="producto-imagen-container">
                <img src="<?php echo htmlspecialchars($producto['imagenes']); ?>"
                    alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" class="producto-imagen">
            </div>

            <div class="producto-info">
                <span class="producto-categoria">
                    <?php echo htmlspecialchars($producto['nombre_categoria']); ?>
                </span>

                <h1><?php echo htmlspecialchars($producto['nombre_producto']); ?></h1>

                <div class="producto-precio">
                    €<?php echo number_format($producto['precio'], 2); ?>
                </div>

                <p class="descripcion">
                    <?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?>
                </p>

                <?php if ($isLoggedIn): ?>
                    <form method="POST" class="form-agregar">
                        <div class="cantidad-control">
                            <button type="button" onclick="actualizarCantidad(-1)">-</button>
                            <span id="cantidad">1</span>
                            <button type="button" onclick="actualizarCantidad(1)">+</button>
                            <input type="hidden" name="cantidad" id="cantidad-input" value="1">
                        </div>
                        <button type="submit" name="agregar_carrito" class="btn-agregar">
                            Agregar al Carrito
                        </button>
                    </form>
                <?php else: ?>
                    <button class="btn-agregar" onclick="window.location.href='iniciar_sesion.html'">
                        Iniciar Sesión para Comprar
                    </button>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Sección de Reseñas -->
        <div class="resenas-seccion">
            <h2 class="resenas-titulo">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                Reseñas de Clientes
            </h2>
            
            <!-- Promedio de valoraciones -->
            <div class="resenas-promedio">
                <span class="resenas-promedio-valor"><?php echo number_format($valoracion_promedio, 1); ?></span>
                <div class="estrellas">
                    <?php
                    $valoracion_entera = floor($valoracion_promedio);
                    $valoracion_decimal = $valoracion_promedio - $valoracion_entera;
                    
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $valoracion_entera) {
                            // Estrella completa
                            echo '<svg class="estrella llena" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
                        } elseif ($i == $valoracion_entera + 1 && $valoracion_decimal >= 0.3 && $valoracion_decimal < 0.8) {
                            // Media estrella
                            echo '<svg class="estrella media" viewBox="0 0 24 24">
                                <defs>
                                    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="50%" style="stop-color:#FFD700;stop-opacity:1" />
                                        <stop offset="50%" style="stop-color:transparent;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>';
                        } elseif ($i == $valoracion_entera + 1 && $valoracion_decimal >= 0.8) {
                            // Estrella completa (si el decimal es >= 0.8)
                            echo '<svg class="estrella llena" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
                        } else {
                            // Estrella vacía
                            echo '<svg class="estrella" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
                        }
                    }
                    ?>
                </div>
                <span>(<?php echo count($resenas); ?> reseñas)</span>
            </div>
            
            <!-- Mensaje de éxito o error -->
            <?php if (isset($_GET['resena_enviada'])): ?>
                <div class="mensaje-resena exito">
                    Tu reseña ha sido enviada correctamente. ¡Gracias por compartir tu opinión!
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_resena)): ?>
                <div class="mensaje-resena error">
                    <?php echo $error_resena; ?>
                </div>
            <?php endif; ?>
            
            <!-- Lista de reseñas -->
            <div class="resenas-lista">
                <?php if (empty($resenas)): ?>
                    <div class="sin-resenas">
                        <p>Aún no hay reseñas para este producto. ¡Sé el primero en opinar!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($resenas as $resena): ?>
                        <div class="resena-item">
                            <div class="resena-cabecera">
                                <span class="resena-usuario"><?php echo htmlspecialchars($resena['nombre_usuario']); ?></span>
                                <span class="resena-fecha"><?php echo date('d/m/Y', strtotime($resena['fecha_creacion'])); ?></span>
                            </div>
                            <div class="resena-valoracion">
                                <div class="estrellas">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $resena['valoracion']): ?>
                                            <svg class="estrella llena" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        <?php else: ?>
                                            <svg class="estrella" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p class="resena-comentario"><?php echo nl2br(htmlspecialchars($resena['comentario'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Formulario para añadir reseña con sistema mejorado de estrellas -->
            <?php if ($isLoggedIn): ?>
                <form method="POST" class="form-resena">
                    <h3 class="form-resena-titulo">Deja tu opinión</h3>
                    
                    <div class="form-grupo">
                        <label>Tu valoración:</label>
                        <div class="valoracion-selector">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" name="valoracion" id="star<?php echo $i; ?>" value="<?php echo $i; ?>" <?php echo ($i == 5) ? 'checked' : ''; ?>>
                                <label for="star<?php echo $i; ?>" title="<?php echo $i; ?> estrellas">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="comentario">Tu comentario:</label>
                        <textarea name="comentario" id="comentario" required placeholder="Escribe tu opinión sobre este producto..."></textarea>
                    </div>
                    
                    <button type="submit" name="enviar_resena" class="btn-enviar-resena">Enviar Reseña</button>
                </form>
            <?php else: ?>
                <div class="form-resena">
                    <p>Debes <a href="iniciar_sesion.html" style="color: #4CAF50; text-decoration: underline;">iniciar sesión</a> para dejar una reseña.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Función para actualizar la cantidad
        function actualizarCantidad(cambio) {
            const cantidadSpan = document.getElementById('cantidad');
            const cantidadInput = document.getElementById('cantidad-input');
            let cantidad = parseInt(cantidadSpan.textContent);

            cantidad = Math.max(1, cantidad + cambio);
            cantidadSpan.textContent = cantidad;
            cantidadInput.value = cantidad;
        }

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

        // Asegurarse de que el canvas no interfiera con el scroll
        canvas.style.pointerEvents = 'none';

        // Prevenir que el canvas capture eventos de rueda
        document.addEventListener('wheel', function(event) {
            event.stopPropagation();
        }, { passive: true });

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



</html>