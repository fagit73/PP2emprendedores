document.addEventListener('DOMContentLoaded', () => {
    const cells = document.querySelectorAll('.schedule-table td:not(.time-col)');
    const optionCards = document.querySelectorAll('.option-card');
    const inputMotivo = document.getElementById('motivo');

    let tipoSeleccionado = null;

    // --- NUEVO: CARGAR ESTADO INICIAL DE LA TABLA ---
    // Esto marca las celdas como reservadas si ya existen en el LocalStorage
    const cargarReservasPrevias = () => {
        const reservas = JSON.parse(localStorage.getItem('mis_reservas')) || [];
        const dias = ["Horario", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];

        cells.forEach(cell => {
            const horario = cell.parentElement.querySelector('.time-col').innerText;
            const nombreDia = dias[cell.cellIndex];

            // Buscamos si esta celda específica (día y hora) ya está en nuestro storage
            const yaReservado = reservas.find(r => r.dia === nombreDia && r.horario === horario);

            if (yaReservado) {
                cell.classList.add(`bg-${yaReservado.tipo}`);
                cell.innerHTML = `
                    <span class="reserved-text">RESERVADO</span>
                    <small style="font-size: 0.6rem;">${yaReservado.tipo}</small>
                `;
            }
        });
    };

    cargarReservasPrevias(); // Ejecutamos al entrar a la página

    // 1. Manejo de Selección de Categoría
    optionCards.forEach(card => {
        card.addEventListener('click', () => {
            optionCards.forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            tipoSeleccionado = card.getAttribute('data-type');
        });
    });

    // 2. Manejo de Clic en la Tabla
    cells.forEach(cell => {
        cell.addEventListener('click', () => {
            const motivoValue = inputMotivo.value.trim();

            // Validación 1: Tipo de Reserva
            if (!tipoSeleccionado) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Selección requerida',
                    text: 'Por favor, selecciona un Tipo de Reserva primero.',
                    confirmButtonColor: '#1a3a8a'
                });
                return;
            }

            // Validación 2: Motivo vacío
            if (motivoValue === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Falta el motivo',
                    text: 'Debes contarnos brevemente para qué necesitas el turno.',
                    confirmButtonColor: '#1a3a8a',
                    didClose: () => inputMotivo.focus()
                });
                return;
            }

            // Validación 3: Ya reservado (basado en clases CSS)
            if (cell.classList.contains('bg-lectura') || 
                cell.classList.contains('bg-audiovisual') || 
                cell.classList.contains('bg-extension')) {
                Swal.fire({
                    icon: 'info',
                    title: 'No disponible',
                    text: 'Este horario ya se encuentra reservado.',
                    confirmButtonColor: '#1a3a8a'
                });
                return;
            }

            // 3. Confirmación de Reserva
            Swal.fire({
                title: '¿Confirmar reserva?',
                text: `Vas a reservar para: ${tipoSeleccionado.toUpperCase()}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1a3a8a',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, reservar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    // --- LÓGICA DE PERSISTENCIA ---
                    const horario = cell.parentElement.querySelector('.time-col').innerText;
                    const columnIndex = cell.cellIndex;
                    const dias = ["Horario", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
                    const nombreDia = dias[columnIndex];

                    const nuevaReserva = {
                        id: Date.now(),
                        tipo: tipoSeleccionado,
                        motivo: motivoValue,
                        dia: nombreDia,
                        horario: horario
                    };

                    let reservasActuales = JSON.parse(localStorage.getItem('mis_reservas')) || [];
                    reservasActuales.push(nuevaReserva);
                    localStorage.setItem('mis_reservas', JSON.stringify(reservasActuales));

                    // --- ACTUALIZACIÓN VISUAL ---
                    const claseColor = `bg-${tipoSeleccionado}`;
                    cell.classList.add(claseColor);
                    cell.innerHTML = `
                        <span class="reserved-text">RESERVADO</span>
                        <small style="font-size: 0.6rem;">${tipoSeleccionado}</small>
                    `;
                    
                    // Limpieza
                    inputMotivo.value = "";
                    tipoSeleccionado = null;
                    optionCards.forEach(c => c.classList.remove('active'));

                    Swal.fire({
                        icon: 'success',
                        title: '¡Reserva Exitosa!',
                        text: 'El turno ha sido guardado. Puedes verlo en "Mis Reservas".',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        });
    });
});