// DOM cargado
document.addEventListener('DOMContentLoaded', () => {

    // Elementos
    const formulario =
        document.getElementById('recuperarForm');

    const pantallaForm =
        document.getElementById('pantalla-formulario');

    const pantallaExito =
        document.getElementById('pantalla-exito');

    // Validación
    if (!formulario) {

        console.error('Formulario no encontrado');

        return;
    }

    // Submit
    formulario.addEventListener('submit', (e) => {

        // Evita recarga
        e.preventDefault();

        // Oculta formulario
        pantallaForm.style.display = 'none';

        // Muestra éxito
        pantallaExito.style.display = 'block';

        // Redirección
        setTimeout(() => {

            window.location.href = 'login.html';

        }, 3000);

    });

});






