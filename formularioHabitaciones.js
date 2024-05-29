document.addEventListener("DOMContentLoaded", function () {
  var idReformaCheckbox = document.getElementById("idreforma");
  var usuarioSelectContainer = document.getElementById(
    "usuario-select-container"
  );
  var roomSelectContainer = document.getElementById("room-select-container");
  var legendReserva = document.getElementById("legend-reserva");
  var legendReforma = document.getElementById("legend-reforma");
  var numeroPersonasContainer = document.getElementById(
    "numeropersonas-container"
  );
  var comentarioContainer = document.getElementById("comentario-container");
  var form = document.querySelector("form");

  if (idReformaCheckbox) {
    idReformaCheckbox.addEventListener("change", function () {
      if (this.checked) {
        usuarioSelectContainer.style.display = "none";
        roomSelectContainer.style.display = "block";
        legendReserva.style.display = "none";
        legendReforma.style.display = "block";
        numeroPersonasContainer.style.display = "none";
        comentarioContainer.style.display = "none";
        reformaMarcadaContainer.style.display = "block";
      } else {
        usuarioSelectContainer.style.display = "block";
        roomSelectContainer.style.display = "none";
        legendReserva.style.display = "block";
        legendReforma.style.display = "none";
        numeroPersonasContainer.style.display = "block";
        comentarioContainer.style.display = "block";
        reformaMarcadaContainer.style.display = "none";
      }
    });
  }

  // Captura el evento de envío del formulario
  form.addEventListener("submit", function (event) {
    // Detiene el envío del formulario
    event.preventDefault();

    // Verifica si la casilla de verificación de reforma está marcada
    if (idReformaCheckbox && idReformaCheckbox.checked) {
      // Verifica que las fechas no estén vacías y que la fecha de entrada sea menor que la fecha de salida
      var fechaEntrada = document.getElementById("identrada").value;
      var fechaSalida = document.getElementById("idsalida").value;

      if (!fechaEntrada || !fechaSalida || fechaEntrada >= fechaSalida) {
        alert(
          "Por favor, selecciona fechas válidas: la fecha de entrada debe ser anterior a la fecha de salida."
        );
        return false; // Detiene el envío del formulario
      }

      // Muestra una ventana de confirmación
      var confirmacion = confirm("¿Estás seguro de enviar la reserva?");

      // Si el usuario confirma, envía el formulario
      if (confirmacion) {
        form.submit();
      } else {
        // Si el usuario cancela, no hace nada
        return false;
      }
    } else {
        // Si la casilla de verificación de reforma no está marcada, envía el formulario
        form.submit();
    }
  });
});
