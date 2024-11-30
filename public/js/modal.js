document.addEventListener("DOMContentLoaded", () => {
    const modals = document.querySelectorAll(".modal"); // Selecciona todos los modales
    const btnOpenModals = document.querySelectorAll("[data-modal-target]"); // Botones que abren modales
    const soundTake = new Audio('/sounds/modal_tomar.wav');
    const soundRelease = new Audio('/sounds/modal_soltar.wav');

    let isDragging = false;
    let offsetX = 0;
    let offsetY = 0;

    // Abrir el modal
    function openModal(modal) {
        modal.style.visibility = "visible";
        modal.classList.add("show");
    }

    // Cerrar el modal
    function closeModal(modal) {
        modal.classList.add("hide");
        modal.classList.remove("show");
        modal.addEventListener(
            "animationend",
            () => {
                modal.classList.remove("hide");
                modal.style.visibility = "hidden";
            },
            { once: true }
        );
    }

    // Eventos para abrir modales
    btnOpenModals.forEach((button) => {
        button.addEventListener("click", () => {
            const modalTarget = button.getAttribute("data-modal-target");
            const modal = document.querySelector(modalTarget);
            if (modal) openModal(modal);
        });
    });

    // Configurar eventos para cada modal
    modals.forEach((modal) => {
        const modalContent = modal.querySelector(".modal-content");
        const btnClose = modal.querySelector(".btn-close");

        // Botón para cerrar el modal
        btnClose.addEventListener("click", () => closeModal(modal));

        // Cerrar modal al hacer clic fuera de él
        modal.addEventListener("click", (event) => {
            if (event.target === modal) closeModal(modal);
        });

        // Cerrar modal con la tecla Esc
        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") closeModal(modal);
        });

        // Inicio del arrastre
        modalContent.addEventListener("mousedown", (e) => {
            isDragging = true;
            offsetX = e.clientX - modalContent.offsetLeft;
            offsetY = e.clientY - modalContent.offsetTop;

            modalContent.classList.add("dragging");
            soundTake.currentTime = 0;
            soundTake.play();
        });

        // Movimiento durante el arrastre
        document.addEventListener("mousemove", (e) => {
            if (isDragging) {
                const windowWidth = window.innerWidth;
                const windowHeight = window.innerHeight;
                const modalWidth = modalContent.offsetWidth;
                const modalHeight = modalContent.offsetHeight;

                let newLeft = e.clientX - offsetX;
                let newTop = e.clientY - offsetY;

                newLeft = Math.max(0, Math.min(newLeft, windowWidth - modalWidth));
                newTop = Math.max(0, Math.min(newTop, windowHeight - modalHeight));

                modalContent.style.position = "absolute";
                modalContent.style.left = `${newLeft}px`;
                modalContent.style.top = `${newTop}px`;
            }
        });

        // Fin del arrastre
        document.addEventListener("mouseup", () => {
            if (isDragging) {
                isDragging = false;
                modalContent.classList.remove("dragging");
                modalContent.classList.add("rebound");
                modalContent.addEventListener(
                    "animationend",
                    () => {
                        modalContent.classList.remove("rebound");
                    },
                    { once: true }
                );
                soundRelease.currentTime = 0;
                soundRelease.play();
            }
        });
    });
});
