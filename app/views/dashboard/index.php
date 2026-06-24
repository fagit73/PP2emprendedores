<?php

include APPROOT . '/app/views/inc/header.php';

?>

<!-- Main -->
<main class="main-content">

    <?php if (isset($_SESSION['error'])): ?>
        <div class="reserva-container">

            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="reserva-container" id="contenedor-listado">
        <div class="titulos">
            <h1>Mis Reservas Confirmadas</h1>
        </div>
        <?php if (empty($datos['reservasConfirmadas'])): ?>
            <p class="listado-vacio">No tienes reservas pendientes.</p>
        <?php else: ?>
            <?php foreach ($datos['reservasConfirmadas'] as $r): ?>
                <div class="reserva-card" style="border-left: 4px solid #f87171;">
                    <div style="width: 100%;">
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
                            <span class="tag-tipo" style="background:#fff0f0; color:#c0392b;"><?= htmlspecialchars($r->nombre) ?></span>
                            <span class="tag-tipo" style="background:#e0e7ff; color:#4338ca;"><?= (empty($r->estado)) ? "PENDIENTE" : $r->estado ?></span>
                            <span style="font-size:13px; color:#64748b; font-weight:500; margin-left:auto;">
                                <?= $r->fecha_reserva ?>
                            </span>
                        </div>

                        <div style="margin: 12px 0; padding: 12px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <?php if ($r->tipo_carga == 'FORMULARIO'): ?>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 13px;">
                                    <p style="margin:0;"><strong>Título:</strong> <?= htmlspecialchars($r->titulo) ?></p>
                                    <p style="margin:0;"><strong>Responsable:</strong> <?= htmlspecialchars($r->responsable_proyecto) ?></p>
                                    <p style="margin:0;"><strong>Fechas:</strong> <?= $r->fecha_inicio ?> al <?= $r->fecha_fin ?></p>
                                    <p style="margin:0;"><strong>Evaluación:</strong> <?= htmlspecialchars($r->evaluacion) ?></p>
                                    <p style="margin:0; grid-column: span 2;"><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($r->descripcion)) ?></p>
                                    <p style="margin:0; grid-column: span 2;"><strong>Palabras Clave:</strong>
                                        <?php foreach (explode(',', $r->palabras_clave) as $tag): ?>
                                            <span style="background:#e0e7ff; color:#4338ca; padding:2px 6px; border-radius:4px; margin-right:4px; font-size:11px;"><?= trim($tag) ?></span>
                                        <?php endforeach; ?>
                                    </p>
                                </div>
                            <?php elseif ($r->tipo_carga == 'ARCHIVO'): ?>
                                <div style="font-size: 13px;">
                                    <p style="margin:0;"><strong>Archivo Adjunto:</strong>
                                        <a href="<?= URLAPP ?>/uploads/<?= $r->archivo ?>" target="_blank" style="color: #2563eb;">
                                            Descargar <?= htmlspecialchars($r->archivo) ?>
                                        </a>
                                    </p>
                                </div>
                            <?php else: ?>
                                <p style="margin:0; font-size:13px; color:#94a3b8;">Reserva sin información de proyecto adicional.</p>
                            <?php endif; ?>
                        </div>

                        <div style="display:flex; gap:20px; margin-top:10px;">
                            <span style="font-size:13px; color:#475569;">Horario: <strong><?= substr($r->hora_inicio, 0, 5) ?> - <?= substr($r->hora_fin, 0, 5) ?></strong></span>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="reserva-container" id="contenedor-listado">
        <div class="titulos">
            <h1>Mis Reservas Pendientes</h1>
            <p>Edita o cancela tus reservas</p>
        </div>
        <?php if (empty($datos['reservasPendientes'])): ?>
            <p class="listado-vacio">No tienes reservas pendientes.</p>
        <?php else: ?>
            <?php foreach ($datos['reservasPendientes'] as $r): ?>
                <div class="reserva-card" style="border-left: 4px solid #f87171;">
                    <div style="width: 100%;">
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
                            <span class="tag-tipo" style="background:#fff0f0; color:#c0392b;"><?= htmlspecialchars($r->nombre) ?></span>
                            <span class="tag-tipo" style="background:#e0e7ff; color:#4338ca;"><?= (empty($r->estado)) ? "PENDIENTE" : $r->estado ?></span>
                            <span style="font-size:13px; color:#64748b; font-weight:500; margin-left:auto;">
                                <?= $r->fecha_reserva ?>
                            </span>
                        </div>

                        <div style="margin: 12px 0; padding: 12px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <?php if ($r->tipo_carga == 'FORMULARIO'): ?>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 13px;">
                                    <p style="margin:0;"><strong>Título:</strong> <?= htmlspecialchars($r->titulo) ?></p>
                                    <p style="margin:0;"><strong>Responsable:</strong> <?= htmlspecialchars($r->responsable_proyecto) ?></p>
                                    <p style="margin:0;"><strong>Fechas:</strong> <?= $r->fecha_inicio ?> al <?= $r->fecha_fin ?></p>
                                    <p style="margin:0;"><strong>Evaluación:</strong> <?= htmlspecialchars($r->evaluacion) ?></p>
                                    <p style="margin:0; grid-column: span 2;"><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($r->descripcion)) ?></p>
                                    <p style="margin:0; grid-column: span 2;"><strong>Palabras Clave:</strong>
                                        <?php foreach (explode(',', $r->palabras_clave) as $tag): ?>
                                            <span style="background:#e0e7ff; color:#4338ca; padding:2px 6px; border-radius:4px; margin-right:4px; font-size:11px;"><?= trim($tag) ?></span>
                                        <?php endforeach; ?>
                                    </p>
                                </div>
                            <?php elseif ($r->tipo_carga == 'ARCHIVO'): ?>
                                <div style="font-size: 13px;">
                                    <p style="margin:0;"><strong>Archivo Adjunto:</strong>
                                        <a href="<?= URLAPP ?>/uploads/<?= $r->archivo ?>" target="_blank" style="color: #2563eb;">
                                            Descargar <?= htmlspecialchars($r->archivo) ?>
                                        </a>
                                    </p>
                                </div>
                            <?php else: ?>
                                <p style="margin:0; font-size:13px; color:#94a3b8;">Reserva sin información de proyecto adicional.</p>
                            <?php endif; ?>
                        </div>

                        <div style="display:flex; gap:20px; margin-top:10px;">
                            <span style="font-size:13px; color:#475569;">Horario: <strong><?= substr($r->hora_inicio, 0, 5) ?> - <?= substr($r->hora_fin, 0, 5) ?></strong></span>
                        </div>
                    </div>

                    <!-- Botones
                    <div style="display:flex; gap:8px; margin-left: 20px;">
                        <a href="<?= URLAPP ?>/reservas/editar/<?= $r->id_reserva ?>" class="btn-accion">Editar</a>
                        <button onclick="cancelarReserva(<?= $r->id_reserva ?>)" class="btn-accion btn-eliminar">Eliminar</button>
                    </div>-->
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (tieneRol(['ADMIN'])): ?>
        <div class="reserva-container" id="contenedor-listado">
            <div class="titulos">
                <h1>Reservas a Confirmar</h1>
            </div>
            <?php if (empty($datos['reservasAConfirmar'])): ?>
                <p class="listado-vacio">No tienes reservas pendientes.</p>
            <?php else: ?>
                <?php foreach ($datos['reservasAConfirmar'] as $r): ?>
                    <div class="reserva-card" style="border-left: 4px solid #f87171;">
                        <div style="width: 100%;">
                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
                                <span class="tag-tipo" style="background:#fff0f0; color:#c0392b;"><?= htmlspecialchars($r->nombre) ?></span>
                                <span class="tag-tipo" style="background:#e0e7ff; color:#4338ca;"><?= (empty($r->estado)) ? "PENDIENTE" : $r->estado ?></span>
                                <span style="font-size:13px; color:#64748b; font-weight:500; margin-left:auto;">
                                    <?= $r->fecha_reserva ?>
                                </span>
                            </div>

                            <div style="margin: 12px 0; padding: 12px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;">
                                <?php if ($r->tipo_carga == 'FORMULARIO'): ?>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 13px;">
                                        <p style="margin:0;"><strong>Título:</strong> <?= htmlspecialchars($r->titulo) ?></p>
                                        <p style="margin:0;"><strong>Responsable:</strong> <?= htmlspecialchars($r->responsable_proyecto) ?></p>
                                        <p style="margin:0;"><strong>Fechas:</strong> <?= $r->fecha_inicio ?> al <?= $r->fecha_fin ?></p>
                                        <p style="margin:0;"><strong>Evaluación:</strong> <?= htmlspecialchars($r->evaluacion) ?></p>
                                        <p style="margin:0; grid-column: span 2;"><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($r->descripcion)) ?></p>
                                        <p style="margin:0; grid-column: span 2;"><strong>Palabras Clave:</strong>
                                            <?php foreach (explode(',', $r->palabras_clave) as $tag): ?>
                                                <span style="background:#e0e7ff; color:#4338ca; padding:2px 6px; border-radius:4px; margin-right:4px; font-size:11px;"><?= trim($tag) ?></span>
                                            <?php endforeach; ?>
                                        </p>
                                    </div>
                                <?php elseif ($r->tipo_carga == 'ARCHIVO'): ?>
                                    <div style="font-size: 13px;">
                                        <p style="margin:0;"><strong>Archivo Adjunto:</strong>
                                            <a href="<?= URLAPP ?>/uploads/<?= $r->archivo ?>" target="_blank" style="color: #2563eb;">
                                                Descargar <?= htmlspecialchars($r->archivo) ?>
                                            </a>
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <p style="margin:0; font-size:13px; color:#94a3b8;">Reserva sin información de proyecto adicional.</p>
                                <?php endif; ?>
                            </div>

                            <div style="display:flex; gap:20px; margin-top:10px;">
                                <span style="font-size:13px; color:#475569;">Horario: <strong><?= substr($r->hora_inicio, 0, 5) ?> - <?= substr($r->hora_fin, 0, 5) ?></strong></span>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div style="display:flex; gap:8px; margin-left: 20px;">
                            <a href="<?= URLAPP ?>/reservas/cancelar/<?= $r->id_reserva ?>" class="btn-accion">Cancelar</a>
                            <a href="<?= URLAPP ?>/reservas/confirmar/<?= $r->id_reserva ?>" class="btn-accion">Confirmar</a>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>



</main>


<?php

include APPROOT . '/app/views/inc/footer.php';

?>