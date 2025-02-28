<?php foreach ($productos as $producto): ?>
    <div class="producto">
        <h2><?php echo htmlspecialchars($producto['nombre_producto']); ?></h2>
        <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
        <p class="precio">Precio: $<?php echo number_format($producto['precio'], 2); ?></p>
    </div>
<?php endforeach; ?> 