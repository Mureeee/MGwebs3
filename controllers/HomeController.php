<?php

class HomeController {
    public function index() {
        $isLoggedIn = isset($_SESSION['usuario_id']);
        $primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';
        
        require 'views/index.php';
    }
} 