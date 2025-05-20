USE mgwebs;

CREATE TABLE IF NOT EXISTS resenas (
    id_resena INT PRIMARY KEY AUTO_INCREMENT,
    producto_id INT,
    usuario_id INT,
    rating INT,
    comentario TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES producto(id_producto),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
