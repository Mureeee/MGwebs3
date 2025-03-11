<?php
session_start();
require_once 'config/database.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        // Dentro del bloque try, en la sección de actualización
        if (isset($_POST['action']) && $_POST['action'] === 'update') {
            $id = $_POST['id'];
            $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
            $descripcion = filter_var($_POST['descripcion'], FILTER_SANITIZE_STRING);
            $precio = filter_var($_POST['precio'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            
            // Si se subió una nueva imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $_FILES['imagen'];
                $nombreArchivo = $imagen['name'];
                $rutaDestino = 'imagenes/' . $nombreArchivo;
                
                if (!file_exists('imagenes')) {
                    mkdir('imagenes', 0777, true);
                }
                
                if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                    $nombreParaBD = 'imagenes/' . $nombreArchivo;
                } else {
                    throw new Exception('Error al subir la nueva imagen');
                }
            } else {
                // Mantener la imagen actual
                $nombreParaBD = $_POST['imagen_actual'];
            }
            
            $query = "UPDATE producto SET 
                      nombre_producto = ?, 
                      descripcion = ?, 
                      precio = ?, 
                      imagenes = ?, 
                      categoria_id = ? 
                      WHERE id_producto = ?";
                      
            $stmt = $conn->prepare($query);
            $stmt->execute([
                $nombre,
                $descripcion,
                $precio,
                $nombreParaBD,
                $_POST['categoria_id'] ?? 1,
                $id
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Producto actualizado correctamente'
            ]);
            exit;
        }
        
        // Obtener los datos del formulario
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
        $descripcion = filter_var($_POST['descripcion'], FILTER_SANITIZE_STRING);
        $precio = filter_var($_POST['precio'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        
        // Procesar la imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagen = $_FILES['imagen'];
            
            // Mantener el nombre original del archivo
            $nombreArchivo = $imagen['name'];
            $rutaDestino = 'imagenes/' . $nombreArchivo;
            
            // Verificar si la carpeta imagenes existe
            if (!file_exists('imagenes')) {
                mkdir('imagenes', 0777, true);
            }
            
            // Mover la imagen a la carpeta imagenes/
            if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                // Guardar en la base de datos con el prefijo "imagenes/"
                $nombreParaBD = 'imagenes/' . $nombreArchivo;
                
                $query = "INSERT INTO producto (nombre_producto, descripcion, precio, imagenes, categoria_id) 
                         VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->execute([
                    $nombre, 
                    $descripcion, 
                    $precio, 
                    $nombreParaBD, // Guardamos la ruta completa incluyendo "imagenes/"
                    $_POST['categoria_id'] ?? 1
                ]);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Producto añadido correctamente'
                ]);
            } else {
                throw new Exception('Error al subir la imagen');
            }
        } else {
            throw new Exception('No se proporcionó una imagen válida');
        }
        
    } catch (Exception $e) {
        error_log("Error al procesar producto: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error al guardar el producto: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
?> 