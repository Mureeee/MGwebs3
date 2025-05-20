<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php'; // Necesario para APP_URL si se usa en el modelo

class ProductoModel {
    private $conn;
    private $table_name = "producto";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todos los productos
    public function getAllProductos() {
        $query = "SELECT p.*, c.nombre_categoria FROM " . $this->table_name . " p LEFT JOIN categoria c ON p.categoria_id = c.id_categoria ORDER BY p.id_producto DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener un producto por ID
    public function getProductoById($id) {
        $query = "SELECT p.*, c.nombre_categoria FROM " . $this->table_name . " p LEFT JOIN categoria c ON p.categoria_id = c.id_categoria WHERE p.id_producto = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    // Añadir un nuevo producto
    public function addProducto($nombre, $descripcion, $precio, $categoria_id, $imagen_path = null) {
        $query = "INSERT INTO " . $this->table_name . " (nombre_producto, descripcion, precio, categoria_id, imagenes) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        // Convertir la ruta de la imagen a JSON si existe
        $imagenes_json = ($imagen_path !== null) ? json_encode([$imagen_path]) : null;

        // Sanitizar los valores (opcional pero recomendado)
        $nombre = htmlspecialchars(strip_tags($nombre));
        $descripcion = htmlspecialchars(strip_tags($descripcion));
        $precio = htmlspecialchars(strip_tags($precio));
        $categoria_id = htmlspecialchars(strip_tags($categoria_id));
        // La ruta de la imagen ya está controlada por la subida

        if ($stmt->execute([$nombre, $descripcion, $precio, $categoria_id, $imagenes_json])) {
            return true;
        }

        return false;
    }

    // Subir imagen (método auxiliar)
    public function uploadImage($file) {
        $target_dir = __DIR__ . "/../public/imagenes/"; // Directorio donde se guardarán las imágenes
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($file["name"],PATHINFO_EXTENSION));

        // Generar un nombre de archivo único para evitar colisiones
        $unique_file_name = uniqid() . "." . $imageFileType;
        $target_file = $target_dir . $unique_file_name;

        // Check if image file is a actual image or fake image
        $check = getimagesize($file["tmp_name"]);
        if($check !== false) {
            // echo "El archivo es una imagen - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            // echo "El archivo no es una imagen.";
            $uploadOk = 0;
        }

        // Check file size (ej. 5MB)
        if ($file["size"] > 5000000) {
            // echo "Lo siento, tu archivo es demasiado grande.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            // echo "Lo siento, solo se permiten archivos JPG, JPEG, PNG & GIF.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            // echo "Lo siento, tu archivo no fue subido.";
            return false; // Indica fallo en la subida
        } else {
            // if everything is ok, try to upload file
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                // Devolver la ruta relativa desde la raíz del proyecto
                return "public/imagenes/" . $unique_file_name;
            } else {
                // echo "Lo siento, hubo un error al subir tu archivo.";
                return false; // Indica fallo en la subida
            }
        }
    }

    // Actualizar un producto existente
    public function updateProducto($id, $nombre, $descripcion, $precio, $categoria_id, $imagen_path = null) {
        $query = "UPDATE " . $this->table_name . " SET nombre_producto = ?, descripcion = ?, precio = ?, categoria_id = ?";
        $params = [$nombre, $descripcion, $precio, $categoria_id];

        // Si se proporciona una nueva imagen, actualizar la columna de imágenes
        if ($imagen_path !== null) {
            $query .= ", imagenes = ?";
            $params[] = json_encode([$imagen_path]); // Guardar como JSON
        }

        $query .= " WHERE id_producto = ?";
        $params[] = $id;

        $stmt = $this->conn->prepare($query);
        
        // Sanitizar los valores (opcional pero recomendado)
        $nombre = htmlspecialchars(strip_tags($nombre));
        $descripcion = htmlspecialchars(strip_tags($descripcion));
        $precio = htmlspecialchars(strip_tags($precio));
        $categoria_id = htmlspecialchars(strip_tags($categoria_id));
        $id = htmlspecialchars(strip_tags($id));
        // La ruta de la imagen ya está controlada por la subida

        if ($stmt->execute($params)) {
            return true;
        }

        return false;
    }

    // Eliminar un producto
    public function deleteProducto($id) {
        // Opcional: Obtener la ruta de la imagen para eliminar el archivo físico
        // $producto = $this->getProductoById($id);
        // if ($producto && !empty($producto['imagenes'])) {
        //     $imagenes = json_decode($producto['imagenes'], true);
        //     foreach ($imagenes as $img_path) {
        //         $file_path = __DIR__ . '/../' . $img_path;
        //         if (file_exists($file_path)) {
        //             unlink($file_path);
        //         }
        //     }
        // }

        $query = "DELETE FROM " . $this->table_name . " WHERE id_producto = ?";
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar el ID
        $id = htmlspecialchars(strip_tags($id));

        if ($stmt->execute([$id])) {
            return true;
        }

        return false;
    }

    // Método auxiliar para obtener categorías (necesario para el formulario de añadir/editar)
    public function getAllCategorias() {
        $query = "SELECT id_categoria, nombre_categoria FROM categoria ORDER BY nombre_categoria";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?> 