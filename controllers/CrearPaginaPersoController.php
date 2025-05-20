<?php

class CrearPaginaPersoController {
    public function index() {
        // La sesión ya se inició en el controlador frontal (index.php)

        // Verificar si el usuario ha iniciado sesión
        // La variable $isLoggedIn se espera que esté disponible desde index.php
        global $isLoggedIn, $primeraLetra, $nombreUsuario, $rolUsuario; 

        // Si usas un partial de navbar, podrías necesitar pasar estas variables a la vista o al partial
        // Alternativamente, podrías incluirlos aquí si la vista de crearpaginaperso no usa el partial de navbar principal
        $isLoggedIn = isset($_SESSION['usuario_id']);
        $primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';
        $nombreUsuario = $isLoggedIn ? $_SESSION['usuario_nombre'] : '';
        $rolUsuario = $isLoggedIn ? $_SESSION['usuario_rol'] : '';

        // Incluir la vista correspondiente
        require 'views/crearpaginaperso_view.php';
    }

    // Si tienes lógica para procesar el formulario, podrías añadir otro método aquí
    // public function procesarFormulario() {
    //     // Lógica para manejar el envío del formulario
    // }
}

?> 