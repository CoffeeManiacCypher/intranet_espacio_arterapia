document.addEventListener('DOMContentLoaded', function() {
    const botones = document.querySelectorAll('button');

    botones.forEach(boton => {
        // Efecto de sonido al pasar el mouse (hover)
        boton.addEventListener('mouseover', function() {
            let hoverSound = new Audio('/sounds/hover.wav');
            hoverSound.play();
        });

        // Efecto de sonido al hacer clic
        boton.addEventListener('click', function() {
            let clickSound = new Audio('/sounds/click.wav');
            clickSound.play();
        });
    });
});
