// Espera carga completa
document.addEventListener('DOMContentLoaded', () => {

    // Elementos
    const registroForm =
        document.getElementById('registroForm');

    const pantallaRegistro =
        document.getElementById('pantalla-registro');

    const pantallaExito =
        document.getElementById('pantalla-exito-registro');

    // Verifica formulario
    if (!registroForm) {

        console.error('Formulario no encontrado');

        return;
    }

    // Submit
    registroForm.addEventListener('submit', (e) => {

        // Evita recarga
        e.preventDefault();

        // Mostrar éxito
        pantallaRegistro.style.display = 'none';

        pantallaExito.style.display = 'block';

        // Limpiar formulario
        registroForm.reset();

        // Redirección
        setTimeout(() => {

            window.location.href = 'login.html';

        }, 3000);

    });

});









