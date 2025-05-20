<?php

class AdminController {
    private $db;

    public function __construct() {
        // Asegurar que la sesión esté iniciada (ya debería estar en index.php)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Incluir archivos necesarios
        require_once __DIR__ . '/../config/database.php';
        require_once __DIR__ . '/../config/app.php'; // Para SITE_PATH si se usa
        require_once __DIR__ . '/UsuarioController.php'; // Asegúrate de la ruta correcta

        // Instanciar la base de datos
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        // Instanciar el controlador de usuario
        $usuarioController = new UsuarioController($this->db);

        // Verificar si el usuario ha iniciado sesión y es administrador
        if (!$usuarioController->isLoggedIn() || !$usuarioController->isAdmin()) {
            // Redirigir a la página de inicio de sesión
            header('Location: ' . APP_URL . '/login'); // Usar APP_URL con la ruta del enrutador
            exit();
        }

        // Lógica para obtener datos si es necesario (ej: lista de usuarios, productos, etc.)
        // Aquí podrías llamar a métodos del UsuarioController o de un ProductoModel
        // $productos = $productoModel->getAll();
        // $usuarios = $usuarioController->getAllUsers();

        // Preparar datos para la vista (si los hay)
        $data = [
            'isLoggedIn' => true, // Sabemos que está logueado si llega aquí
            'primeraLetra' => strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)),
            // Pasar datos como $productos, $usuarios si se obtienen
            // 'productos' => $productos,
            // 'usuarios' => $usuarios
        ];

        // Hacer que las variables del array $data estén disponibles en la vista
        extract($data);

        // Incluir la vista
        // Usar __DIR__ para la ruta absoluta de la vista
        require __DIR__ . '/../views/admin_panel.php';
    }
} 