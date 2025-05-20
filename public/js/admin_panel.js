document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleAddProductForm');
    const formContainer = document.getElementById('addProductFormContainer');
    let formLoaded = false; // Bandera para saber si el formulario ya se cargó

    if (toggleButton && formContainer) {
        toggleButton.addEventListener('click', function() {
            if (!formLoaded) {
                // Si el formulario no se ha cargado, hacer la petición AJAX
                // *** IMPORTANTE: Reemplaza '/admin/get-add-product-form' con la URL correcta de tu endpoint PHP ***
                fetch('<?php echo APP_URL; ?>/admin/get-add-product-form') // Asegúrate de que APP_URL esté disponible o usa la URL directa
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.text();
                    })
                    .then(html => {
                        // Insertar el HTML del formulario en el contenedor
                        formContainer.innerHTML = html;
                        formLoaded = true;
                        // Mostrar el contenedor después de cargar el contenido
                        formContainer.style.display = 'block'; // O 'flex'
                    })
                    .catch(error => {
                        console.error('Error al cargar el formulario:', error);
                        // Opcional: mostrar un mensaje de error al usuario
                        formContainer.innerHTML = '<p style="color: red;">Error al cargar el formulario.</p>';
                        formContainer.style.display = 'block';
                    });
            } else {
                // Si el formulario ya se cargó, simplemente alternar visibilidad
                if (formContainer.style.display === 'none' || formContainer.style.display === '') {
                    formContainer.style.display = 'block'; // O 'flex'
                } else {
                    formContainer.style.display = 'none';
                }
            }
        });
    }
}); 