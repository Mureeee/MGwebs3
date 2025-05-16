<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Log para depuración
    error_log("Intentando obtener productos de la base de datos");
    
    // Consulta simplificada que obtiene los datos tal cual están en la base de datos
    $query = "SELECT id_producto as id, nombre_producto as nombre, descripcion, precio, imagenes as imagen, categoria_id 
              FROM producto 
              ORDER BY id_producto DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Log del número de productos encontrados
    error_log("Productos encontrados: " . count($productos));
    
    if (empty($productos)) {
        error_log("No se encontraron productos");
        echo json_encode([]);
    } else {
        foreach ($productos as &$producto) {
            $producto['precio'] = number_format((float)$producto['precio'], 2, '.', '');
        }
        echo json_encode($productos);
    }
} catch (Exception $e) {
    error_log("Error al obtener productos: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
?> 