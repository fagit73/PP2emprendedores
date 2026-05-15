const registroForm = document.getElementById('registroForm');
const pantallaReg = document.getElementById('pantalla-registro');
const pantallaExitoReg = document.getElementById('pantalla-exito-registro');

registroForm.addEventListener('submit', (e) => {
    e.preventDefault(); // Evita la recarga de página

    // 1. Intercambio visual
    pantallaReg.style.display = 'none';
    pantallaExitoReg.style.display = 'block';
    pantallaExitoReg.classList.add('fade-in');

    // 2. Limpiar campos (importante para seguridad de datos)
    registroForm.reset();

    // 3. Redirección automática al Login tras 3 segundos
    setTimeout(() => {
        window.location.href = 'login.html';
    }, 3000);
});