document.addEventListener('DOMContentLoaded', () => {
    // 1. Obtener el ID de la reserva desde la URL
    const urlParams = new URLSearchParams(window.location.search);
    const idReserva = parseInt(urlParams.get('id'));

    // 2. Traer todas las reservas del localStorage
    const reservas = JSON.parse(localStorage.getItem('mis_reservas')) || [];

    // 3. Buscar la reserva específica por su ID
    const reservaEncontrada = reservas.find(r => r.id === idReserva);

    // 4. Si existe, poner los datos en los elementos HTML
    if (reservaEncontrada) {
        document.getElementById('current-dia').textContent = reservaEncontrada.dia;
        document.getElementById('current-horario').textContent = reservaEncontrada.horario;
        document.getElementById('current-motivo').textContent = reservaEncontrada.motivo || reservaEncontrada.proposito;
        
        const inputMotivo = document.getElementById('input-motivo');
        if(inputMotivo) {
            inputMotivo.value = reservaEncontrada.motivo || reservaEncontrada.proposito;
        }
    } else {
        console.error("No se encontró la reserva con ID:", idReserva);
        alert("Error: No se pudo cargar la información de la reserva.");
        window.location.href = 'reservas.html';
        return; 
    }

    // --- LÓGICA PARA GUARDAR CAMBIOS (UNIFICADA CON IMAGEN_4) ---
    const btnGuardar = document.getElementById('btn-guardar');
    if (btnGuardar) {
        btnGuardar.addEventListener('click', () => {
            const nuevoDia = document.getElementById('select-dia').value;
            const nuevoHorario = document.getElementById('select-horario').value;
            const nuevoMotivo = document.getElementById('input-motivo').value;

            // Determinamos el motivo para mostrar en la alerta (nuevo o antiguo)
            const motivoAMostrar = (nuevoMotivo || reservaEncontrada.motivo || reservaEncontrada.proposito || "");

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Confirmar reserva?',
                    text: `Vas a reservar para: ${motivoAMostrar.toUpperCase()}`,
                    icon: 'question',
                    iconColor: '#a5dc86', 
                    showCancelButton: true,
                    confirmButtonText: 'Sí, reservar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#1a3a8a', // Azul oscuro de imagen_4
                    cancelButtonColor: '#6e7d88',  // Gris azulado de imagen_4
                    reverseButtons: true,          // Confirmar a la izquierda, Cancelar a la derecha
                    focusConfirm: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        ejecutarGuardado(nuevoDia, nuevoHorario, nuevoMotivo);
                    }
                });
            } else {
                if (confirm("¿Confirmar reserva?")) {
                    ejecutarGuardado(nuevoDia, nuevoHorario, nuevoMotivo);
                }
            }
        });
    }

    // Función auxiliar para actualizar LocalStorage
    function ejecutarGuardado(dia, horario, motivo) {
        let lista = JSON.parse(localStorage.getItem('mis_reservas')) || [];
        const idx = lista.findIndex(r => r.id === idReserva);
        if (idx !== -1) {
            if (dia !== "") lista[idx].dia = dia;
            if (horario !== "") lista[idx].horario = horario;
            lista[idx].motivo = motivo;
            localStorage.setItem('mis_reservas', JSON.stringify(lista));
            window.location.href = 'reservas.html';
        }
    }

    // --- LÓGICA PARA CANCELAR (ELIMINAR) RESERVA ---
    const btnCancelar = document.getElementById('btn-cancelar-reserva');
    if (btnCancelar) {
        btnCancelar.addEventListener('click', () => {
            const ejecutarCancelacion = () => {
                let reservasActuales = JSON.parse(localStorage.getItem('mis_reservas')) || [];
                const nuevasReservas = reservasActuales.filter(r => r.id !== idReserva);
                localStorage.setItem('mis_reservas', JSON.stringify(nuevasReservas));
                window.location.href = 'reservas.html';
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Eliminar reserva?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    iconColor: '#ef4444',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Volver',
                    confirmButtonColor: '#ef4444', 
                    cancelButtonColor: '#6e7d88',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        ejecutarCancelacion();
                    }
                });
            } else {
                if (confirm("¿Estás seguro de que deseas eliminar esta reserva?")) {
                    ejecutarCancelacion();
                }
            }
        });
    }
});

// Función global para redireccionar
window.irAEditar = function(id) {
    window.location.href = `editar_reserva.html?id=${id}`;
};