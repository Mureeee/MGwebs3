<?php
// Iniciar la sesión al principio
session_start();

// Incluir la configuración de la aplicación (para SITE_PATH)
require_once __DIR__ . '/config/app.php';

// Incluir el controlador del panel de administración
require_once __DIR__ . '/controllers/admin_panel.php';
?> 