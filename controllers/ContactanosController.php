<?php

class ContactanosController {
    public function index() {
        // Preparar los datos para la vista
        $data = [
            'isLoggedIn' => isset($_SESSION['usuario_id']),
            'primeraLetra' => isset($_SESSION['usuario_nombre']) ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '',
            'nombreUsuario' => isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : '',
            'correoUsuario' => isset($_SESSION['usuario_correo']) ? $_SESSION['usuario_correo'] : '',
            'rolUsuario' => isset($_SESSION['usuario_rol']) ? $_SESSION['usuario_rol'] : '',
            'itemsCarrito' => isset($_SESSION['carrito']) && is_array($_SESSION['carrito']) ? array_sum($_SESSION['carrito']) : 0
        ];

        // Cargar la vista
        require ROOT_PATH . '/views/contactanos.php';
    }

    public function enviar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Aquí iría la lógica para procesar el formulario de contacto
            // Por ejemplo, enviar un email, guardar en base de datos, etc.
            
            // Redireccionar con mensaje de éxito
            header('Location: ' . APP_URL . '/contactanos?success=1');
            exit;
        }
    }
}
