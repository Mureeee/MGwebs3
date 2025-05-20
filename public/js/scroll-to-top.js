document.addEventListener('DOMContentLoaded', function () {
    const scrollBtn = document.getElementById('scrollToTopBtn');

    // Función para verificar la posición de scroll y mostrar/ocultar el botón
    function checkScrollPosition() {
        if (window.scrollY > 300) {
            scrollBtn.classList.add('visible');
        } else {
            scrollBtn.classList.remove('visible');
        }
    }

    // Verificar al cargar la página
    checkScrollPosition();

    // Verificar al hacer scroll
    window.addEventListener('scroll', checkScrollPosition);

    // Acción al hacer clic en el botón
    scrollBtn.addEventListener('click', function () {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
