<?php
// partials/header.php - Incluye el inicio de la estructura HTML y la carga de estilos
// APP_URL debe estar definido antes de incluir este archivo (generalmente en index.php o config.php)

if (!defined('APP_URL')) {
    error_log("APP_URL no está definida en partials/header.php");
    // Considerar una forma de manejar este error o asegurar que APP_URL siempre esté definido.
    // Por ahora, definimos un fallback, pero la causa raíz debe ser arreglada en el punto de entrada.
    define('APP_URL', ''); 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'MGwebs'; ?></title>
    
    <!-- Enlaces a estilos CSS globales -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Aquí se pueden añadir más meta etiquetas, enlaces a fuentes, etc. -->
</head>
<body>
    <!-- El contenido principal de la página comienza después del body en la vista principal -->
</body>
</html>