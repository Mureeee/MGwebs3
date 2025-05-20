document.addEventListener('DOMContentLoaded', function () {
    const userMenu = document.querySelector('.user-menu');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    if (userMenu) {
        // Alternar el menú desplegable al hacer clic en el avatar
        userMenu.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('active');
        });

        // Cerrar el menú al hacer clic fuera de él
        document.addEventListener('click', function () {
            if (dropdownMenu.classList.contains('active')) {
                dropdownMenu.classList.remove('active');
            }
        });

        // Evitar que el menú se cierre al hacer clic dentro de él
        dropdownMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    // Menú móvil
    const menuButton = document.querySelector('.menu-button');
    const navLinks = document.querySelector('.nav-links');

    if (menuButton) {
        menuButton.addEventListener('click', function () {
            navLinks.classList.toggle('active');
        });
    }
});
