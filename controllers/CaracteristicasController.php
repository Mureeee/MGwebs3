<?php
class CaracteristicasController {
    public function index() {
        // Verificar si el usuario está logueado
        $isLoggedIn = isset($_SESSION['usuario_id']);
        $primeraLetra = '';
        $nombreUsuario = '';
        $correoUsuario = '';
        $rolUsuario = '';
        $itemsCarrito = 0;

        // Si está logueado, obtener información del usuario
        if ($isLoggedIn) {
            $primeraLetra = strtoupper(substr($_SESSION['usuario_nombre'], 0, 1));
            $nombreUsuario = $_SESSION['usuario_nombre'];
            $correoUsuario = isset($_SESSION['usuario_correo']) ? $_SESSION['usuario_correo'] : '';
            $rolUsuario = isset($_SESSION['usuario_rol']) ? $_SESSION['usuario_rol'] : '';

            // Calcular items en el carrito
            if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
                $itemsCarrito = array_sum($_SESSION['carrito']);
            }
        }

        // Preparar los datos para la vista
        $data = [
            'isLoggedIn' => $isLoggedIn,
            'primeraLetra' => $primeraLetra,
            'nombreUsuario' => $nombreUsuario,
            'correoUsuario' => $correoUsuario,
            'rolUsuario' => $rolUsuario,
            'itemsCarrito' => $itemsCarrito
        ];

        // Cargar la vista
        require_once 'views/caracteristicas.php';
    }
} 