<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="welcome">
    <h1>Bienvenido, <?php echo $_SESSION['user_name']; ?></h1>
    <p>Este es tu panel de administración (Dashboard).</p>
    
    <a href="<?php echo URLAPP; ?>/auth/logout">Cerrar Sesión</a>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>