document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        let isValid = true;

        // Limpiar errores previos
        clearErrors();

        // 1. Validar Email Obligatorio y Formato
        if (!emailInput.value.trim()) {
            showError('emailError', 'El correo electrónico es obligatorio');
            emailInput.classList.add('invalid');
            isValid = false;
        } else if (!validateEmail(emailInput.value)) {
            showError('emailError', 'Formato incorrecto (falta el @ o dominio)');
            emailInput.classList.add('invalid');
            isValid = false;
        }

        // 2. Validar Contraseña Obligatoria
        if (!passwordInput.value.trim()) {
            showError('passwordError', 'La contraseña es obligatoria');
            passwordInput.classList.add('invalid');
            isValid = false;
        }

        // 3. Redirección si todo es correcto
        if (isValid) {
            console.log("Datos válidos. Redirigiendo...");
            // Aquí simularíamos la petición al servidor
            window.location.href = 'pantallareserva.html'; // Redirige a la pantalla principal
        }
    });

    // Función de ayuda para validar formato de mail con Regex
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function showError(elementId, message) {
        document.getElementById(elementId).innerText = message;
    }

    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => el.innerText = '');
        document.querySelectorAll('input').forEach(input => input.classList.remove('invalid'));
    }
});