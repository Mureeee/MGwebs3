<?php

class UsuarioController {
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    public function isLoggedIn() {
        // Verificar si la variable de sesión para el ID de usuario está definida
        return isset($_SESSION['usuario_id']);
    }

    public function getUsuario($id) {
        $stmt = $this->conn->prepare("SELECT id_usuario, nombre, correo, direccion_envio FROM usuario WHERE id_usuario = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarUsuario($id, $nombre, $email, $password = null) {
        try {
            $this->conn->beginTransaction();

            // Actualizar nombre y correo
            $sql = "UPDATE usuario SET nombre = ?, correo = ? WHERE id_usuario = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$nombre, $email, $id]);

            // Si se proporcionó una nueva contraseña
            if ($password !== null && !empty($password)) {
                // Aquí podrías añadir lógica para confirmar contraseña si es necesario
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE usuario SET contraseña = ? WHERE id_usuario = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$password_hash, $id]);
            }

            $this->conn->commit();

            // Opcional: actualizar datos de sesión si se cambian nombre/correo
            // if (isset($_SESSION['usuario_nombre'])) $_SESSION['usuario_nombre'] = $nombre;
            // if (isset($_SESSION['usuario_correo'])) $_SESSION['usuario_correo'] = $email;

            return true; // Éxito
        } catch (PDOException $e) {
            $this->conn->rollBack();
            // Aquí podrías loggear el error: $e->getMessage()
            return false; // Error
        }
    }

    // Puedes añadir más métodos relacionados con usuarios aquí (registro, login, etc.)
}

?> 