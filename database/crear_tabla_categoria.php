<?php
require_once 'config/database.php';

try {
    // Conectar a la base de datos
    $database = new Database();
    $conn = $database->getConnection();
    
    // SQL para crear la tabla categoria
    $sql_crear = "
    CREATE TABLE IF NOT EXISTS categoria (
        id_categoria INT AUTO_INCREMENT PRIMARY KEY,
        nombre_categoria VARCHAR(100) NOT NULL,
        descripcion TEXT,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    // Ejecutar la consulta para crear la tabla
    $conn->exec($sql_crear);
    
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
        
        echo "<div style='background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px;'>
                <h3>¡Éxito!</h3>
                <p>La tabla 'categoria' ha sido creada y se han insertado las 10 categorías.</p>
              </div>";
    } else {
        echo "<div style='background-color: #cce5ff; color: #004085; padding: 15px; border-radius: 5px; margin: 20px;'>
                <h3>Información</h3>
                <p>La tabla 'categoria' ya existe y contiene datos. No se han realizado cambios.</p>
              </div>";
    }
    
} catch(PDOException $e) {
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px;'>
            <h3>Error</h3>
            <p>No se pudo crear la tabla o insertar los datos: " . $e->getMessage() . "</p>
          </div>";
}
?> 