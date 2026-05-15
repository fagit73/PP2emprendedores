const formulario = document.getElementById('recuperarForm');
const pantallaForm = document.getElementById('pantalla-formulario');
const pantallaExito = document.getElementById('pantalla-exito');

if (!formulario) console.error("ERROR: No se encontró el formulario con id 'recuperarForm'");

formulario.addEventListener('submit', (e) => {
    e.preventDefault(); 
    console.log("1. Formulario enviado correctamente.");

    // Ocultar y mostrar pantallas
    if (pantallaForm && pantallaExito) {
        pantallaForm.style.display = 'none';
        pantallaExito.style.display = 'block';
        pantallaExito.classList.add('fade-in');
        console.log("2. Cambio de pantalla realizado.");
    } else {
        console.error("ERROR: No se encontraron los IDs de las pantallas.");
    }

    // Redirección con 3 segundos
    console.log("3. Iniciando cuenta regresiva de 3 segundos...");
    
    setTimeout(() => {
        console.log("4. Intentando redirigir a login.html...");
        window.location.href = 'login.html'; 
    }, 3000); 
});