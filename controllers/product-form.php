<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?php echo isset($producto) ? 'Editar' : 'Agregar'; ?> Producto</title>
    <link rel="stylesheet" href="/public/assets/css/styles.css">
    <style>
        .product-form {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="header-common">
        <div class="container">
            <div class="logo-container">
                <a href="/index.php">
                    <img src="/public/assets/img/logo.png" alt="MG Logo">
                </a>
            </div>
        </div>
    </div>
    <div class="product-form">
        <h1><?php echo isset($producto) ? 'Editar' : 'Agregar'; ?> Producto</h1>

        <form method="POST" action="/controllers/ProductoController.php">
            <input type="hidden" name="action"
                value="<?php echo isset($producto) ? 'update-product' : 'add-product'; ?>">
            <?php if (isset($producto)): ?>
                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" id="nombre" name="nombre_producto" required
                    value="<?php echo isset($producto) ? htmlspecialchars($producto['nombre_producto']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required
                    rows="4"><?php echo isset($producto) ? htmlspecialchars($producto['descripcion']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" required
                    value="<?php echo isset($producto) ? $producto['precio'] : ''; ?>">
            </div>

            <button type="submit" class="btn-submit">
                <?php echo isset($producto) ? 'Actualizar' : 'Agregar'; ?> Producto
            </button>
        </form>

        <div style="margin-top: 20px;">
            <a href="?page=admin"><button type="button">Volver al Panel</button></a>
        </div>
    </div>
</body>

</html>