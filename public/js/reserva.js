document.addEventListener('DOMContentLoaded', () => {

    // 1. VARIABLES GLOBALES (Deben ir primero)
    const calendarDays = document.getElementById('calendarDays');
    const currentMonthYear = document.getElementById('currentMonthYear');
    const timeSlotsGrid = document.getElementById('timeSlotsGrid');
    let currentViewDate = new Date();
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);

    // Feriados (YYYY-MM-DD)
    const feriados = [
        '2026-01-01',
        '2026-03-24',
        '2026-04-02',
        '2026-05-01',
        '2026-05-25',
        '2026-06-20',
        '2026-07-09',
        '2026-12-08',
        '2026-12-25'
    ];


    function esFechaHabilitada(fecha) {

        // Normalizar
        const f = new Date(fecha);
        f.setHours(0, 0, 0, 0);

        // Pasadas
        if (f < hoy) return false;

        // Sábado o domingo
        const diaSemana = f.getDay(); // 0=domingo 6=sábado

        if (diaSemana === 0 || diaSemana === 6)
            return false;

        // Feriado
        const fechaTexto =
            `${f.getFullYear()}-${String(f.getMonth() + 1).padStart(2, '0')
            }-${String(f.getDate()).padStart(2, '0')
            }`;

        if (feriados.includes(fechaTexto))
            return false;

        return true;
    }

    // 2. DEFINICIÓN DE FUNCIONES (Deben ir antes de llamarlas)
    const renderCalendar = () => {
        calendarDays.innerHTML = '';
        const month = currentViewDate.getMonth();
        const year = currentViewDate.getFullYear();
        currentMonthYear.innerText = new Intl.DateTimeFormat('es-ES', { month: 'long', year: 'numeric' }).format(currentViewDate);

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) calendarDays.appendChild(document.createElement('div'));

        for (let day = 1; day <= daysInMonth; day++) {

            const fechaDia = new Date(year, month, day);
            fechaDia.setHours(0, 0, 0, 0);

            const dayDiv = document.createElement('div');
            dayDiv.classList.add('calendar-day');
            dayDiv.innerText = day;

            // Bloquear fechas anteriores a hoy
            if (!esFechaHabilitada(fechaDia)) {

                dayDiv.classList.add('disabled');

            } else {
                dayDiv.addEventListener('click', () => {
                    // 1. Limpiar selección
                    document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
                    dayDiv.classList.add('selected');

                    // 2. DEFINIR LAS VARIABLES AQUÍ ADENTRO (Scope correcto)
                    const mesFormateado = String(month + 1).padStart(2, '0');
                    const diaFormateado = String(day).padStart(2, '0');
                    const fechaString = `${year}-${mesFormateado}-${diaFormateado}`;

                    // 3. Limpiar valores previos
                    const inputHorario = document.getElementById('id_horario');
                    const inputFecha = document.getElementById('fecha_reserva');

                    if (inputHorario) inputHorario.value = "";
                    if (inputFecha) inputFecha.value = fechaString;

                    // 4. Cargar nuevos horarios
                    renderTimeSlots(fechaString);
                });
            }

            calendarDays.appendChild(dayDiv);
        }
    };

    const renderTimeSlots = async (fechaFormateada) => {
        try {
            const response = await fetch(`${URLAPP}/reserva/obtenerHorariosOcupados/${fechaFormateada}`);
            const ocupados = await response.json();

            timeSlotsGrid.innerHTML = '';

            todosLosHorarios.forEach(h => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.classList.add('time-slot');
                btn.innerText = `${h.hora_inicio.substring(0, 5)} - ${h.hora_fin.substring(0, 5)}`;

                if (ocupados.includes(parseInt(h.id_horario))) {
                    btn.classList.add('reserved');
                    btn.disabled = true;
                } else {
                    btn.addEventListener('click', () => {
                        // UI selection
                        document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                        btn.classList.add('selected');

                        // GUARDAR VALORES
                        document.getElementById('id_horario').value = h.id_horario;

                        // Guardamos la fecha también (fechaFormateada viene del argumento de la función)
                        document.getElementById('fecha_reserva').value = fechaFormateada;
                    });
                }
                timeSlotsGrid.appendChild(btn);
            });
        } catch (e) {
            console.error("Error al cargar horarios:", e);
        }
    };

    // 3. EVENTOS
    document.getElementById('prevMonth').addEventListener('click', () => {

        const nuevoMes = new Date(currentViewDate);
        nuevoMes.setMonth(nuevoMes.getMonth() - 1);

        // Primer día del mes actual
        const limite = new Date(hoy.getFullYear(), hoy.getMonth(), 1);

        // Primer día del mes al que quiero ir
        const destino = new Date(
            nuevoMes.getFullYear(),
            nuevoMes.getMonth(),
            1
        );

        if (destino < limite)
            return;

        currentViewDate = nuevoMes;
        renderCalendar();

    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currentViewDate.setMonth(currentViewDate.getMonth() + 1);
        renderCalendar();
    });

    // --- 1. LÓGICA DE PROPÓSITO ---
    const buttons = document.querySelectorAll('.btn-purpose');
    const formContainer = document.getElementById('formContainer');
    const fileContainer = document.getElementById('fileContainer');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Resetear visibilidad
            if (formContainer) formContainer.style.display = 'none';
            if (fileContainer) fileContainer.style.display = 'none';

            // Mostrar el correcto
            if (btn.innerText.includes('formulario')) {
                if (formContainer) formContainer.style.display = 'block';
            } else if (btn.innerText.includes('Subir')) {
                if (fileContainer) fileContainer.style.display = 'block';
            }
        });
    });

    // 4. INICIALIZACIÓN
    renderCalendar();


    const prevBtn = document.getElementById('prevMonth');

    const esMesActual =
        currentViewDate.getMonth() === hoy.getMonth() &&
        currentViewDate.getFullYear() === hoy.getFullYear();

    prevBtn.disabled = esMesActual;

    // LÓGICA DE EDICIÓN
    const idReservaInput = document.querySelector('input[name="id_reserva"]');
    if (idReservaInput && idReservaInput.value !== "") {
        // --- LÓGICA CORREGIDA PARA EDICIÓN ---
        const idReservaInput = document.querySelector('input[name="id_reserva"]');

        if (idReservaInput && idReservaInput.value !== "") {
            // 1. Mostrar contenedor y marcar botón activo
            const formContainer = document.getElementById('formContainer');
            if (formContainer) formContainer.style.display = 'block';

            const btnPurpose = document.querySelector('.btn-purpose');
            if (btnPurpose) btnPurpose.classList.add('active');

            // 2. Cargar fecha guardada correctamente (evitando problemas de zona horaria)
            const fechaGuardada = document.getElementById('fecha_reserva').value; // "2026-06-10"
            if (fechaGuardada) {
                const [y, m, d] = fechaGuardada.split('-').map(Number);
                currentViewDate = new Date(y, m - 1, d);
                renderCalendar();

                // 3. Seleccionar el día en la UI
                setTimeout(() => {
                    const dias = document.querySelectorAll('.calendar-day');
                    dias.forEach(dayEl => {
                        if (parseInt(dayEl.innerText) === d && !dayEl.classList.contains('disabled')) {
                            dayEl.classList.add('selected');
                            // No disparamos el click aquí para evitar bucles, 
                            // forzamos la carga de horarios directamente
                            renderTimeSlots(fechaGuardada);
                        }
                    });
                }, 100);
            }
        }
    }



    $(document).ready(function() {
    $('#palabras_clave').select2({
        width: '100%'
    }
    );
});
});