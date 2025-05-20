<?php

require_once __DIR__ . '/../config/database.php';

require 'UsuarioController.php';

class PerfilController {

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        $usuarioController = new UsuarioController($this->db);

        // Verificar si el usuario ha iniciado sesión
        if (!$usuarioController->isLoggedIn()) {
            header('Location: ' . APP_URL . '/login');
            exit();
        }

        $usuario_id = $_SESSION['usuario_id'];
        $usuario = $usuarioController->getUsuario($usuario_id);

        // Si no se encuentra el usuario (esto no debería pasar si isLoggedIn() es true, pero es una medida de seguridad)
        if (!$usuario) {
            // Podríamos redirigir o mostrar un error. Por ahora, asignamos un array vacío
            // para evitar errores en la vista.
            $usuario = [
                'nombre' => '',
                'correo' => '',
                'direccion_envio' => ''
            ];
            // Opcional: Cerrar sesión o redirigir si el usuario no existe a pesar de la sesión
            // header('Location: ' . APP_URL . '/logout');
            // exit();
        }

        // Variables para el navbar (se pasarán a la vista a través de $data)
        $isLoggedIn = isset($_SESSION['usuario_id']);
        $primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';

        $mensaje = '';
        $error = '';

        // Procesar la actualización del perfil si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['usuario_id']; // Usar usuario_id de la sesión
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            // Validar y sanear entradas aquí antes de usar
            $password = !empty($_POST['password']) ? $_POST['password'] : null; // Solo actualizar si se proporciona una nueva contraseña

            if ($usuarioController->actualizarUsuario($id, $nombre, $email, $password)) {
                // Éxito al actualizar, recargar datos del usuario por si acaso
                $usuario = $usuarioController->getUsuario($id);
                // Actualizar nombre en la sesión si se cambió
                $_SESSION['usuario_nombre'] = $nombre;
                $mensaje = "Perfil actualizado correctamente.";
            } else {
                $error = "Error al actualizar el perfil.";
            }
        }

        // Preparar datos para pasar a la vista
        $data = [
            'isLoggedIn' => $isLoggedIn,
            'primeraLetra' => $primeraLetra,
            'usuario' => $usuario,
            'mensaje' => $mensaje,
            'error' => $error
        ];

        // Incluir la vista
        include __DIR__ . '/../views/perfil.php';
    }
}

?> 