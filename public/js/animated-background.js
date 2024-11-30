document.addEventListener('DOMContentLoaded', () => {
    const background = document.querySelector('.animated-background');
    const pngIcons = [
        '/img/icons/ico_logo.png',
        '/img/icons/ico_logo_b.png',
        '/img/icons/ico_qr.png',
        '/img/icons/ico_valorizacion.png',
        '/img/icons/ico_paciente.png',
        '/img/icons/ico_giftcard.png'
    ];

    // Cola para asegurar el uso uniforme de los íconos
    let iconQueue = [...pngIcons];

    const getNextIcon = () => {
        // Si se han usado todos los íconos, reinicia la cola
        if (iconQueue.length === 0) {
            iconQueue = [...pngIcons];
        }
        return iconQueue.shift(); // Devuelve y elimina el primer ícono de la cola
    };

    // Función para crear y animar un ícono PNG
    const createAnimatedIcon = () => {
        const icon = document.createElement('img');
        icon.src = getNextIcon(); // Obtener el siguiente ícono de la cola
        icon.classList.add('animated-png');

        // Posición horizontal: preferencia a los lados
        const horizontalPosition = Math.random();
        if (horizontalPosition < 0.5) {
            // Lado izquierdo (0% - 20%)
            icon.style.left = `${Math.random() * 20}vw`;
        } else {
            // Lado derecho (80% - 100%)
            icon.style.left = `${80 + Math.random() * 20}vw`;
        }

        // Posición inicial y tamaño
        icon.style.bottom = `-10vh`; // Inicia más abajo
        const initialSize = 80 + Math.random() * 40; // Tamaño inicial (80px a 120px)
        icon.style.width = `${initialSize}px`;
        icon.style.height = `${initialSize}px`; // Proporcional
        icon.style.filter = 'brightness(1.1)'; // Leve teñido blanco
        icon.style.animationDelay = `${Math.random() * 2}s`; // Retraso aleatorio

        background.appendChild(icon);

        // Eliminar el ícono después de que la animación termine
        setTimeout(() => {
            icon.remove();
        }, 15000); // Duración total de la animación (15s)
    };

    // Generar íconos de manera periódica
    setInterval(() => {
        createAnimatedIcon();
    }, 1200); // Genera un nuevo ícono cada 1.2 segundos
});
