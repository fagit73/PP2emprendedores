<?php include APPROOT . '/app/views/inc/header.php'; ?>

<main class="main-content">
    <div class="reserva-container">

        <div class="reserva-header">
            <h1>Editar Reserva</h1>
        </div>

        <!-- NOTA: El action apunta a actualizar, enviamos el ID de reserva oculto -->
        <form action="<?= URLAPP; ?>/reserva/actualizar" method="POST" id="formReserva">

            <!-- ESTE ES EL ÚNICO ID QUE NECESITAS (El de la reserva) -->
            <input type="hidden" name="id_reserva" value="<?= $datos['reserva']->id_reserva; ?>">

            <h3>1. Tipo de Reserva:</h3>
            <div class="options-grid">
                <label class="radio-card">
                    <input type="radio" name="id_tipo_uso" value="1" class="hidden-radio"
                        <?= ($datos['reserva']->id_tipo_uso == 1) ? 'checked' : ''; ?> required>
                    <span class="card-content">📚 Lectura</span>
                </label>

                <label class="radio-card">
                    <input type="radio" name="id_tipo_uso" value="2" class="hidden-radio"
                        <?= ($datos['reserva']->id_tipo_uso == 2) ? 'checked' : ''; ?>>
                    <span class="card-content">🎬 Audiovisual</span>
                </label>
                <label class="radio-card">
                    <input type="radio" name="id_tipo_uso" value="3" class="hidden-radio"
                        <?= ($datos['reserva']->id_tipo_uso == 3) ? 'checked' : ''; ?>>
                    <span class="card-content">🎭 Extensión Cultural</span>
                </label>
            </div>

            <h3 style="margin-top: 25px;">2. ¿Para qué necesitas el turno?</h3>
            <div class="purpose-buttons">
                <button type="button" class="btn-purpose">Completar formulario</button>
                <button type="button" class="btn-purpose">Subir Archivo</button>
                <button type="button" class="btn-purpose">Saltar este paso</button>
            </div>
            <div id="formContainer" class="dynamic-container">
                <div class="form-grid">
                    <div class="form-group-item">
                        <label>Título</label>
                        <input type="text" name="titulo" class="form-input"
                            value="<?= htmlspecialchars($datos['proyecto']->titulo ?? ''); ?>">
                    </div>

                    <div class="form-group-item">
                        <label>Docente/Responsable</label>
                        <input type="text" name="responsable_proyecto" class="form-input"
                            value="<?= htmlspecialchars($datos['proyecto']->responsable_proyecto ?? ''); ?>">
                    </div>

                    <div class="form-group-item">
                        <label>Fecha de inicio</label>
                        <input type="date" name="fecha_inicio" class="form-input"
                            value="<?= $datos['proyecto']->fecha_inicio ?? ''; ?>">
                    </div>

                    <div class="form-group-item">
                        <label>Fecha de fin</label>
                        <input type="date" name="fecha_fin" class="form-input"
                            value="<?= $datos['proyecto']->fecha_fin ?? ''; ?>">
                    </div>

                    <div class="form-group-item full-width">
                        <label>Descripción general</label>
                        <textarea name="descripcion" class="form-input" rows="3"><?= htmlspecialchars($datos['proyecto']->descripcion ?? ''); ?></textarea>
                    </div>

                    <div class="form-group-item">
                        <label>Evaluación</label>
                        <input type="text" name="evaluacion" id="formEvaluacion" class="form-input" value="<?= htmlspecialchars($datos['proyecto']->evaluacion ?? ''); ?>">
                    </div>
                    <!-- CAMBIO: Campo de palabras clave con datalist para autocompletado -->
                    <div class="form-group-item">
                        <label>Palabras clave</label>
                        <input type="text" id="formPalabrasClave" name="palabras_clave" class="form-input" placeholder="Escribe o selecciona..." value="<?= htmlspecialchars($datos['proyecto']->palabras_clave ?? ''); ?>" list="keywordsList">
                        <datalist id="keywordsList">
                            <?php foreach($datos["palabrasClaves"] as $p): ?>
                                <option value="<?= $p->nombre ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                </div>
            </div>

            <div id="fileContainer" class="dynamic-container" style="display: none;">
                <div class="file-upload-box">
                    <label for="archivoTurno">Selecciona un archivo (.doc, .pdf):</label>
                    <!-- CAMBIO: Mejorada la gestión de cambio de archivo -->
                    <div class="file-input-wrapper">
                        <input type="file" name="archivo" id="archivoTurno" accept=".doc,.pdf" class="form-input">
                        <div id="fileStatus" class="file-status-msg" style="display: none; margin-top: 10px; font-size: 0.85rem; color: var(--blue-primary);">
                            Archivo seleccionado: <strong id="selectedFileName"></strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendario y Horarios -->
            <div class="calendar-section">
                <h3>3. Fecha y Horario</h3>
                <div class="booking-layout">
                    <!-- El calendario se inyectará aquí -->
                    <div id="calendarContainer" class="calendar-container">
                        <div class="calendar-header">
                            <button type="button" id="prevMonth" class="btn-nav">&lt;</button>
                            <h4 id="currentMonthYear">Cargando...</h4>
                            <button type="button" id="nextMonth" class="btn-nav">&gt;</button>
                        </div>
                        <div class="calendar-days-header">
                            <span>Dom</span><span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span>
                        </div>
                        <div id="calendarDays" class="calendar-days"></div>
                    </div>

                    <!-- Horarios -->
                    <div id="timeSlotsContainer" class="time-slots-container">
                        <h4>Horarios disponibles</h4>
                        <div id="timeSlotsGrid" class="time-slots-grid"></div>
                    </div>
                    <!-- En tu formulario en editar.php, asegúrate de tener esto: -->
                    <input type="hidden" name="id_reserva" id="id_reserva" value="<?= $datos['reserva']->id_reserva; ?>">
                    <input type="hidden" name="fecha_reserva" id="fecha_reserva" value="<?= $datos['reserva']->fecha_reserva ?>">
                    <input type="hidden" name="id_horario" id="id_horario" value="<?= $datos['reserva']->id_horario ?>">
                </div>
            </div>

            <div style="margin-top: 30px; text-align: center;">
                <button type="submit" class="btn-confirmar">Guardar Cambios</button>
                <a href="<?= URLAPP ?>/dashboard" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</main>
<script>
    const URLAPP = "<?= URLAPP; ?>";
    // Esto conecta PHP con JS:
    const todosLosHorarios = <?= json_encode($datos['horarios'] ?? []); ?>;
</script>

<?php include APPROOT . '/app/views/inc/footer.php'; ?>