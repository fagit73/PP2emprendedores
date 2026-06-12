document.addEventListener('DOMContentLoaded', () => {
    // --- ELEMENTOS DEL DOM ---
    const optionCards = document.querySelectorAll('.option-card');
    const btnFormulario = document.getElementById('btnFormulario');
    const btnSubirArchivo = document.getElementById('btnSubirArchivo');
    const btnSaltarPaso = document.getElementById('btnSaltarPaso');
    const formContainer = document.getElementById('formContainer');
    const fileContainer = document.getElementById('fileContainer');
    
    // Calendario
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    const currentMonthYear = document.getElementById('currentMonthYear');
    const calendarDays = document.getElementById('calendarDays');
    const timeSlotsContainer = document.getElementById('timeSlotsContainer');
    const timeSlotsGrid = document.getElementById('timeSlotsGrid');
    const selectedDateText = document.getElementById('selectedDateText');

    // --- VARIABLES DE ESTADO ---
    let tipoSeleccionado = null;
    let propositoSeleccionado = null; // 'formulario', 'archivo', 'saltar'
    let fechaSeleccionada = null; // Objeto Date
    let currentViewDate = new Date(); // Fecha para la vista del calendario
    const añoEnCurso = new Date().getFullYear();

    // Franjas horarias (mismas que en la versión original)
    const franjasHorarias = [
        "7:30 - 8:30", "8:30 - 9:30", "9:40 - 10:40", "10:50 - 11:50",
        "11:50 - 12:30", "13:00 - 14:00", "14:00 - 15:00", "15:10 - 16:10",
        "16:20 - 17:20", "17:20 - 18:20", "18:20 - 19:20", "19:30 - 20:30",
        "20:30 - 21:30", "21:30 - 22:30"
    ];

    // --- 1. SELECCIÓN DE CATEGORÍA (Mantenido de original) ---
    optionCards.forEach(card => {
        card.addEventListener('click', () => {
            optionCards.forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            tipoSeleccionado = card.getAttribute('data-type');
        });
    });

    // --- 2. LÓGICA DE PROPÓSITO (Instrucción 3.1) ---
    const resetPropositoUI = () => {
        [btnFormulario, btnSubirArchivo, btnSaltarPaso].forEach(btn => btn.classList.remove('active'));
        formContainer.style.display = 'none';
        fileContainer.style.display = 'none';
    };

    btnFormulario.addEventListener('click', () => {
        resetPropositoUI();
        btnFormulario.classList.add('active');
        formContainer.style.display = 'block';
        propositoSeleccionado = 'formulario';
    });

    btnSubirArchivo.addEventListener('click', () => {
        resetPropositoUI();
        btnSubirArchivo.classList.add('active');
        fileContainer.style.display = 'block';
        propositoSeleccionado = 'archivo';
    });

    btnSaltarPaso.addEventListener('click', () => {
        resetPropositoUI();
        btnSaltarPaso.classList.add('active');
        propositoSeleccionado = 'saltar';
    });

    // --- 3. LÓGICA DEL CALENDARIO (Instrucción 4) ---
    const renderCalendar = () => {
        calendarDays.innerHTML = '';
        const month = currentViewDate.getMonth();
        const year = currentViewDate.getFullYear();

        currentMonthYear.innerText = new Intl.DateTimeFormat('es-ES', { month: 'long', year: 'numeric' }).format(currentViewDate);

        const firstDayOfMonth = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Espacios vacíos para días del mes anterior
        for (let i = 0; i < firstDayOfMonth; i++) {
            const emptyDiv = document.createElement('div');
            emptyDiv.classList.add('calendar-day', 'empty');
            calendarDays.appendChild(emptyDiv);
        }

        // Días del mes
        for (let day = 1; day <= daysInMonth; day++) {
            const dayDiv = document.createElement('div');
            dayDiv.classList.add('calendar-day');
            dayDiv.innerText = day;

            const dateObj = new Date(year, month, day);
            const today = new Date();
            today.setHours(0,0,0,0);

            // Resaltar hoy
            if (dateObj.getTime() === today.getTime()) dayDiv.classList.add('today');

            // CAMBIO: Limitar al año en curso, evitar fechas pasadas y deshabilitar fines de semana (Sáb=6, Dom=0)
            const dayOfWeek = dateObj.getDay();
            const esFinDeSemana = (dayOfWeek === 0 || dayOfWeek === 6);

            if (year !== añoEnCurso || dateObj < today || esFinDeSemana) {
                dayDiv.classList.add('disabled');
                if (esFinDeSemana) dayDiv.title = "Fines de semana no disponibles";
            } else {
                dayDiv.addEventListener('click', () => selectDate(dateObj, dayDiv));
            }

            // Mantener selección si se navega entre meses
            if (fechaSeleccionada && dateObj.getTime() === fechaSeleccionada.getTime()) {
                dayDiv.classList.add('active');
            }

            calendarDays.appendChild(dayDiv);
        }
    };

    const selectDate = (date, element) => {
        // UI de selección
        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('active'));
        element.classList.add('active');
        
        fechaSeleccionada = date;
        const formattedDate = date.toLocaleDateString('es-ES');
        selectedDateText.innerText = `Horarios para el: ${formattedDate}`;
        
        // Mostrar grilla de horarios (CAMBIO: Manejo de panel lateral)
        renderTimeSlots(formattedDate);
        timeSlotsContainer.style.display = 'block';
        document.getElementById('noDateSelected').style.display = 'none';
    };

    const renderTimeSlots = (fechaStr) => {
        timeSlotsGrid.innerHTML = '';
        const reservas = JSON.parse(localStorage.getItem('mis_reservas')) || [];
        
        franjasHorarias.forEach(horario => {
            const slot = document.createElement('div');
            slot.classList.add('time-slot');
            slot.innerText = horario;

            // Verificar si ya está reservado para esa fecha y horario
            const estaReservado = reservas.some(r => r.fecha === fechaStr && r.horario === horario);

            if (estaReservado) {
                slot.classList.add('reserved');
                slot.title = "Ya reservado";
            } else {
                slot.addEventListener('click', () => confirmarReserva(horario, fechaStr));
            }

            timeSlotsGrid.appendChild(slot);
        });
    };

    // Navegación del calendario
    prevMonthBtn.addEventListener('click', () => {
        if (currentViewDate.getMonth() === 0 && currentViewDate.getFullYear() === añoEnCurso) return; // No salir del año en curso hacia atrás
        currentViewDate.setMonth(currentViewDate.getMonth() - 1);
        renderCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        if (currentViewDate.getMonth() === 11 && currentViewDate.getFullYear() === añoEnCurso) return; // No salir del año en curso hacia adelante
        currentViewDate.setMonth(currentViewDate.getMonth() + 1);
        renderCalendar();
    });

    // --- CAMBIO: Cargar palabras clave para autocompletado ---
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

    // --- CAMBIO: Gestión de cambio de archivo ---
    const fileInput = document.getElementById('archivoTurno');
    const fileStatus = document.getElementById('fileStatus');
    const selectedFileName = document.getElementById('selectedFileName');
    
    if (fileInput) {
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                selectedFileName.innerText = fileInput.files[0].name;
                fileStatus.style.display = 'block';
            } else {
                fileStatus.style.display = 'none';
            }
        });
    }

    // --- 4. CONFIRMACIÓN Y GUARDADO ---
    const confirmarReserva = (horario, fechaStr) => {
        if (!tipoSeleccionado) {
            Swal.fire({ icon: 'warning', title: 'Selección requerida', text: 'Por favor, selecciona un Tipo de Reserva primero.', confirmButtonColor: '#1a3a8a' });
            return;
        }

        if (!propositoSeleccionado) {
            Swal.fire({ icon: 'warning', title: 'Falta propósito', text: 'Por favor, elige cómo quieres detallar el propósito de tu turno.', confirmButtonColor: '#1a3a8a' });
            return;
        }

        // Recopilar datos según el propósito
        let datosProposito = {};
        if (propositoSeleccionado === 'formulario') {
            datosProposito = {
                titulo: document.getElementById('formTitulo').value,
                docente: document.getElementById('formDocente').value, // CAMBIO: Nuevo campo
                inicio: document.getElementById('formFechaInicio').value,
                fin: document.getElementById('formFechaFin').value,
                descripcion: document.getElementById('formDescripcion').value,
                evaluacion: document.getElementById('formEvaluacion').value,
                keywords: document.getElementById('formPalabrasClave').value
            };
            if (!datosProposito.titulo) {
                Swal.fire({ icon: 'error', title: 'Datos incompletos', text: 'Por favor completa al menos el título del formulario.', confirmButtonColor: '#1a3a8a' });
                return;
            }
        } else if (propositoSeleccionado === 'archivo') {
            if (fileInput.files.length === 0) {
                Swal.fire({ icon: 'error', title: 'Archivo faltante', text: 'Por favor selecciona un archivo para subir.', confirmButtonColor: '#1a3a8a' });
                return;
            }
            datosProposito = { archivo: fileInput.files[0].name };
        }

        Swal.fire({
            title: '¿Confirmar Reserva?',
            html: `
                <div style="text-align: left; font-size: 0.9rem;">
                    <p><strong>Tipo:</strong> ${tipoSeleccionado.toUpperCase()}</p>
                    <p><strong>Fecha:</strong> ${fechaStr}</p>
                    <p><strong>Horario:</strong> ${horario}</p>
                    <p><strong>Modo:</strong> ${propositoSeleccionado}</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1a3a8a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const nuevaReserva = {
                    id: Date.now(),
                    tipo: tipoSeleccionado,
                    propositoModo: propositoSeleccionado,
                    detalles: datosProposito,
                    fecha: fechaStr,
                    horario: horario,
                    // Mantenemos campos originales por compatibilidad si se usan en otras pantallas
                    motivo: propositoSeleccionado === 'saltar' ? 'Sin datos' : (datosProposito.titulo || datosProposito.archivo || 'Detallado'),
                    dia: new Intl.DateTimeFormat('es-ES', { weekday: 'long' }).format(fechaSeleccionada)
                };

                let reservasActuales = JSON.parse(localStorage.getItem('mis_reservas')) || [];
                reservasActuales.push(nuevaReserva);
                localStorage.setItem('mis_reservas', JSON.stringify(reservasActuales));

                Swal.fire({ icon: 'success', title: '¡Reserva Exitosa!', text: `Registrado para el ${fechaStr} a las ${horario}`, timer: 2000, showConfirmButton: false })
                .then(() => {
                    window.location.href = 'reservas.html';
                });
            }
        });
    };

    // Inicializar
    renderCalendar();
});
