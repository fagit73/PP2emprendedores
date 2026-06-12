document.addEventListener('DOMContentLoaded', () => {
    // --- 1. CARGAR DATOS DE LA RESERVA ACTUAL ---
    const urlParams = new URLSearchParams(window.location.search);
    const idReserva = parseInt(urlParams.get('id'));
    const reservas = JSON.parse(localStorage.getItem('mis_reservas')) || [];
    const reservaEncontrada = reservas.find(r => r.id === idReserva);

    if (reservaEncontrada) {
        document.getElementById('current-dia').textContent = reservaEncontrada.dia;
        document.getElementById('current-horario').textContent = reservaEncontrada.horario;
        document.getElementById('current-fecha').textContent = reservaEncontrada.fecha || 'Sin fecha';
        document.getElementById('current-motivo').textContent = reservaEncontrada.motivo || 'Sin motivo';
        document.getElementById('input-motivo').value = reservaEncontrada.motivo || '';

        // CAMBIO: Mostrar Docente si existe en la reserva actual
        if (reservaEncontrada.detalles && reservaEncontrada.detalles.docente) {
            document.getElementById('current-docente-wrapper').style.display = 'block';
            document.getElementById('current-docente').textContent = reservaEncontrada.detalles.docente;
        }

        // CAMBIO: Preparar campos de edición según el modo
        if (reservaEncontrada.propositoModo === 'formulario') {
            document.getElementById('editFormFields').style.display = 'block';
            document.getElementById('labelMotivo').innerText = "Editar Título del Proyecto";
            if (reservaEncontrada.detalles) {
                document.getElementById('editDocente').value = reservaEncontrada.detalles.docente || '';
                document.getElementById('editPalabrasClave').value = reservaEncontrada.detalles.keywords || '';
            }
        } else if (reservaEncontrada.propositoModo === 'archivo') {
            document.getElementById('editFileFields').style.display = 'block';
            document.getElementById('labelMotivo').innerText = "Editar Descripción / Nota";
            if (reservaEncontrada.detalles && reservaEncontrada.detalles.archivo) {
                document.getElementById('editFileStatus').innerText = `Archivo actual: ${reservaEncontrada.detalles.archivo}`;
            }
        }
    } else {
        window.location.href = 'reservas.html';
        return; 
    }

    // --- CAMBIO: Cargar palabras clave para autocompletado (Misma lista que script.js) ---
    const keywords = [
        "Ciencias Sociales", "Educación Artistica", "Educación Física", "Ingles", "Matemática", 
        "Prácticas del Lenguaje", "Construcción Ciudadana", "Procedimientos Técnicos", "Sistemas Tecnológicos", 
        "Lenguajes Tecnológicos", "Biología", "Fisico Química", "Geografía", "Historia", 
        "Salud y Adolescencia", "Matemática Ciclo Superior", "Física", "Química", 
        "Tecnologías Electrónicas", "Política y Ciudadanía", "Análisis Matemático", "Sistemas Digitales", 
        "Bases de Datos", "Modelos y Sistemas", "Laboratorio de Programación (LPR)", 
        "Laboratorio de Diseño de Bases de Datos (LDB)", "Laboratorio de Sistemas Operativos (LSO)", 
        "Laboratorio de Hardware (LHW)", "Laboratorio de Aplicaciones (LAP)", 
        "Laboratorio de Redes Informáticas (LRI)", "Laboratorio de Diseño Web (LDW)", "Filosofía", "Arte", 
        "Matemática Discreta", "Sistemas de Gestión y Autogestión", "Seguridad Informática", 
        "Derechos del Trabajo", "Laboratorio de Procesos Indrustriales", "Desarrollo de Aplicaciones Web Estáticas", 
        "Desarrollo de Aplicaciones Web Dinámicas", "Prácticas Profesionalizantes", 
        "Emprendimientos Productios y Desarrollo Local", "Evaluación de Proyectos", "Organización y Metodos", 
        "Proyecto, Diseño e Implementación de Sistemas Computacionales", 
        "Proyecto de Desarrollo de Software para Plataformas Móviles", 
        "Proyecto de Diseño e Implementación de Sitios Web Dinámicos", "Técnico en Programación", 
        "Teleinformática", "Investigación Operativa", 
        "Instalación, Mantenimiento y Reparación de Sistemas Computacionales", 
        "Instalación, Mantenimiento y Reparación de Redes Informáticas", 
        "Técnico en Informática Personal y Profesional (IPP)", "Comercialización", 
        "Introcucción a las Organizaciones", "Derecho", "Organización Industrial", "Costos", 
        "Teoria de las Organizaciones", "Tecnologías de la Información de la Gestión", "Gestión Comercial", 
        "Administracion y Gestión de Recursos Humanos", "Sistemas de Información Contable", 
        "Gestión de la Producción", "Planeamiento", "Economía", "Gestión Financieran, Bancaria y Seguros", 
        "Teoría y Técnica Impositiva", "Economíay Desarrollo Sustentable", "Auditoria y Control de Gestión", 
        "Producción y Comercio Exterior", "Evaluación y Gestión de Proyectos", 
        "Capital Humano Y Relaciones Laborales", "Análisis e Interpretación de los Estados Contables", 
        "Técnico en Administración de las Organizaciones (ADO)", "Centro de Estudiantes", "Capacitación", 
        "Torneo de Ajedrez", "Fechas Patrias", "Obra de Teatro", "Taller Literario", "Proyección Audiovisual"
    ];
    
    const datalist = document.getElementById('keywordsList');
    if (datalist) {
        keywords.forEach(kw => {
            const option = document.createElement('option');
            option.value = kw;
            datalist.appendChild(option);
        });
    }

    // --- 2. LÓGICA DEL CALENDARIO ---
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    const currentMonthYear = document.getElementById('currentMonthYear');
    const calendarDays = document.getElementById('calendarDays');
    const timeSlotsContainer = document.getElementById('timeSlotsContainer');
    const timeSlotsGrid = document.getElementById('timeSlotsGrid');
    const selectedDateText = document.getElementById('selectedDateText');
    const inputFecha = document.getElementById('input-fecha');
    const selectHorario = document.getElementById('select-horario');
    const selectDia = document.getElementById('select-dia');

    let fechaSeleccionada = null;
    let currentViewDate = new Date();
    const añoEnCurso = new Date().getFullYear();

    const franjasHorarias = [
        "7:30 - 8:30", "8:30 - 9:30", "9:40 - 10:40", "10:50 - 11:50",
        "11:50 - 12:30", "13:00 - 14:00", "14:00 - 15:00", "15:10 - 16:10",
        "16:20 - 17:20", "17:20 - 18:20", "18:20 - 19:20", "19:30 - 20:30",
        "20:30 - 21:30", "21:30 - 22:30"
    ];

    const renderCalendar = () => {
        calendarDays.innerHTML = '';
        const month = currentViewDate.getMonth();
        const year = currentViewDate.getFullYear();
        currentMonthYear.innerText = new Intl.DateTimeFormat('es-ES', { month: 'long', year: 'numeric' }).format(currentViewDate);

        const firstDayOfMonth = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        for (let i = 0; i < firstDayOfMonth; i++) {
            const emptyDiv = document.createElement('div');
            emptyDiv.classList.add('calendar-day', 'empty');
            calendarDays.appendChild(emptyDiv);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayDiv = document.createElement('div');
            dayDiv.classList.add('calendar-day');
            dayDiv.innerText = day;
            const dateObj = new Date(year, month, day);
            const today = new Date();
            today.setHours(0,0,0,0);

            const dayOfWeek = dateObj.getDay();
            const esFinDeSemana = (dayOfWeek === 0 || dayOfWeek === 6);

            if (year !== añoEnCurso || dateObj < today || esFinDeSemana) {
                dayDiv.classList.add('disabled');
            } else {
                dayDiv.addEventListener('click', () => selectDate(dateObj, dayDiv));
            }
            if (fechaSeleccionada && dateObj.getTime() === fechaSeleccionada.getTime()) {
                dayDiv.classList.add('active');
            }
            calendarDays.appendChild(dayDiv);
        }
    };

    const selectDate = (date, element) => {
        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('active'));
        element.classList.add('active');
        fechaSeleccionada = date;
        const formattedDate = date.toLocaleDateString('es-ES');
        selectedDateText.innerText = `Horarios para el: ${formattedDate}`;
        inputFecha.value = formattedDate;
        selectDia.value = new Intl.DateTimeFormat('es-ES', { weekday: 'long' }).format(date);
        
        renderTimeSlots(formattedDate);
        timeSlotsContainer.style.display = 'block';
        document.getElementById('noDateSelected').style.display = 'none';
    };

    const renderTimeSlots = (fechaStr) => {
        timeSlotsGrid.innerHTML = '';
        const todasLasReservas = JSON.parse(localStorage.getItem('mis_reservas')) || [];
        
        franjasHorarias.forEach(horario => {
            const slot = document.createElement('div');
            slot.classList.add('time-slot');
            slot.innerText = horario;

            const estaReservado = todasLasReservas.some(r => r.fecha === fechaStr && r.horario === horario && r.id !== idReserva);

            if (estaReservado) {
                slot.classList.add('reserved');
            } else {
                slot.addEventListener('click', () => {
                    document.querySelectorAll('.time-slot').forEach(s => s.style.background = '');
                    slot.style.background = '#f97316';
                    slot.style.color = 'white';
                    selectHorario.value = horario;
                });
            }
            timeSlotsGrid.appendChild(slot);
        });
    };

    prevMonthBtn.addEventListener('click', () => {
        if (currentViewDate.getMonth() === 0 && currentViewDate.getFullYear() === añoEnCurso) return;
        currentViewDate.setMonth(currentViewDate.getMonth() - 1);
        renderCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        if (currentViewDate.getMonth() === 11 && currentViewDate.getFullYear() === añoEnCurso) return;
        currentViewDate.setMonth(currentViewDate.getMonth() + 1);
        renderCalendar();
    });

    // --- 3. GUARDAR CAMBIOS ---
    document.getElementById('btn-guardar').addEventListener('click', () => {
        const nuevaFecha = inputFecha.value || reservaEncontrada.fecha;
        const nuevoHorario = selectHorario.value || reservaEncontrada.horario;
        const nuevoDia = selectDia.value || reservaEncontrada.dia;
        const nuevoMotivo = document.getElementById('input-motivo').value;

        Swal.fire({
            title: '¿Guardar cambios?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1a3a8a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let lista = JSON.parse(localStorage.getItem('mis_reservas')) || [];
                const idx = lista.findIndex(r => r.id === idReserva);
                if (idx !== -1) {
                    lista[idx].fecha = nuevaFecha;
                    lista[idx].horario = nuevoHorario;
                    lista[idx].dia = nuevoDia;
                    lista[idx].motivo = nuevoMotivo;

                    // CAMBIO: Actualizar detalles según el modo
                    if (lista[idx].propositoModo === 'formulario') {
                        lista[idx].detalles.titulo = nuevoMotivo;
                        lista[idx].detalles.docente = document.getElementById('editDocente').value;
                        lista[idx].detalles.keywords = document.getElementById('editPalabrasClave').value;
                    } else if (lista[idx].propositoModo === 'archivo') {
                        const fileInput = document.getElementById('editArchivo');
                        if (fileInput.files.length > 0) {
                            lista[idx].detalles.archivo = fileInput.files[0].name;
                        }
                    }

                    localStorage.setItem('mis_reservas', JSON.stringify(lista));
                    window.location.href = 'reservas.html';
                }
            }
        });
    });

    // --- 4. ELIMINAR RESERVA ---
    document.getElementById('btn-cancelar-reserva').addEventListener('click', () => {
        Swal.fire({
            title: '¿Eliminar reserva?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let lista = JSON.parse(localStorage.getItem('mis_reservas')) || [];
                const nuevaLista = lista.filter(r => r.id !== idReserva);
                localStorage.setItem('mis_reservas', JSON.stringify(nuevaLista));
                window.location.href = 'reservas.html';
            }
        });
    });

    renderCalendar();
});
