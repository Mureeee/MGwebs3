<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="/MGwebs3/public/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <?php // Aquí se pueden añadir enlaces a CSS específicos de la página si es necesario ?>
</head>
<body>

<?php
// DEPURACIÓN: Antes del header
 echo '<!-- Antes del header -->';
$isLoggedIn = isset($_SESSION['usuario_id']);
$primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';
include __DIR__ . '/partials/header.php';
// DEPURACIÓN: Después del header
 echo '<!-- Después del header -->';
?>

<main>
    <div class="register-container">
        <h2>Iniciar Sesión</h2>
        
        <?php if (!empty($errorMessage)): ?>
            <p style="color: red; text-align: center;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <form method="post" action="/MGwebs3/login">
            <label for="usuario">Usuario o Email</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Entrar</button>
        </form>
        <div class="register-link">
            ¿No tienes cuenta? <a href="/MGwebs3/registrarse.html">Regístrate</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html> 