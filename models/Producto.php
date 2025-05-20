<?php

class Producto {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getProductos($filtros = []) {
        $sql = "SELECT p.*, c.nombre_categoria 
                FROM productos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id_categoria 
                WHERE 1=1";
        $params = [];

        if (!empty($filtros['nombre'])) {
            $sql .= " AND p.nombre LIKE ?";
            $params[] = "%" . $filtros['nombre'] . "%";
        }

        if (!empty($filtros['categoria'])) {
            $sql .= " AND p.categoria_id = ?";
            $params[] = $filtros['categoria'];
        }

        if (!empty($filtros['precio_min'])) {
            $sql .= " AND p.precio >= ?";
            $params[] = $filtros['precio_min'];
        }

        if (!empty($filtros['precio_max'])) {
            $sql .= " AND p.precio <= ?";
            $params[] = $filtros['precio_max'];
        }

        return $this->db->query($sql, $params);
    }

    public function getProductoById($id) {
        $sql = "SELECT p.*, c.nombre_categoria 
                FROM productos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id_categoria 
                WHERE p.id = ?";
        $result = $this->db->query($sql, [$id]);
        return $result ? $result[0] : null;
    }

    public function getCategorias() {
        return $this->db->query("SELECT * FROM categorias ORDER BY nombre_categoria");
    }

    public function getPrecioMinMax() {
        $result = $this->db->query("SELECT MIN(precio) as min_precio, MAX(precio) as max_precio FROM productos");
        return $result ? $result[0] : ['min_precio' => 0, 'max_precio' => 1000];
    }
}
