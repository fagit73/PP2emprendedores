document.addEventListener('DOMContentLoaded', () => {
    // --- 1. LÓGICA DE PROPÓSITO ---
    const buttons = document.querySelectorAll('.btn-purpose');
    const formContainer = document.getElementById('formContainer');
    const fileContainer = document.getElementById('fileContainer');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            if (formContainer) formContainer.style.display = 'none';
            if (fileContainer) fileContainer.style.display = 'none';
            if (btn.innerText.includes('formulario')) {
                if (formContainer) formContainer.style.display = 'block';
            } else if (btn.innerText.includes('Subir')) {
                if (fileContainer) fileContainer.style.display = 'block';
            }
        });
    });

    // --- 2. LÓGICA DEL CALENDARIO ---
    const calendarDays = document.getElementById('calendarDays');
    const currentMonthYear = document.getElementById('currentMonthYear');
    const timeSlotsGrid = document.getElementById('timeSlotsGrid');
    let currentViewDate = new Date();

    const renderCalendar = () => {
        calendarDays.innerHTML = '';
        const month = currentViewDate.getMonth();
        const year = currentViewDate.getFullYear();
        currentMonthYear.innerText = new Intl.DateTimeFormat('es-ES', { month: 'long', year: 'numeric' }).format(currentViewDate);

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) calendarDays.appendChild(document.createElement('div'));

        for (let day = 1; day <= daysInMonth; day++) {
            const dayDiv = document.createElement('div');
            dayDiv.classList.add('calendar-day');
            dayDiv.innerText = day;
            dayDiv.addEventListener('click', () => {
                document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
                dayDiv.classList.add('selected');

                // Formatear fecha para enviar al servidor: YYYY-MM-DD
                const mesFormateado = String(month + 1).padStart(2, '0');
                const diaFormateado = String(day).padStart(2, '0');
                renderTimeSlots(`${year}-${mesFormateado}-${diaFormateado}`);
            });
            calendarDays.appendChild(dayDiv);
        }
    };

    // --- 3. RENDERIZAR HORARIOS (Consumiendo la BD) ---
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

    document.getElementById('prevMonth').addEventListener('click', () => {
        currentViewDate.setMonth(currentViewDate.getMonth() - 1);
        renderCalendar();
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currentViewDate.setMonth(currentViewDate.getMonth() + 1);
        renderCalendar();
    });

    renderCalendar();


    const formReserva = document.getElementById('formReserva');

    formReserva.addEventListener('submit', (e) => {
        // Prevenir envío automático para validar
        e.preventDefault();

        // Obtener valores de inputs ocultos
        const idTipoUso = document.querySelector('input[name="id_tipo_uso"]:checked')?.value;
        const fechaReserva = document.getElementById('fecha_reserva').value;
        const idHorario = document.getElementById('id_horario').value;

        // Validaciones
        if (!idTipoUso) {
            Swal.fire({ icon: 'warning', title: 'Atención', text: 'Debes seleccionar un tipo de reserva.' });
            return;
        }
        if (!fechaReserva || !idHorario) {
            Swal.fire({ icon: 'warning', title: 'Atención', text: 'Debes seleccionar una fecha y un horario disponible.' });
            return;
        }

        // Si todo está ok, mostrar confirmación
        Swal.fire({
            title: '¿Confirmar reserva?',
            text: "Se guardará tu turno en el sistema.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Ahora sí enviamos el formulario físicamente
                formReserva.submit();
            }
        });
    });


});