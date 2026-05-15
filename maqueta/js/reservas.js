document.addEventListener('DOMContentLoaded', () => {
    const contenedor = document.getElementById('contenedor-listado');
    const reservas = JSON.parse(localStorage.getItem('mis_reservas')) || [];

    // Diccionarios de colores para el diseño dinámico
    const colorBorde = { lectura: '#f87171', audiovisual: '#60a5fa', extension: '#c084fc' };
    const colorFondo = { lectura: '#fff0f0', audiovisual: '#f0f6ff', extension: '#faf0ff' };
    const colorTexto = { lectura: '#c0392b', audiovisual: '#1a56a0', extension: '#7c3aed' };

    // Limpiamos el contenedor antes de renderizar
    contenedor.innerHTML = '';

    if (reservas.length === 0) {
        contenedor.innerHTML = '<p style="text-align:center; color:#64748b;">No tienes reservas guardadas.</p>';
        return;
    }

    reservas.forEach(reserva => {
        const tipo = reserva.tipo.toLowerCase();
        const card = document.createElement('div');
        card.className = 'reserva-card';

        // Alineación y Estilos de la Tarjeta (Flexbox)
        card.style.cssText = `
            border: 0.5px solid #e2e8f0;
            border-left: 4px solid ${colorBorde[tipo] || '#cbd5e1'};
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        `;

        card.innerHTML = `
            <div>
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                    <span style="font-size:11px; font-weight:600; background:${colorFondo[tipo] || '#f1f5f9'}; color:${colorTexto[tipo] || '#475569'}; border-radius:6px; padding:2px 10px; text-transform: uppercase;">
                        ${reserva.tipo}
                    </span>
                    <span style="font-size:13px; color:#64748b; font-weight:500;">${reserva.dia}</span>
                </div>
                <div style="display:flex; gap:20px;">
                    <span style="font-size:13px; color:#475569;">Horario: <strong>${reserva.horario}</strong></span>
                    <span style="font-size:13px; color:#475569;">Motivo: <strong>${reserva.motivo}</strong></span>
                </div>
            </div>
            <div style="display:flex; gap:8px;">
                <button onclick="irAEditar(${reserva.id})" 
                    style="font-size:13px; padding:6px 16px; border-radius:8px; cursor:pointer; border:1px solid #e2e8f0; background:white; color:#1e293b; font-weight:500;">
                    Editar
                </button>
                <button onclick="cancelarReserva(${reserva.id})" 
                    style="font-size:13px; padding:6px 16px; border-radius:8px; cursor:pointer; border:1px solid #fee2e2; background:white; color:#ef4444; font-weight:500;">
                    Eliminar
                </button>
            </div>
        `;

        contenedor.appendChild(card);
    });
});

/**
 * Lógica de Navegación y Borrado
 */
window.irAEditar = function(id) {
    window.location.href = `editar_reserva.html?id=${id}`;
};

window.cancelarReserva = function(id) {
    Swal.fire({
        title: '¿Deseas eliminar esta reserva?',
        text: "Esta acción liberará el turno en la tabla principal.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2c3e94',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'No, mantener'
    }).then((result) => {
        if (result.isConfirmed) {
            let reservas = JSON.parse(localStorage.getItem('mis_reservas')) || [];
            reservas = reservas.filter(r => r.id !== id);
            localStorage.setItem('mis_reservas', JSON.stringify(reservas));

            Swal.fire({
                title: 'Reserva eliminada',
                icon: 'success',
                timer: 1000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    });
};