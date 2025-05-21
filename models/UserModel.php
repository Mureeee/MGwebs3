<?php
require_once __DIR__ . '/../config/database.php';

class UserModel {
    private $conn;
    private $table_name = "usuario"; // Nombre correcto de la tabla

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getUserByCorreo($correo) {
        $query = "SELECT id_usuario, nombre, correo, contraseña, rol FROM " . $this->table_name . " WHERE correo = ? LIMIT 0,1";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute([$correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($nombre, $correo, $contraseñaHash, $direccion_envio, $rol = 'cliente') {
        $query = "INSERT INTO " . $this->table_name . " (nombre, correo, contraseña, direccion_envio, rol) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt->execute([$nombre, $correo, $contraseñaHash, $direccion_envio, $rol])) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Puedes agregar más métodos aquí para obtener usuarios, actualizar, eliminar, etc.
} 