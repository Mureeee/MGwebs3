<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'MGwebs'; ?></title>
    <link rel="stylesheet" href="../public/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <?php // Aquí se pueden añadir enlaces a CSS específicos de la página si es necesario ?>
</head>
<body>

<!-- Navbar -->
<nav class="navbar slide-down">
        <div class="logo">
            <a href="<?php echo APP_URL; ?>" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                <svg class="bot-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2 2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" />
                    <path d="M12 8v8" />
                    <path d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5z" />
                </svg>
                <span>MGwebs</span>
            </a>
        </div>

        <div class="nav-links">
            <a href="<?php echo APP_URL; ?>/caracteristicas">Características</a>
            <a href="<?php echo APP_URL; ?>/como-funciona">Cómo Funciona</a>
            <a href="<?php echo APP_URL; ?>/productos">Productos</a>
            <a href="<?php echo APP_URL; ?>/soporte">Soporte</a>
            <a href="<?php echo APP_URL; ?>/contactanos">Contáctanos</a>
        </div>

        <div class="auth-buttons">
            <?php if ($isLoggedIn): ?>
                <div class="user-menu">
                    <div class="user-avatar" title="<?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>">
                        <?php echo $primeraLetra; ?>
                    </div>
                    <div class="dropdown-menu">
                        <div class="dropdown-header">
                            <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                        </div>
                        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'administrador'): ?>
                            <a href="<?php echo APP_URL; ?>/admin" class="dropdown-item">Panel Admin</a>
                        <?php endif; ?>
                        <a href="<?php echo APP_URL; ?>/perfil" class="dropdown-item">Perfil</a>
                        <a href="<?php echo APP_URL; ?>/logout" class="dropdown-item">Cerrar Sesión</a>

                    </div>
                </div>

                <!-- Icono del carrito (solo para usuarios logueados) -->
                <a href="<?php echo APP_URL; ?>/carrito" class="cart-icon">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    <?php if (!empty($_SESSION['carrito'])): ?>
                        <span class="cart-count"><?php echo array_sum($_SESSION['carrito']); ?></span>
                    <?php endif; ?>
                </a>
            <?php else: ?>
                <a href="<?php echo APP_URL; ?>/login" class="btn btn-ghost">Iniciar Sesión</a>
                <a href="<?php echo APP_URL; ?>/registrarse.html" class="btn btn-ghost">Registrate</a>
            <?php endif; ?>

            <button class="btn btn-primary" onclick="window.location.href='<?php echo APP_URL; ?>/crearpaginaperso'">Comenzar</button>
        </div>
    </nav>
</body>
</html> 