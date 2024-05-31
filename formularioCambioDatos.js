document.addEventListener('DOMContentLoaded', function() {
    var mostrarFormularioBtn = document.getElementById('mostrar-formulario');
    var formulario = document.getElementById('formulario-cambiar-datos');

    mostrarFormularioBtn.addEventListener('click', function() {
        if (formulario.style.display === 'none' || formulario.style.display === '') {
            formulario.style.display = 'block';
        } else {
            formulario.style.display = 'none';
        }
    });
});
