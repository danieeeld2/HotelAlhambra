var temporaryFiles = [];

document.addEventListener("DOMContentLoaded", function() {
    // Captura el evento de cambio en el campo de entrada de archivos
    document.getElementById('foto').addEventListener('change', function() {
        temporaryFiles = [];
        handleFileSelect(event);
        previsualizarImagenes(temporaryFiles);
    });
});

function handleFileSelect(event) {
    var files = event.target.files;
    for (var i = 0, file; file = files[i]; i++) {
        // Almacenar temporalmente el archivo
        temporaryFiles.push(file);
    }
}

function previsualizarImagenes(files) {
    if (files.length > 0) {
        var previsualizaciones = document.getElementById('previsualizaciones');
        previsualizaciones.innerHTML = '';

        for (var i = 0; i < files.length; i++) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var imagen = document.createElement('img');
                imagen.src = e.target.result;
                imagen.style.maxWidth = '350px'; // Cambia el tamaño máximo de la previsualización
                imagen.style.height = 'auto'; // Ajusta automáticamente la altura para mantener la proporción
                previsualizaciones.appendChild(imagen);
            }
            reader.readAsDataURL(files[i]);
        }
    }                    
}