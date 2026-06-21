<?php

include APPROOT . '/app/views/inc/header.php';


?>

<!-- Main -->
<main class="main-content">


    <div class="card">
        <div class="card-header">Listado de Usuarios</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email institucional</th>
                        <th>Email recuperacion</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos['usuarios'] as $u): ?>
                        <tr>
                            <td><?= $u->nombre_completo ?></td>
                            <td><?= $u->email_institucional ?></td>
                            <td><?= $u->email_recuperacion ?></td>
                            <td><?= ($u->activo == 1) ? "ACTIVO" : "SUSPENDIDO" ?></td>
                            <td><span class="badge bg-info"><?= $u->nombre_rol ?></span></td>
                            <td><a href="<?= URLAPP ?>/usuario/editar/<?= $u->id_usuario ?>" class="btn-accion">Editar</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>


</main>


<?php

include APPROOT . '/app/views/inc/footer.php';

?>