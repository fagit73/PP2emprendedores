<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicia Sesión - Turnera</title>
    <link rel="stylesheet" href="<?= URLAPP ?>/public/css/login.css">
  
</head>
<body class="login-page">
    <main class="login-card">
        <h1 class="login-title">Inicia sesión</h1>
        <p class="login-subtitle">Accede a tu cuenta</p>

        <form id="loginForm" action="<?php echo URLAPP; ?>/auth/verificar" method="POST">
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" placeholder="tu@correo.com" required>
                <span class="error-message" id="emailError"></span>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="........" required>
                <span class="error-message" id="passwordError"></span>
            </div>

            <button type="submit" id="btnEntrar" class="btn-primary">Entrar</button>
        </form>

        <div class="login-footer">
            <a href="<?= URLAPP ?>/auth/recuperar" class="link-secondary">¿Olvidaste tu contraseña?</a>
            <a href="registro.html" class="link-accent">Crear nueva cuenta</a>
        </div>
    </main>

    <script src="js/login.js"></script>
</body>
</html>