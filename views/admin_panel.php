<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - MGwebs3</title>
    <!-- Enlazar CSS -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Incluir scripts JS si hay -->
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <section class="admin-panel">
            <h1>Panel de Administración</h1>
            
            <!-- Aquí iría el contenido específico del panel, por ejemplo, tablas de usuarios, productos, etc. -->
            <!-- Ejemplo de contenido: -->
            <h2>Gestión de Productos</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php /* foreach ($productos as $producto): */ ?>
                    <!-- <tr>
                        <td><?php // echo $producto['id']; ?></td>
                        <td><?php // echo $producto['nombre']; ?></td>
                        <td><?php // echo $producto['precio']; ?></td>
                        <td>
                            <a href="<?php // echo APP_URL; ?>/admin/edit_product.php?id=<?php // echo $producto['id']; ?>">Editar</a>
                            <a href="<?php // echo APP_URL; ?>/admin/delete_product.php?id=<?php // echo $producto['id']; ?>">Eliminar</a>
                        </td>
                    </tr> -->
                    <?php /* endforeach; */ ?>
                    <tr>
                         <td colspan="4">Aún no hay contenido de gestión implementado.</td>
                    </tr>
                </tbody>
            </table>

            <h2>Gestión de Usuarios</h2>
             <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php /* foreach ($usuarios as $usuario): */ ?>
                    <!-- <tr>
                        <td><?php // echo $usuario['id']; ?></td>
                        <td><?php // echo $usuario['nombre']; ?></td>
                        <td><?php // echo $usuario['email']; ?></td>
                        <td><?php // echo $usuario['rol']; ?></td>
                         <td>
                            <a href="<?php // echo APP_URL; ?>/admin/edit_user.php?id=<?php // echo $usuario['id']; ?>">Editar</a>
                            <a href="<?php // echo APP_URL; ?>/admin/delete_user.php?id=<?php // echo $usuario['id']; ?>">Eliminar</a>
                        </td>
                    </tr> -->
                    <?php /* endforeach; */ ?>
                     <tr>
                         <td colspan="5">Aún no hay contenido de gestión implementado.</td>
                    </tr>
                </tbody>
            </table>

            <!-- Puedes añadir más secciones según las necesidades del panel -->
            
        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <!-- Scripts JS específicos del panel si hay -->
    <script src="<?php echo APP_URL; ?>/public/js/script.js"></script>
</body>
</html> 