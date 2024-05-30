document.querySelectorAll('.editar-comentario').forEach(button => {
    button.addEventListener('click', function() {
        let row = this.closest('tr');
        let comentarioTd = row.querySelector('.comentario');
        let nuevoComentarioTd = row.querySelector('.nuevo-comentario');
        let modificarComentarioBtn = row.querySelector('.modificar-comentario');

        comentarioTd.style.display = 'none';
        nuevoComentarioTd.style.display = 'table-cell';
        this.style.display = 'none';
        modificarComentarioBtn.style.display = 'inline-block';
    });
});

document.addEventListener("DOMContentLoaded", function() {
    var habitaciones = document.querySelectorAll(".habitacion");

    habitaciones.forEach(function(habitacion) {
        habitacion.addEventListener("mouseover", function(event) {
            var timestamp = parseInt(event.target.getAttribute("title"));
            var fechaCreacion = new Date(timestamp * 1000); // Multiplica por 1000 para convertir segundos a milisegundos
            var fechaLegible = fechaCreacion.toLocaleString(); // Convertir a una cadena de fecha legible

            var cuadroEmergente = document.createElement("div");
            cuadroEmergente.textContent = "Fecha de creación: " + fechaLegible;
            cuadroEmergente.style.position = "absolute";
            cuadroEmergente.style.backgroundColor = "white";
            cuadroEmergente.style.padding = "5px";
            cuadroEmergente.style.border = "1px solid black";
            cuadroEmergente.style.top = (event.clientY + 10) + "px";
            cuadroEmergente.style.left = (event.clientX + 10) + "px";
            document.body.appendChild(cuadroEmergente);

            habitacion.addEventListener("mouseout", function() {
                cuadroEmergente.remove();
            });
        });
    });
});