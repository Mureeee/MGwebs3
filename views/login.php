<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="<?php echo SITE_PATH; ?>public/styles.css"> <!-- Asumiendo que tienes un archivo CSS principal -->
</head>
<body>

    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="login-container">
        <h2>Iniciar Sesión</h2>
        
        <form action="<?php echo SITE_PATH; ?>controllers/procesar_login.php" method="POST">
            <div>
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
        
        <p>¿No tienes cuenta? <a href="<?php echo SITE_PATH; ?>registro.php">Regístrate aquí</a></p>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html> 