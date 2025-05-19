<?php

class AuthController {
    public function login() {
        $errorMessage = ''; // Variable para almacenar mensajes de error

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar el formulario de login
            $usuarioInput = $_POST['usuario'] ?? '';
            $passwordInput = $_POST['password'] ?? '';

            if (empty($usuarioInput) || empty($passwordInput)) {
                $errorMessage = 'Por favor, ingrese usuario/email y contraseña.';
            } else {
                // Lógica de autenticación
                $database = new Database();
                $db = $database->getConnection();

                // Consultar la base de datos para encontrar al usuario usando los nombres de columna correctos
                $query = "SELECT id_usuario, nombre, correo, contraseña, rol FROM usuario WHERE nombre = :usuario OR correo = :email LIMIT 1";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':usuario', $usuarioInput);
                $stmt->bindParam(':email', $usuarioInput);
                $stmt->execute();

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificar la contraseña usando la columna correcta 'contraseña'
                if ($user && password_verify($passwordInput, $user['contraseña'])) {
                    // Autenticación exitosa - Guardar datos correctos en la sesión
                    $_SESSION['usuario_id'] = $user['id_usuario']; // Usar 'id_usuario'
                    $_SESSION['usuario_nombre'] = $user['nombre'];
                    $_SESSION['usuario_email'] = $user['correo'];   // Usar 'correo'
                    $_SESSION['usuario_rol'] = $user['rol'];

                    // Redirigir al usuario a una página de inicio (ajusta la URL según tu proyecto)
                    header('Location: ' . APP_URL);
                    exit();
                } else {
                    // Credenciales inválidas
                    $errorMessage = 'Usuario o contraseña incorrectos.';
                }
            }
        }

        // Incluir la vista (pasa el mensaje de error si existe)
        require 'views/login.php';
    }
    
    public function logout() {
        session_destroy();
        // DEPURACIÓN: Mensaje antes de la redirección
        echo 'DEBUG: Intentando redirigir a ' . APP_URL;
        // Redirigir al usuario a la página de inicio (ajusta la URL según tu proyecto)
        header('Location: ' . APP_URL);
        //exit;
    }
} 