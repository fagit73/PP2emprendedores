<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="<?= URLAPP ?>/public/css/registro.css">
</head>
<body>

<div class="contenedor">
    
    <div id="pantalla-registro">
        <header>
            <h1>Crear cuenta</h1>
            <p>Completa los datos</p>
        </header>

        <form id="registroForm" action="<?= URLAPP ?>/usuario/registrar" method="POST">
            <label class="required-label">Nombre completo</label>
            <input name="nombre_completo" type="text" placeholder="Tu nombre completo" required>

            <label class="required-label">DNI</label>
            <input name="dni" type="text" placeholder="12345678" required>

            <label class="required-label">Celular</label>
            <input name="celular" type="text" placeholder="+54 9 11 1234-5678" required>

            <label class="required-label">Email institucional</label>
            <input name="email_institucional" type="email" placeholder="usuario@institucion.edu" required>

            <label class="required-label">Email de recuperación</label>
            <input name="email_recuperacion" type="email" placeholder="tu@correo.com" required>

            <label class="required-label">Contraseña</label>
            <input name="password" type="password" placeholder="********" required>

            <button type="submit" class="btn-registro">Registrarse</button>
        </form>

        <a href="<?= URLAPP ?>/auth/login" class="link link-azul">← Volver al login</a>
    </div>


    <div id="pantalla-exito-registro" style="display: none;">
        <header>
            <h1>¡Bienvenido!</h1>
            <p>Tu cuenta ha sido creada</p>
        </header>

        <a href="login.html" class="link-volver">← Ir al login para ingresar</a>

        <div class="alerta-mensaje-verde">
            <span>✓ Registro completado. Ya podés iniciar sesión.</span>
        </div>
    </div>

</div>


</body>
</html>