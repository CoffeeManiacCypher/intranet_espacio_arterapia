document.addEventListener("DOMContentLoaded", () => {
    const sidebarContainer = document.getElementById("sidebar-container") || (() => {
        const container = document.createElement("div");
        container.id = "sidebar-container";
        document.body.appendChild(container);
        return container;
    })();

    const sidebarHTML = `
         <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

        <button id="sidebar-toggle" aria-label="Abrir menú">
            <i class="bi bi-list"></i>
        </button>
        <div class="sidebar">
            <div class="sidebar-header">

                <img src="/img/icons/ico_logo_b.png" alt="Logo Espacio Arterapia" class="sidebar-logo">

                <input type="text" class="search-bar" placeholder="Buscar...">
            </div>
            <div class="sidebar-separator"></div>
            <ul class="sidebar-menu">
                <li class="sidebar-item" data-link="/home">
                    <i class="fas fa-home"></i> 
                    <span>Inicio</span>
                </li>
                <li class="sidebar-item" data-link="/pacientes">
                    <i class="fa-solid fa-user-group"></i>
                    <span>Pacientes</span>
                </li>
                <li class="sidebar-item" data-link="/pago-qr">
                    <i class="fas fa-qrcode"></i>
                    <span>Pago QR</span>
                </li>
                <li class="sidebar-item" data-link="/giftcard">
                    <i class="fas fa-gift"></i> 
                    <span>Giftcard</span>
                </li>
                <li class="sidebar-item" data-link="/valoraciones">
                    <i class="fas fa-star"></i>
                    <span>Valoraciones</span>
                </li>
                <li class="sidebar-item" data-link="/servicios">
                    <i class="fas fa-briefcase"></i>
                    <span>Servicios</span>
                </li>
                <li class="sidebar-item" data-link="/configuracion">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </li>
            </ul>
            <div class="sidebar-separator"></div>
            <div class="sidebar-footer">
                <div class="profile-container">
                    <img src="/img/icons/ico_logo.png" alt="Perfil Usuario" class="profile-icon">

                    <div class="user-info">
                        <span class="user-name">Nombre Usuario</span>
                        <span class="user-role">Administrador</span>
                    </div>
                    <div class="dropdown-options">
                        <button class="dropdown-item" data-link="/perfil">
                            <i class="fa-solid fa-circle-user"></i> Ver Perfil
                        </button>
                        <button class="dropdown-item" data-link="/logout">
                            <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    sidebarContainer.innerHTML = sidebarHTML;

    const sidebar = sidebarContainer.querySelector(".sidebar");
    const sidebarItems = sidebarContainer.querySelectorAll(".sidebar-item");
    const toggleButton = sidebarContainer.querySelector("#sidebar-toggle");
    const searchBar = sidebarContainer.querySelector(".search-bar");
    const profileContainer = sidebarContainer.querySelector(".profile-container");
    const dropdownMenu = sidebarContainer.querySelector(".dropdown-options");

    // Detectar la sección actual
    const currentPath = window.location.pathname;
    sidebarItems.forEach((item) => {
        const itemPath = item.getAttribute("data-link");
        if (currentPath === itemPath) {
            item.classList.add("active");
        } else {
            item.classList.remove("active");
        }

        // Asignar evento click a cada sidebar-item
        item.addEventListener("click", () => {
            if (itemPath) {
                window.location.href = itemPath; // Redirigir a la URL especificada
            }
        });
    });

    // Abrir/Cerrar Sidebar
    toggleButton.addEventListener("click", () => {
        sidebar.classList.toggle("expanded");
    });

    // Función de búsqueda interactiva
    let searchTimeout;
    searchBar.addEventListener("input", (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const query = e.target.value.toLowerCase();
            sidebarItems.forEach((item) => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(query) ? "flex" : "none";
            });
        }, 300); // Retraso de debounce
    });

    // Auto-compactar Sidebar cuando el cursor sale
    sidebar.addEventListener("mouseleave", () => {
        sidebar.classList.remove("expanded");
    });

    // Referencia al sidebar-menu
    const sidebarMenu = sidebar.querySelector(".sidebar-menu");

    // Desplazamiento suave con la rueda del ratón
    sidebarMenu.addEventListener("wheel", (e) => {
        // Solo desplazarse si hay contenido que exceda la altura visible
        if (sidebarMenu.scrollHeight > sidebarMenu.clientHeight) {
            e.preventDefault(); // Evitar el scroll general de la página

            // Suavizar el desplazamiento
            const scrollStep = 30; // Ajustar la velocidad del scroll
            sidebarMenu.scrollBy({
                top: e.deltaY > 0 ? scrollStep : -scrollStep, // Subir o bajar según la dirección de la rueda
                behavior: "smooth", // Movimiento suave
            });
        }
    });


    // Perfil de usuario: Abrir/Cerrar opciones
    profileContainer.addEventListener("click", (e) => {
        e.stopPropagation(); // Evitar cierre inesperado
        dropdownMenu.classList.toggle("show");
        profileContainer.classList.toggle("elevated");
    });

    // Cerrar opciones al hacer clic fuera
    document.addEventListener("click", () => {
        dropdownMenu.classList.remove("show");
        profileContainer.classList.remove("elevated");
    });

    // Asegurar que el dropdown no se cierra si interactúas con él
    dropdownMenu.addEventListener("click", (e) => {
        e.stopPropagation();
    });

    // Referencia al sidebar-menu y al toggle button

    const sidebarToggle = document.querySelector("#sidebar-toggle");

    // Scroll suave con snap para el sidebar-menu
    sidebarMenu.addEventListener("wheel", (e) => {
        if (sidebarMenu.scrollHeight > sidebarMenu.clientHeight) {
            e.preventDefault();

            const scrollStep = sidebarMenu.firstElementChild.offsetHeight + 10; // Altura de un elemento + margen
            const direction = e.deltaY > 0 ? 1 : -1;

            // Realiza el desplazamiento con ajuste al elemento más cercano
            sidebarMenu.scrollBy({
                top: direction * scrollStep,
                behavior: "smooth",
            });
        }
    });

    // Animación de giro del botón toggle
    sidebarToggle.addEventListener("click", () => {
        const isExpanded = sidebar.classList.contains("expanded");

        // Añade clases para animar el giro
        sidebarToggle.classList.add(isExpanded ? "rotate-back" : "rotate");
        setTimeout(() => {
            sidebarToggle.classList.remove("rotate", "rotate-back");
        }, 400); // Duración de la animación
    });

});
