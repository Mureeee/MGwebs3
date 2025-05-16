<?php
require_once 'config/app.php';
session_start();
$isLoggedIn = isset($_SESSION['usuario_id']);
$primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';

// Incluir la vista
require 'views/index.php';

?>