// Espera carga completa del DOM
document.addEventListener('DOMContentLoaded', () => {

    // Elementos HTML
    const loginForm = document.getElementById('loginForm');

    const emailInput = document.getElementById('email');

    const passwordInput = document.getElementById('password');

    // Verifica formulario
    if (!loginForm) {

        console.error('Formulario no encontrado');

        return;
    }

    // Evento submit
    loginForm.addEventListener('submit', (e) => {

        // Evita recarga
        e.preventDefault();

        // Estado validación
        let isValid = true;

        // Limpia errores
        clearErrors();

        // Validación email vacío
        if (!emailInput.value.trim()) {

            showError(
                'emailError',
                'El correo electrónico es obligatorio'
            );

            emailInput.classList.add('invalid');

            isValid = false;
        }

        // Validación formato email
        else if (!validateEmail(emailInput.value)) {

            showError(
                'emailError',
                'Formato incorrecto'
            );

            emailInput.classList.add('invalid');

            isValid = false;
        }

        // Validación password
        if (!passwordInput.value.trim()) {

            showError(
                'passwordError',
                'La contraseña es obligatoria'
            );

            passwordInput.classList.add('invalid');

            isValid = false;
        }

        // Login correcto
        if (isValid) {

            console.log('Login exitoso');

            // Redirección
            window.location.href =
                'pantallareserva.html';
        }
    });

    // Validación regex email
    function validateEmail(email) {

        const re =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        return re.test(email);
    }

    // Mostrar errores
    function showError(id, message) {

        const element =
            document.getElementById(id);

        if (element) {

            element.innerText = message;
        }
    }

    // Limpiar errores
    function clearErrors() {

        document
            .querySelectorAll('.error-message')
            .forEach(el => {

                el.innerText = '';
            });

        document
            .querySelectorAll('input')
            .forEach(input => {

                input.classList.remove('invalid');
            });
    }

});








