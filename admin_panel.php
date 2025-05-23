<?php
session_start();
// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header('Location: index.php');
    exit;
}

// Obtener la primera letra del nombre de usuario
$primeraLetra = strtoupper(substr($_SESSION['usuario_nombre'], 0, 1));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MGwebs - Panel de Administración</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="styles_admin.css">
</head>
<body>
<main class="min-h-screen bg-black">
        <!-- Particles Canvas -->
        <canvas id="sparkles" class="particles-canvas"></canvas>

        <!-- Main Content -->
        <div class="content-wrapper">
            <!-- Navbar -->
            <nav class="navbar slide-down">
                <a href="index.php" class="logo">
                    <svg class="bot-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2 2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
                        <path d="M12 8v8"/>
                        <path d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5z"/>
                    </svg>
                    <span>MGwebs</span>
                </a>

                <div class="nav-links">
                    <a href="caracteristicas.php">Características</a>
                    <a href="como_funciona.php">Cómo Funciona</a>
                    <a href="productos.php">Productos</a>
                    <a href="soporte.php">Soporte</a>
                    <a href="contactanos.php">Contáctanos</a>
                </div>

                <div class="auth-buttons">
                    <div class="user-menu">
                        <div class="user-avatar" title="<?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>">
                            <?php echo $primeraLetra; ?>
                        </div>
                        <div class="dropdown-menu">
                            <div class="dropdown-header">
                                <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                            </div>
                            <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'administrador'): ?>
                                <a href="admin_panel.php" class="dropdown-item">Panel Admin</a>
                            <?php endif; ?>
                            <a href="cerrar_sesion.php" class="dropdown-item">Cerrar Sesión</a>
                        </div>
                    </div>
                    <button class="btn btn-primary" onclick="window.location.href='productos.php'">Comenzar</button>
                </div>

                <button class="menu-button">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 6h16M4 12h16m-16 6h16"/>
                    </svg>
                </button>
            </nav>

    
    <main>
        

        <div class="admin-container">
            <h1>Panel de Administración</h1>
            
            <div class="admin-sections">
                <!-- Sección de Productos -->
                <section class="admin-section">
                    <h2>Gestión de Productos</h2>
                    <button class="btn btn-primary" onclick="mostrarFormulario('producto')">Añadir Producto</button>
                    
                    <div id="formulario-producto" class="form-container" style="display: none;">
                        <form id="productoForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nombre">Nombre del Producto:</label>
                                <input type="text" id="nombre" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción:</label>
                                <textarea id="descripcion" name="descripcion" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="precio">Precio:</label>
                                <input type="number" id="precio" name="precio" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="categoria_id">Categoría:</label>
                                <select id="categoria_id" name="categoria_id" required>
                                    <option value="1">Tiendas Online</option>
                                    <option value="2">Marketplaces</option>
                                    <option value="3">Blogs</option>
                                    <option value="4">Landing Pages</option>
                                    <option value="5">Páginas Corporativas</option>
                                    <option value="6">Webs de Consultoría</option>
                                    <option value="7">Webs Deportivas</option>
                                    <option value="8">Webs de Música</option>
                                    <option value="9">Webs de Streaming</option>
                                    <option value="10">Foros</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="imagen">Imagen:</label>
                                <input type="file" id="imagen" name="imagen" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar Producto</button>
                        </form>
                    </div>

                    <div class="productos-lista" id="productos-lista">
                        <!-- Los productos se cargarán aquí dinámicamente -->
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script>
    function mostrarFormulario(tipo) {
        const formulario = document.getElementById(`formulario-${tipo}`);
        formulario.style.display = formulario.style.display === 'none' ? 'block' : 'none';
    }

    function cargarProductos() {
        fetch('obtener_productos.php')
        .then(response => response.json())
        .then(data => {
            const listaProductos = document.getElementById('productos-lista');
            listaProductos.innerHTML = '';
            
            if (!Array.isArray(data) || data.length === 0) {
                listaProductos.innerHTML = '<p class="no-productos">No hay productos disponibles</p>';
                return;
            }
            
            data.forEach(producto => {
                const productoElement = document.createElement('div');
                productoElement.className = 'producto-item';
                productoElement.innerHTML = `
                    <img src="${producto.imagen || 'img/default-product.jpg'}" alt="${producto.nombre}" onerror="this.src='img/default-product.jpg'">
                    <div class="producto-info">
                        <h3>${producto.nombre}</h3>
                        <p>${producto.descripcion}</p>
                        <p class="precio">€${producto.precio}</p>
                    </div>
                    <div class="producto-acciones">
                        <button onclick="editarProducto(${producto.id})" class="btn btn-edit">
                            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Editar
                        </button>
                        <button onclick="eliminarProducto(${producto.id})" class="btn btn-danger">
                            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                <line x1="10" y1="11" x2="10" y2="17"/>
                                <line x1="14" y1="11" x2="14" y2="17"/>
                            </svg>
                            Eliminar
                        </button>
                    </div>
                `;
                listaProductos.appendChild(productoElement);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('productos-lista').innerHTML = 
                '<p class="error">Error al cargar los productos</p>';
        });
    }

    function editarProducto(id) {
        fetch(`obtener_producto.php?id=${id}`)
        .then(response => response.json())
        .then(producto => {
            // Rellenar los campos del formulario con los datos existentes
            document.getElementById('nombre').value = producto.nombre_producto;
            document.getElementById('descripcion').value = producto.descripcion;
            document.getElementById('precio').value = producto.precio;
            document.getElementById('categoria_id').value = producto.categoria_id;
            
            // Mostrar la imagen actual
            const imagenPreview = document.createElement('div');
            imagenPreview.className = 'imagen-actual';
            imagenPreview.innerHTML = `
                <p>Imagen actual:</p>
                <img src="${producto.imagenes}" alt="${producto.nombre_producto}" style="max-width: 200px;">
                <p class="imagen-nota">* Sube una nueva imagen solo si deseas cambiarla</p>
            `;
            
            // Quitar el required del input de imagen
            document.getElementById('imagen').removeAttribute('required');
            
            // Insertar preview antes del input de imagen
            const imagenInput = document.getElementById('imagen').parentNode;
            imagenInput.insertBefore(imagenPreview, imagenInput.firstChild);
            
            // Añadir el ID del producto al formulario
            const form = document.getElementById('productoForm');
            form.dataset.editId = id;
            form.dataset.imagenActual = producto.imagenes;
            
            // Mostrar el formulario
            document.getElementById('formulario-producto').style.display = 'block';
            
            // Scroll hacia el formulario
            document.getElementById('formulario-producto').scrollIntoView({ behavior: 'smooth' });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar el producto');
        });
    }

    function eliminarProducto(id) {
        if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
            const formData = new FormData();
            formData.append('id', id);
            
            fetch('eliminar_producto.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Producto eliminado correctamente');
                    cargarProductos();
                } else {
                    alert('Error al eliminar el producto: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error al conectar con el servidor');
            });
        }
    }

    document.getElementById('productoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const editId = this.dataset.editId;
        
        // Si hay un ID, es una edición
        if (editId) {
            formData.append('id', editId);
            formData.append('action', 'update');
            
            // Si no se seleccionó una nueva imagen, mantener la actual
            if (!formData.get('imagen').size) {
                formData.delete('imagen');
                formData.append('imagen_actual', this.dataset.imagenActual);
            }
        }
        
        fetch('procesar_producto.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(editId ? 'Producto actualizado correctamente' : 'Producto guardado correctamente');
                this.reset();
                this.dataset.editId = '';
                // Limpiar la preview de imagen actual si existe
                const imagenActual = this.querySelector('.imagen-actual');
                if (imagenActual) {
                    imagenActual.remove();
                }
                // Restaurar el required del input de imagen
                document.getElementById('imagen').setAttribute('required', 'required');
                document.getElementById('formulario-producto').style.display = 'none';
                this.querySelector('button[type="submit"]').textContent = 'Guardar Producto';
                cargarProductos();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error al conectar con el servidor');
        });
    });

    // Cargar productos al iniciar
    document.addEventListener('DOMContentLoaded', cargarProductos);
    </script>
</body>
</html> 