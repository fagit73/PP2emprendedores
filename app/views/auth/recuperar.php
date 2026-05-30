<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="<?= URLAPP ?>/public/css/recuperar.css">
</head>
<body>

    <div class="contenedor">
        
        <div id="pantalla-formulario">
            <header>
                <h1>Recuperar contraseña</h1>
                <p>Te enviaremos instrucciones</p>
            </header>

            <form id="recuperarForm">
                <div class="grupo-input">
                    <label for="email" class="required-recuperar">Correo electrónico</label>
                    <input 
                        type="email" 
                        id="email" 
                        placeholder="tu@correo.com" 
                        required
                    >
                </div>

                <button type="submit" class="btn-naranja">
                    Enviar enlace
                </button>
            </form>
        </div>

        <div id="pantalla-exito" style="display: none;">
            <header>
                <h1 class="titulo-exito">Recuperar contraseña</h1>
                <p>Te enviaremos instrucciones</p>
            </header>

            <a href="<?= URLAPP ?>/auth/login" class="link-volver">← Volver al login</a>

            <div class="alerta-mensaje-verde">
                <span>✓ Revisa tu correo para recuperar tu contraseña</span>
            </div>
        </div>

    </div>

</body>
</html>