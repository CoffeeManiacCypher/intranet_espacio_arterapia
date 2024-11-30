document.addEventListener("DOMContentLoaded", () => {
    // Muestra u oculta campos según selección
    const estadoSelect = document.getElementById("estado");
    const valorMinimoInput = document.querySelector("input[name='valor_minimo']");
    const valorMaximoInput = document.querySelector("input[name='valor_maximo']");

    if (estadoSelect) {
        estadoSelect.addEventListener("change", (e) => {
            const estado = e.target.value;
            if (estado === "expirada") {
                valorMinimoInput.disabled = true;
                valorMaximoInput.disabled = true;
            } else {
                valorMinimoInput.disabled = false;
                valorMaximoInput.disabled = false;
            }
        });
    }
});
