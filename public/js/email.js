document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const emailContainer = document.getElementById('email-container');
    const emailErrorMessage = document.getElementById('email-error-message');

    emailInput.addEventListener('blur', function() {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!emailPattern.test(emailInput.value)) {
            // Aplica la clase de error (borde rojo y movimiento de negación)
            emailContainer.querySelector('.input-group').classList.add('error');
            
            // Animación de sacudida
            emailContainer.querySelector('.input-group').style.animation = 'shake 0.3s';

            // Muestra el mensaje de error
            emailErrorMessage.style.display = 'block';
        } else {
            // Remueve la clase de error
            emailContainer.querySelector('.input-group').classList.remove('error');
            
            // Remueve la animación
            emailContainer.querySelector('.input-group').style.animation = 'none';

            // Oculta el mensaje de error
            emailErrorMessage.style.display = 'none';
        }
    });

});

document.addEventListener("DOMContentLoaded", () => {
    const emailInputs = document.querySelectorAll(".email");
    const errorSound = new Audio('/sounds/error.wav'); // Ruta del sonido de error

    emailInputs.forEach((emailInput) => {
        emailInput.addEventListener("blur", () => {
            // Obtener el mensaje de error asociado
            const errorMessage = emailInput.closest('.input-container').querySelector('.error-message');

            // Validar si el campo está vacío o tiene un formato incorrecto
            if (!emailInput.value.trim()) {
                // Campo vacío
                errorMessage.textContent = "Este campo es obligatorio.";
                errorMessage.style.display = "block";
                playErrorSound();
            } else if (!isValidEmail(emailInput.value)) {
                // Formato inválido
                errorMessage.textContent = "Ingresa un formato de correo adecuado, como ejemplo@email.com.";
                errorMessage.style.display = "block";
                playErrorSound();
            } else {
                // Campo válido
                errorMessage.style.display = "none";
            }
        });
    });

    // Función para reproducir el sonido de error
    function playErrorSound() {
        errorSound.currentTime = 0; // Reinicia el sonido
        errorSound.play();
    }

    // Función para validar formato de correo
    function isValidEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }
});
