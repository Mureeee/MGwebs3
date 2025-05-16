<?php
require_once 'config/database.php';

try {
    // Conectar a la base de datos
    $database = new Database();
    $conn = $database->getConnection();
    
    // SQL para crear la tabla pedido
    $sql = "
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
    $conn->exec($sql);
    
    echo "<div style='background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px;'>
            <h3>¡Éxito!</h3>
            <p>La tabla 'pedido' ha sido creada correctamente en la base de datos.</p>
          </div>";
    
} catch(PDOException $e) {
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px;'>
            <h3>Error</h3>
            <p>No se pudo crear la tabla: " . $e->getMessage() . "</p>
          </div>";
}
?> 