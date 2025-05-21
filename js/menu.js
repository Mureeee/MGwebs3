// js/menu.js
document.addEventListener('DOMContentLoaded', () => {
    const menuButton = document.querySelector('.menu-button');
    const navLinks = document.querySelector('.nav-links');

    if (menuButton && navLinks) {
        menuButton.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });

        // Opcional: cerrar el menú si se hace clic fuera de él
        document.addEventListener('click', (event) => {
            const isClickInside = navLinks.contains(event.target) || menuButton.contains(event.target);
            if (!isClickInside && navLinks.classList.contains('active')) {
                navLinks.classList.remove('active');
            }
        });
    }
}); 