document.addEventListener('DOMContentLoaded', function() {
    const diasVisibles = 6;
    let diaInicio = 1;

    function mostrarDias() {
        const dias = document.querySelectorAll('.dia');
        dias.forEach((dia, index) => {
            if (dia.textContent === 'Na' || dia.textContent === 'Error') {
                dia.classList.add('na');
            } else {
                dia.classList.remove('na');
            }

            if (index + 1 >= diaInicio && index + 1 < diaInicio + diasVisibles) {
                dia.style.display = 'inline-block';
            } else {
                dia.style.display = 'none';
            }
        });
    }

    function actualizarDiasHabitaciones(diaInicio, diasVisibles) {
        const habitacionRows = document.querySelectorAll('.table-body, .table-footer');
        habitacionRows.forEach(row => {
            const dias = row.querySelectorAll('.dia');
            dias.forEach((dia, index) => {
                if (dia.textContent === 'Na' || dia.textContent === 'Error') {
                    dia.classList.add('na');
                } else {
                    dia.classList.remove('na');
                }

                if (index + 1 >= diaInicio && index + 1 < diaInicio + diasVisibles) {
                    dia.style.display = 'inline-block';
                } else {
                    dia.style.display = 'none';
                }
            });
        });
    }

    function configurarBotones() {
        const botonesMenos1 = document.querySelectorAll('#boton-menos1, #boton-menos1-footer');
        const botonesMas1 = document.querySelectorAll('#boton-mas1, #boton-mas1-footer');

        botonesMenos1.forEach(boton => {
            boton.addEventListener('click', function() {
                if (diaInicio > 1) {
                    diaInicio--;
                    mostrarDias();
                    actualizarDiasHabitaciones(diaInicio, diasVisibles);
                }
            });
        });

        botonesMas1.forEach(boton => {
            boton.addEventListener('click', function() {
                if (diaInicio < 30 - diasVisibles) {
                    diaInicio++;
                    mostrarDias();
                    actualizarDiasHabitaciones(diaInicio, diasVisibles);
                }
            });
        });
    }

    mostrarDias();
    actualizarDiasHabitaciones(diaInicio, diasVisibles);
    configurarBotones();
});
