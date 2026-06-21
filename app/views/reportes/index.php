<?php

include APPROOT . '/app/views/inc/header.php';


?>

<!-- Main -->
<main class="main-content">


<div class="reserva-container">
    <h1>Reportes de Reservas</h1>
    <div class="options-grid">
        <?php foreach($datos['stats'] as $s): ?>
            <div class="kpi-card">
                <h3><?= $s->total ?></h3>
                <p><?= $s->nombre ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="javascript:window.print()" class="btn-primary mt-4">Imprimir Reporte</a>
</div>


</main>


<?php

include APPROOT . '/app/views/inc/footer.php';

?>