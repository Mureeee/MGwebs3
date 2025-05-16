<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Iniciar sesión lo antes posible

require_once 'config/app.php'; // Incluye el archivo de configuración para SITE_PATH
require_once 'controllers/como_funciona.php'; // Incluye el controlador de como_funciona

?> 