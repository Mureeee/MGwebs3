<?php
// Archivo de instalación para crear las tablas necesarias

// Establecer tiempo máximo de ejecución
set_time_limit(300);

// Incluir estilos para la página
echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalación de Tablas - MGwebs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .step {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .step h2 {
            margin-top: 0;
            color: #444;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .info {
            background-color: #cce5ff;
            color: #004085;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            background-color: #6d28d9;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #5b21b6;
        }
    </style>
</head>
<body>
    <h1>Instalación de Tablas para MGwebs</h1>';

// Función para mostrar mensajes
function showMessage($type, $title, $message) {
    echo "<div class='$type'>
            <h3>$title</h3>
            <p>$message</p>
          </div>";
}

// Verificar si existe el archivo de configuración
if (!file_exists('config/database.php')) {
    showMessage('error', 'Error', 'No se encontró el archivo de configuración de la base de datos.');
    exit;
}

require_once 'config/database.php';

try {
    // Conectar a la base de datos
    $database = new Database();
    $conn = $database->getConnection();
    
    if (!$conn) {
        showMessage('error', 'Error de Conexión', 'No se pudo conectar a la base de datos. Verifique la configuración.');
        exit;
    }
    
    // Paso 1: Verificar si existe la tabla usuario
    echo '<div class="step">
            <h2>Paso 1: Verificar tabla de usuarios</h2>';
    
    $checkUsuario = $conn->query("SHOW TABLES LIKE 'usuario'");
    if ($checkUsuario->rowCount() > 0) {
        showMessage('info', 'Información', 'La tabla "usuario" ya existe en la base de datos.');
    } else {
        showMessage('error', 'Atención', 'La tabla "usuario" no existe. Esta tabla es necesaria para continuar con la instalación.');
        echo '<p>Por favor, asegúrese de que la tabla "usuario" esté creada antes de continuar.</p>';
        exit;
    }
    echo '</div>';
    
    // Paso 2: Crear tabla de categorías
    echo '<div class="step">
            <h2>Paso 2: Crear tabla de categorías</h2>';
    
    // SQL para crear la tabla categoria
    $sql_categoria = "
    CREATE TABLE IF NOT EXISTS categoria (
        id_categoria INT AUTO_INCREMENT PRIMARY KEY,
        nombre_categoria VARCHAR(100) NOT NULL,
        descripcion TEXT,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    // Ejecutar la consulta para crear la tabla
    $conn->exec($sql_categoria);
    
    // Verificar si la tabla ya tiene datos
    $check = $conn->query("SELECT COUNT(*) FROM categoria");
    $count = $check->fetchColumn();
    
    // Si no hay datos, insertar las categorías
    if ($count == 0) {
        // Categorías a insertar
        $categorias = [
            ['Tienda Online', 'Ideal para vender productos físicos o digitales con carrito de compras.'],
            ['Marketplace', 'Plataforma para múltiples vendedores y compradores.'],
            ['Blog y Noticias', 'Perfecto para compartir contenido, artículos y noticias.'],
            ['Recetas y Eventos', 'Ideal para restaurantes, chefs o planificadores de eventos.'],
            ['Páginas Corporativas', 'Sitios web profesionales para empresas y negocios.'],
            ['Consultoría y Coaching', 'Para profesionales que ofrecen servicios de asesoría.'],
            ['Deportes y Fotografía', 'Perfecto para fotógrafos, deportistas o clubes deportivos.'],
            ['Música y Juegos', 'Para artistas, músicos o desarrolladores de juegos.'],
            ['Streaming y Reservas', 'Plataformas de contenido en streaming o sistemas de reservas.'],
            ['Foro Online', 'Comunidades de discusión y foros temáticos.']
        ];
        
        // Preparar la consulta para insertar
        $stmt = $conn->prepare("INSERT INTO categoria (nombre_categoria, descripcion) VALUES (?, ?)");
        
        // Insertar cada categoría
        foreach ($categorias as $categoria) {
            $stmt->execute($categoria);
        }
        
        showMessage('success', '¡Éxito!', 'La tabla "categoria" ha sido creada y se han insertado las 10 categorías.');
    } else {
        showMessage('info', 'Información', 'La tabla "categoria" ya existe y contiene datos. No se han realizado cambios.');
    }
    echo '</div>';
    
    // Paso 3: Crear tabla de pedidos
    echo '<div class="step">
            <h2>Paso 3: Crear tabla de pedidos</h2>';
    
    // SQL para crear la tabla pedido
    $sql_pedido = "
    CREATE TABLE IF NOT EXISTS pedido (
        id_pedido INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NOT NULL,
        categoria_id INT NOT NULL,
        nombre_proyecto VARCHAR(255) NOT NULL,
        descripcion TEXT NOT NULL,
        fecha_pedido DATETIME NOT NULL,
        estado VARCHAR(50) NOT NULL DEFAULT 'pendiente',
        fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
        FOREIGN KEY (categoria_id) REFERENCES categoria(id_categoria) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    // Ejecutar la consulta
    $conn->exec($sql_pedido);
    
    showMessage('success', '¡Éxito!', 'La tabla "pedido" ha sido creada correctamente en la base de datos.');
    echo '</div>';
    
    // Mensaje final
    echo '<div class="step">
            <h2>Instalación Completada</h2>
            <p>Todas las tablas necesarias han sido creadas correctamente. Ahora puede utilizar el sistema de pedidos de páginas web personalizadas.</p>
            <a href="index.html" class="btn">Volver al Inicio</a>
          </div>';
    
} catch(PDOException $e) {
    showMessage('error', 'Error', 'No se pudieron crear las tablas: ' . $e->getMessage());
}

echo '</body></html>';
?> 