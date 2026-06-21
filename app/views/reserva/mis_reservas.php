<?php include APPROOT . '/app/views/inc/header.php'; ?>

<main class="contenedor-reservas">
    <div class="encabezado-reservas">
        <div class="titulos">
            <h1>Mis Reservas</h1>
            <p>Gestiona aquí tus reservas.</p>
        </div>
    </div>

    <div id="contenedor-listado">
        <?php if(empty($datos['reservas'])): ?>
            <p class="listado-vacio">No tienes reservas activas.</p>
        <?php else: ?>
            <?php foreach($datos['reservas'] as $r): ?>
                <div class="reserva-card" style="border-left: 4px solid #f87171;">
                    <div>
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                            <span class="tag-tipo" style="background:#fff0f0; color:#c0392b;">
                                <?= htmlspecialchars($r->nombre) ?>
                            </span>
                            <span class="tag-tipo" style="background:#463ee9; color:#cff3e9;">
                                <?= (empty($r->estado)) ? 'PENDIENTE' : $r->estado  ?>
                            </span>
                            <span style="font-size:13px; color:#64748b; font-weight:500;">
                                <?= $r->fecha_reserva ?>
                            </span>
                        </div>
                        <div style="display:flex; gap:20px;">
                            <span style="font-size:13px; color:#475569;">Horario: <strong><?= substr($r->hora_inicio,0,5) ?> - <?= substr($r->hora_fin,0,5) ?></strong></span>
                            <span style="font-size:13px; color:#475569;">Motivo: <strong><?= htmlspecialchars($r->motivo) ?></strong></span>
                        </div>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <a href="<?= URLAPP ?>/reservas/editar/<?= $r->id_reserva ?>" class="btn-accion">Editar</a>
                        <button onclick="cancelarReserva(<?= $r->id_reserva ?>)" class="btn-accion btn-eliminar">Eliminar</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<script>
function cancelarReserva(id) {
    Swal.fire({
        title: '¿Eliminar reserva?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2c3e94',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= URLAPP ?>/reservas/cancelar/' + id;
        }
    });
}
</script>

<?php include APPROOT . '/app/views/inc/footer.php'; ?>