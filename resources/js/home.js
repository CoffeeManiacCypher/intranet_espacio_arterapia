document.addEventListener('DOMContentLoaded', () => {
    const iconBubbles = document.querySelectorAll('.icon-bubble');
    const descriptionBox = document.querySelector('.module-description');
    const swiperWrapper = document.querySelector('.swiper-container-wrapper');
    const body = document.body;
    let currentTimeout;
    let gradientTimeout;

    // Función para actualizar el fondo y los gradientes
    const updateBackgroundAndGradient = (bgColor) => {
        // Cambiar el color de fondo del body con transición suave
        body.style.transition = 'background-color 1.5s ease';
        body.style.backgroundColor = bgColor;

        // Actualizar gradientes
        const gradientLeft = swiperWrapper.querySelector('.gradient-left');
        const gradientRight = swiperWrapper.querySelector('.gradient-right');

        if (gradientLeft && gradientRight) {
            clearTimeout(gradientTimeout); // Cancelar animaciones anteriores

            gradientLeft.style.transition = 'opacity 1.5s ease, background 1.5s ease';
            gradientRight.style.transition = 'opacity 1.5s ease, background 1.5s ease';

            gradientLeft.style.background = `linear-gradient(to right, ${bgColor} 50%, rgba(255, 255, 255, 0) 100%)`;
            gradientRight.style.background = `linear-gradient(to left, ${bgColor} 50%, rgba(255, 255, 255, 0) 100%)`;

            gradientLeft.style.opacity = '1'; // Mostrar suavemente
            gradientRight.style.opacity = '1';
        }
    };

    // Inicializar el color inicial de los gradientes y el fondo
    const initializeGradients = () => {
        updateBackgroundAndGradient('#E9ECF1'); // Color inicial predeterminado
    };

    // Configuración de eventos para los iconos
    if (iconBubbles && descriptionBox) {
        iconBubbles.forEach((bubble) => {
            bubble.addEventListener('mouseenter', () => {
                const description = bubble.getAttribute('data-description');
                const icon = bubble.getAttribute('data-icon');
                const bgColor = bubble.getAttribute('data-bg-color');

                if (description) {
                    const [title, text] = description.split(':');
                    descriptionBox.innerHTML = `
                        <div class="description-header">
                            <i class="${icon}"></i>
                            <span class="description-title">${title}</span>
                        </div>
                        <div class="description-content">${text}</div>
                    `;
                    descriptionBox.classList.remove('hide');
                    descriptionBox.classList.add('show');
                }

                if (bgColor) {
                    clearTimeout(currentTimeout);
                    currentTimeout = setTimeout(() => {
                        updateBackgroundAndGradient(bgColor);
                    }, 150); // Suavizar el cambio con un pequeño retraso
                }
            });

            bubble.addEventListener('mouseleave', () => {
                descriptionBox.classList.remove('show');
                descriptionBox.classList.add('hide');

                clearTimeout(currentTimeout);
                currentTimeout = setTimeout(() => {
                    updateBackgroundAndGradient('#E9ECF1'); // Fondo predeterminado
                }, 150);
            });
        });
    }

    // Configuración de Swiper
    new Swiper('.swiper-container', {
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        loop: true,
        slidesPerView: 5,
        spaceBetween: 50,
        speed: 600,
        coverflowEffect: {
            rotate: 0,
            stretch: 0,
            depth: 150,
            modifier: 1,
            slideShadows: false,
        },
        navigation: {
            nextEl: '.slider-control .swiper-button-next',
            prevEl: '.slider-control .swiper-button-prev',
        },
        pagination: {
            el: '.slider-control .swiper-pagination',
            clickable: true,
            type: 'bullets',
        },
    });

    // Inicializar los gradientes al cargar
    initializeGradients();
});
