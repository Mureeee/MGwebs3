<?php include 'partials/header.php'; // Incluir el header ?>

<main>
    <div class="perfil-container">
        <h1>Mi Perfil</h1>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje-exito"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="<?php echo SITE_PATH; ?>controllers/perfil.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
            
            <label for="direccion_envio">Dirección de Envío:</label>
            <textarea id="direccion_envio" name="direccion_envio"><?php echo htmlspecialchars($usuario['direccion_envio']); ?></textarea>

            <h2>Cambiar Contraseña (opcional)</h2>
            <label for="password">Nueva Contraseña:</label>
            <input type="password" id="password" name="password">

            <button type="submit">Actualizar Perfil</button>
        </form>

        <!-- Aquí podrías añadir más secciones como historial de pedidos, etc. -->

    </div>
</main>

<?php include 'partials/footer.php'; // Incluir el footer ?> 