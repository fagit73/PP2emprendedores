<?php

include APPROOT . '/app/views/inc/header.php';


?>

<!-- Main -->
<main class="main-content">


    <div class="reserva-container">
        <h1>Editar Usuario: <?= htmlspecialchars($datos['usuario']->nombre_completo) ?></h1>

        <form action="<?= URLAPP ?>/usuario/actualizar" method="POST">
            <!-- Input oculto para identificar qué usuario editar -->
            <input type="hidden" name="id_usuario" value="<?= $datos['usuario']->id_usuario ?>">

            <div class="form-grid">
                <div>
                    <label>Nombre:</label>
                    <input type="text" name="nombre_completo" class="form-input"
                        value="<?= htmlspecialchars($datos['usuario']->nombre_completo) ?>" required>
                </div>

                <div>
                    <label>Email institucional:</label>
                    <input type="email" name="email_institucional" class="form-input"
                        value="<?= htmlspecialchars($datos['usuario']->email_institucional) ?>" required>
                </div>

                <div>
                    <label>Email recuperacion:</label>
                    <input type="email" name="email_recuperacion" class="form-input"
                        value="<?= htmlspecialchars($datos['usuario']->email_recuperacion) ?>" required>
                </div>

                <div>
                    <label>Rol:</label>
                    <select name="id_rol" class="form-input">
                        <!-- Seleccionamos la opción que corresponde al usuario actual -->
                        <option value="1" <?= $datos['usuario']->id_rol == 1 ? 'selected' : '' ?>>Administrador</option>
                        <option value="2" <?= $datos['usuario']->id_rol == 2 ? 'selected' : '' ?>>Usuario</option>
                    </select>
                </div>

                <div>
                    <label>Estado:</label>
                    <select name="activo" class="form-input">
                        <!-- Seleccionamos la opción que corresponde al usuario actual -->
                        <option value="1" <?= $datos['usuario']->activo == 1 ? 'selected' : '' ?>>Activo</option>
                        <option value="2" <?= $datos['usuario']->activo == 2 ? 'selected' : '' ?>>Suspendido</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn-confirmar">Guardar Cambios</button>
                <a href="<?= URLAPP ?>/usuario" style="margin-left: 15px; color: #64748b;">Cancelar</a>
            </div>
        </form>
    </div>


</main>


<?php

include APPROOT . '/app/views/inc/footer.php';

?>