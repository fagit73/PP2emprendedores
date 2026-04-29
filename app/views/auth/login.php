<?php 

require APPROOT_DESA . '/views/inc/header.php'; ?>

<div class="login-card">
    <h2>Iniciar Sesión</h2>
    <!-- La ruta apunta al controlador Auth y método verificar -->
    <form action="<?php echo URLAPP; ?>/auth/verificar" method="POST">
        <input type="email" name="email" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
</div>

<?php require APPROOT_DESA . '/views/inc/footer.php'; ?>