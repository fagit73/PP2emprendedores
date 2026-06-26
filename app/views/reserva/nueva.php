<?php include APPROOT . '/app/views/inc/header.php'; ?>

<main class="main-content">
    <!-- Aquí aplicamos la clase que ya tienes definida en tu CSS -->
    <div class="reserva-container">

        <div class="reserva-header">
            <h1>Reservar Turno</h1>
            <p>Selecciona un horario disponible y cuéntanos el propósito de tu visita</p>
        </div>

        <form action="<?= URLAPP; ?>/reserva/guardar" method="POST" id="formReserva">

            <h3>1. Tipo de Reserva:</h3>
            <div class="options-grid">
                <!-- Repite esta estructura para cada opción -->
                <label class="radio-card">
                    <input type="radio" name="id_tipo_uso" value="1" class="hidden-radio" required>
                    <span class="card-content">📚 Lectura</span>
                </label>

                <label class="radio-card">
                    <input type="radio" name="id_tipo_uso" value="2" class="hidden-radio" required>
                    <span class="card-content">🎬 Audiovisual</span>
                </label>

                <label class="radio-card">
                    <input type="radio" name="id_tipo_uso" value="3" class="hidden-radio" required>
                    <span class="card-content">🎭 Extensión Cultural</span>
                </label>
            </div>

            <h3 style="margin-top: 25px;">2. Sala</h3>
            <div class="form-group-item">
                <select name="id_sala" class="form-input" required>
                    <?php foreach ($datos['salas'] as $sala): ?>
                        <option value="<?= $sala->id_sala ?>"><?= htmlspecialchars($sala->nombre) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <h3 style="margin-top: 25px;">3. ¿Para qué necesitas el turno?</h3>
            <div class="purpose-buttons">
                <button type="button" class="btn-purpose">Completar formulario</button>
                <button type="button" class="btn-purpose">Subir Archivo</button>
                <button type="button" class="btn-purpose">Saltar este paso</button>
            </div>

            <div id="formContainer" class="dynamic-container" style="display: none;">
                <div class="form-grid">
                    <div class="form-group-item">
                        <label>Título</label>
                        <input type="text" name="titulo" id="formTitulo" class="form-input" placeholder="Título del proyecto">
                    </div>
                    <!-- CAMBIO: Nuevo campo Docente/Responsable -->
                    <div class="form-group-item">
                        <label>Docente/Responsable</label>
                        <input type="text" name="responsable_proyecto" id="formDocente" class="form-input" placeholder="Nombre del docente">
                    </div>
                    <div class="form-group-item">
                        <label>Fecha de inicio</label>
                        <input type="date" name="fecha_inicio" id="formFechaInicio" class="form-input">
                    </div>
                    <div class="form-group-item">
                        <label>Fecha de fin</label>
                        <input type="date" name="fecha_fin" id="formFechaFin" class="form-input">
                    </div>
                    <div class="form-group-item full-width">
                        <label>Descripción general</label>
                        <textarea id="formDescripcion" name="descripcion" class="form-input" rows="3"></textarea>
                    </div>
                    <div class="form-group-item">
                        <label>Evaluación</label>
                        <input type="text" name="evaluacion" id="formEvaluacion" class="form-input">
                    </div>
                    <!-- CAMBIO: Campo de palabras clave con datalist para autocompletado -->
                    <div class="form-group-item">
                        <label>Palabras clave</label>
                        <input type="text" id="formPalabrasClave" name="palabras_clave" class="form-input" placeholder="Escribe o selecciona..." list="keywordsList">
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

            <div class="calendar-section">
                <h3>3. Reserva tu lugar</h3>
                <div class="booking-layout">
                    <!-- Calendario -->
                    <div id="calendarContainer" class="calendar-container">
                        <!-- El JS debe inyectar el contenido aquí -->
                        <div class="calendar-header">
                            <button type="button" id="prevMonth" class="btn-nav">&lt;</button>
                            <h4 id="currentMonthYear">Junio 2026</h4>
                            <button type="button" id="nextMonth" class="btn-nav">&gt;</button>
                        </div>
                        <div class="calendar-days-header">
                            <span>Dom</span><span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span>
                        </div>
                        <div id="calendarDays" class="calendar-days">
                            <!-- JS inyecta días -->
                        </div>
                    </div>

                    <!-- Horarios -->
                    <div id="timeSlotsContainer" class="time-slots-container">
                        <h4>Horarios para el día seleccionado</h4>
                        <div id="timeSlotsGrid" class="time-slots-grid">
                            <!-- Los botones de horario irán aquí -->
                        </div>
                    </div>

                    <input type="hidden" name="fecha_reserva" id="fecha_reserva">
                    <input type="hidden" name="id_horario" id="id_horario">
                </div>
            </div>

            <div style="margin-top: 30px; text-align: center;">
                <button type="submit" class="btn-confirmar">Confirmar Reserva</button>
            </div>
        </form>
    </div>
</main>
<script>
    const URLAPP = "<?= URLAPP; ?>";
    const todosLosHorarios = <?= json_encode($datos['horarios']); ?>;
</script>
<?php include APPROOT . '/app/views/inc/footer.php'; ?>