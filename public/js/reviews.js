document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.rating-input label');
    const ratingInputs = document.querySelectorAll('.rating-input input');
    const reviewForm = document.querySelector('.review-form');
    const reviewTextarea = document.querySelector('.review-textarea');

    // Efecto hover en las estrellas
    stars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const rating = this.getAttribute('for').replace('star', '');
            highlightStars(rating);
        });

        star.addEventListener('mouseout', function() {
            const checkedInput = document.querySelector('.rating-input input:checked');
            if (checkedInput) {
                highlightStars(checkedInput.value);
            } else {
                resetStars();
            }
        });
    });

    // Mantener el color cuando se selecciona una calificación
    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            highlightStars(this.value);
        });
    });

    // Función para resaltar estrellas
    function highlightStars(rating) {
        stars.forEach(star => {
            const starRating = star.getAttribute('for').replace('star', '');
            if (starRating <= rating) {
                star.style.color = '#ffd700';
            } else {
                star.style.color = '#666';
            }
        });
    }

    // Función para resetear estrellas
    function resetStars() {
        stars.forEach(star => {
            star.style.color = '#666';
        });
    }

    // Validación del formulario
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            const rating = document.querySelector('.rating-input input:checked');
            if (!rating) {
                e.preventDefault();
                alert('Por favor, selecciona una calificación');
                return;
            }

            if (!reviewTextarea.value.trim()) {
                e.preventDefault();
                alert('Por favor, escribe un comentario');
                return;
            }
        });
    }
});
