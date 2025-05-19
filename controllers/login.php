<?php
// Iniciar la sesión al principio
session_start();

// Incluir la configuración de la aplicación (para SITE_PATH)
require_once __DIR__ . '/config/app.php';

// Incluir la vista del formulario de login
require_once __DIR__ . '/views/login.php';
?> 