<?php
extract($data); // Asegurarse de extraer los datos del controlador
include 'partials/header.php'; // Incluir el header
include 'partials/navbar.php'; // Incluir el navbar
?>

<main>
    <div class="perfil-container registration-container">
        <h1>Mi Perfil</h1>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje-exito"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="<?php echo APP_URL; ?>/perfil" method="POST" class="perfil-form">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="direccion_envio">Dirección de Envío:</label>
                <textarea id="direccion_envio" name="direccion_envio"><?php echo htmlspecialchars($usuario['direccion_envio']); ?></textarea>
            </div>

            <h2>Cambiar Contraseña (opcional)</h2>
            <div class="form-group">
                <label for="password">Nueva Contraseña:</label>
                <input type="password" id="password" name="password">
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
        </form>

        <!-- Aquí podrías añadir más secciones como historial de pedidos, etc. -->

    </div>
</main>

<?php include 'partials/footer.php'; // Incluir el footer ?> 