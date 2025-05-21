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
     <style>
        /* Estilos específicos para el formulario del panel admin */
        .admin-form {
            margin-bottom: 3rem;
            padding: 1.5rem;
            background: rgba(40, 40, 40, 0.9); /* Fondo un poco más claro que el panel */
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .admin-form h3 {
            color: #a78bfa;
            margin-top: 0;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 0.5rem;
        }

        .admin-form .form-group {
            margin-bottom: 1rem;
        }

        .admin-form label {
            display: block;
            margin-bottom: 0.5rem;
            color: #ccc;
            font-size: 0.9rem;
        }

        .admin-form input[type="text"],
        .admin-form input[type="number"],
        .admin-form textarea,
        .admin-form select {
            width: 100%;
            padding: 0.75rem;
            border-radius: 5px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.05);
            color: white;
            font-size: 1rem;
        }

         .admin-form input[type="file"] {
            width: 100%;
            padding: 0.75rem;
            border-radius: 5px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.05);
            color: white;
            font-size: 1rem;
        }

        .admin-form textarea {
            resize: vertical;
            min-height: 100px;
        }

        .admin-form select option {
            background: #1e1e1e; /* Fondo oscuro para opciones del select */
            color: white;
        }

        .admin-form button[type="submit"] {
            display: block;
            width: 100%;
            padding: 0.75rem;
            border-radius: 5px;
            border: none;
            background: linear-gradient(90deg, #6a11cb, #2575fc); /* Gradiente para el botón */
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .admin-form button[type="submit"]:hover {
             transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(106, 17, 203, 0.2);
        }
         
         /* Ajustes para la tabla de productos */
         .admin-panel table {
             margin-top: 2rem; /* Espacio entre el formulario y la tabla */
         }

         .admin-panel th,
         .admin-panel td {
             vertical-align: middle; /* Centrar contenido verticalmente en celdas */
         }

         .admin-panel td:last-child a, /* Estilo para los enlaces de acción (editar/eliminar) */
         .admin-panel td:last-child button {
             margin-right: 10px; /* Espacio entre enlaces/botones */
             text-decoration: none; /* Quitar subrayado */
             padding: 5px 10px;
             border-radius: 4px;
             transition: opacity 0.3s ease;
         }

          .admin-panel td:last-child button {
              background: #e53e3e; /* Fondo rojo para eliminar */
              color: white;
              border: none;
              cursor: pointer;
          }

          .admin-panel td:last-child button:hover {
              opacity: 0.8;
          }

          .admin-panel td:last-child a:hover {
               opacity: 0.8;
          }

          .product-image-preview {
              width: 50px;
              height: 50px;
              object-fit: cover;
              border-radius: 4px;
              vertical-align: middle; /* Alinear con el texto de la celda */
              margin-right: 10px;
              border: 1px solid rgba(255, 255, 255, 0.2);
          }

          .current-image-preview {
              display: block;
              margin-top: 10px;
              max-width: 100px;
              height: auto;
              border-radius: 4px;
              border: 1px solid rgba(255, 255, 255, 0.2);
          }
          
    </style>
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <section class="admin-panel">
            <h1>Panel de Administración</h1>
            
            <?php
            // Mostrar mensajes de éxito o error si existen
            if (isset($_SESSION['success_message'])) {
                echo '<div class="message success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['error_message'])) {
                echo '<div class="message error">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                unset($_SESSION['error_message']);
            }

            // Determinar si estamos en modo edición o añadir
            // Si hay un producto en $data, significa que estamos editando
            $isEditing = isset($producto_a_editar) && !empty($producto_a_editar); // Usar un nombre diferente para evitar conflicto con el loop de productos
            $formTitle = $isEditing ? 'Editar Producto' : 'Añadir Nuevo Producto';
            $formAction = $isEditing ? APP_URL . '/admin/edit/' . $producto_a_editar['id_producto'] : APP_URL . '/admin/add';
            $buttonText = $isEditing ? 'Actualizar Producto' : 'Guardar Producto';

            // Si estamos en modo edición, mostrar el formulario de edición directamente
            if ($isEditing):
            ?>
             <div id="addProductFormContainer">
                <form action="<?php echo $formAction; ?>" method="POST" class="admin-form" enctype="multipart/form-data">
                    <h3><?php echo $formTitle; ?></h3>

                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto_a_editar['nombre_producto'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($producto_a_editar['descripcion'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="precio">Precio:</label>
                        <input type="number" id="precio" name="precio" step="0.01" value="<?php echo htmlspecialchars($producto_a_editar['precio'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="categoria_id">Categoría:</label>
                        <select id="categoria_id" name="categoria_id" required>
                            <option value="">Selecciona una categoría</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['id_categoria']; ?>" <?php echo (isset($producto_a_editar['categoria_id']) && $producto_a_editar['categoria_id'] == $cat['id_categoria']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="imagen">Imagen:</label>
                        <input type="file" id="imagen" name="imagen" accept="image/*">
                        <?php if ($isEditing && !empty($producto_a_editar['imagenes'])): ?>
                            <?php
                                $imagen_actual = '';
                                $imagenes_decodificadas = json_decode($producto_a_editar['imagenes'], true);
                                if (is_array($imagenes_decodificadas) && !empty($imagenes_decodificadas)) {
                                    $imagen_actual = APP_URL . '/' . $imagenes_decodificadas[0];
                                }
                            ?>
                            <?php if (!empty($imagen_actual)): ?>
                                <p>Imagen actual:</p>
                                <img src="<?php echo $imagen_actual; ?>" alt="Imagen actual" class="current-image-preview">
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <button type="submit"><?php echo $buttonText; ?></button>
                </form>
            </div>

            <?php else: // Si no estamos editando, mostramos el botón para añadir y el contenedor (inicialmente oculto) para el formulario de añadir ?>
                 <button id="toggleAddProductForm" class="btn">Añadir Nuevo Producto</button>
                 <div id="addProductFormContainer" style="display: none;">
                    <form action="<?php echo $formAction; ?>" method="POST" class="admin-form" enctype="multipart/form-data">
                         <h3><?php echo $formTitle; ?></h3>

                         <div class="form-group">
                             <label for="nombre">Nombre:</label>
                             <input type="text" id="nombre" name="nombre" value="" required>
                         </div>

                         <div class="form-group">
                             <label for="descripcion">Descripción:</label>
                             <textarea id="descripcion" name="descripcion" required></textarea>
                         </div>

                         <div class="form-group">
                             <label for="precio">Precio:</label>
                             <input type="number" id="precio" name="precio" step="0.01" value="" required>
                         </div>

                         <div class="form-group">
                             <label for="categoria_id">Categoría:</label>
                             <select id="categoria_id" name="categoria_id" required>
                                 <option value="">Selecciona una categoría</option>
                                 <?php foreach ($categorias as $cat): ?>
                                     <option value="<?php echo $cat['id_categoria']; ?>"><?php echo htmlspecialchars($cat['nombre_categoria']); ?></option>
                                 <?php endforeach; ?>
                             </select>
                         </div>

                         <div class="form-group">
                             <label for="imagen">Imagen:</label>
                             <input type="file" id="imagen" name="imagen" accept="image/*">
                         </div>

                         <button type="submit"><?php echo $buttonText; ?></button>
                     </form>
                 </div> <!-- Cierre de #addProductFormContainer -->
            <?php endif; ?>

            <h2>Gestión de Productos</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($productos)): ?>
                        <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo $producto['id_producto']; ?></td>
                             <td>
                                <?php
                                    $imagen_src = '';
                                    $imagen_ruta_relativa_db = '';

                                    if (!empty($producto['imagenes'])) {
                                        // Intentar decodificar JSON, si falla, asumir que es una cadena simple
                                        $imagenes_data = json_decode($producto['imagenes'], true);

                                        if (json_last_error() === JSON_ERROR_NONE && is_array($imagenes_data) && !empty($imagenes_data)) {
                                            // Si es JSON válido y un array, tomar el primer elemento
                                            $imagen_ruta_relativa_db = ltrim($imagenes_data[0], '/');
                                        } else {
                                            // Si no es JSON o array, asumir que el valor es la ruta simple
                                            $imagen_ruta_relativa_db = ltrim($producto['imagenes'], '/');
                                        }

                                        // Construir la ruta completa del archivo en el servidor
                                        // Usamos __DIR__ . '/../' para ir a la raíz del proyecto desde la vista
                                        $ruta_completa_servidor = __DIR__ . '/../' . $imagen_ruta_relativa_db;

                                        // Verificar si el archivo existe en el servidor usando la ruta de la DB
                                        if (file_exists($ruta_completa_servidor)) {
                                            // Si existe, construimos la URL completa usando APP_URL
                                            $imagen_src = APP_URL . '/' . $imagen_ruta_relativa_db;
                                        }
                                    }
                                ?>
                                <?php if (!empty($imagen_src)): ?>
                                    <img src="<?php echo $imagen_src; ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" class="product-image-preview">
                                <?php else: ?>
                                    Imagen no disponible
                                    <?php
                                        // Información de depuración si la imagen no está disponible
                                        $debug_output = "";
                                        $debug_output .= "<br>DB: " . htmlspecialchars($producto['imagenes']);
                                        // $ruta_completa_servidor ya se definió arriba
                                        $debug_output .= "<br>Buscando en: " . htmlspecialchars($ruta_completa_servidor ?? 'Ruta no construida');
                                        $debug_output .= "<br>Existe: " . (file_exists($ruta_completa_servidor ?? '') ? 'Sí' : 'No');
                                    ?>
                                    <span style="font-size: 0.8em; color: #aaa;"><?php echo $debug_output; ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                            <td>€<?php echo number_format($producto['precio'], 2); ?></td>
                             <td><?php echo htmlspecialchars($producto['nombre_categoria']); ?></td>
                            <td>
                                <a href="<?php echo APP_URL; ?>/admin/edit/<?php echo $producto['id_producto']; ?>" class="btn btn-outline">Editar</a>
                                <form action="<?php echo APP_URL; ?>/admin/delete/<?php echo $producto['id_producto']; ?>" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este producto?');">
                                     <button type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="no-content">No hay productos disponibles.</td>
                        </tr>
                    <?php endif; ?>
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
                     <tr>
                         <td colspan="5" class="no-content">Aún no hay contenido de gestión de usuarios implementado.</td>
                    </tr>
                </tbody>
            </table>

            <!-- Puedes añadir más secciones según las necesidades del panel -->
            
        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <!-- Scripts JS específicos del panel si hay -->
    <script src="<?php echo APP_URL; ?>/public/js/script.js"></script>
    <!-- <script src="<?php echo APP_URL; ?>/public/js/admin_panel.js"></script> -->
    <script>
        // Script para mostrar/ocultar el formulario sin AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggleAddProductForm');
            const formContainer = document.getElementById('addProductFormContainer');

            if (toggleButton && formContainer) {
                toggleButton.addEventListener('click', function() {
                    // Alternar visibilidad
                    if (formContainer.style.display === 'none' || formContainer.style.display === '') {
                        formContainer.style.display = 'block'; // O 'flex'
                    } else {
                        formContainer.style.display = 'none';
                    }
                });
                 // Mantener el formulario visible si hubo un error de validación después de un POST fallido
                 <?php if (isset($_SESSION['error_message']) && $_SERVER['REQUEST_METHOD'] === 'POST' && !$isEditing): ?>
                      formContainer.style.display = 'block'; // Mostrar si hay error en POST al añadir
                 <?php endif; ?>
            }
        });
    </script>
</body>
</html> 