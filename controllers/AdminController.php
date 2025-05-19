<?php

class AdminController {
    public function index() {
        // Verificar si el usuario está autenticado y es administrador
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }
        
        require 'views/admin_panel.php';
    }
} 