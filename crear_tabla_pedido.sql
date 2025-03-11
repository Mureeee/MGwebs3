-- Script para crear la tabla pedido
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

-- Comentarios sobre los campos:
-- id_pedido: Identificador único del pedido (clave primaria)
-- usuario_id: ID del usuario que realiza el pedido (clave foránea)
-- categoria_id: ID de la categoría seleccionada (clave foránea)
-- nombre_proyecto: Nombre del proyecto proporcionado por el usuario
-- descripcion: Descripción detallada del proyecto
-- fecha_pedido: Fecha y hora en que se realizó el pedido
-- estado: Estado actual del pedido (pendiente, en proceso, completado, cancelado)
-- fecha_actualizacion: Fecha y hora de la última actualización del pedido 