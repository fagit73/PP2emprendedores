

<div class="welcome">
    <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
    <p>Este es tu panel de administración (Dashboard).</p>


    <ul>
        <li>
            <a href="#">
                Inicio
            </a>
        </li>
    </ul>

    <ul>
        <li>
            <a href="#">
                Mis reservas
            </a>
        </li>
    </ul>

    <ul>
        <li>
            <a href="#">
                Inicio
            </a>
        </li>
    </ul>

    <ul>
        <li>
            <a href="#">
                Inicio
            </a>
        </li>
    </ul>
    
    <a href="<?php echo URLAPP; ?>/auth/logout">Cerrar Sesión</a>
</div>

