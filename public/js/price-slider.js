// Script para el slider de precio
document.addEventListener('DOMContentLoaded', function() {
    const precioSlider = document.getElementById('precio_slider');
    const precioMaxInput = document.getElementById('precio_max');
    const precioMinInput = document.getElementById('precio_min');

    if (precioSlider && precioMaxInput) {
        // Sincronizar slider con input max
        precioSlider.addEventListener('input', function() {
            precioMaxInput.value = this.value;
        });

        // Sincronizar input max con slider
        precioMaxInput.addEventListener('input', function() {
            if (parseInt(this.value) >= parseInt(precioSlider.min) && parseInt(this.value) <= parseInt(precioSlider.max)) {
                precioSlider.value = this.value;
            }
        });

        // Sincronizar input min con slider
        precioMinInput.addEventListener('input', function() {
            // Validar que el precio mínimo no sea mayor que el máximo
            if (parseInt(this.value) > parseInt(precioMaxInput.value)) {
                this.value = precioMaxInput.value;
            }
        });

        // Inicializar input max con el valor del slider si hay un valor pre-filtrado
        if (precioMaxInput.value === '') {
            precioMaxInput.value = precioSlider.value;
        }
    }
});
