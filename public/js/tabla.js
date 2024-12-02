document.addEventListener('DOMContentLoaded', () => {
    const selectAllCheckbox = document.getElementById('select-all');
    const rows = document.querySelectorAll('.tabla-dinamica tbody tr');
    const rowCheckboxes = document.querySelectorAll('.tabla-dinamica tbody .checkbox');
    const sortableColumns = document.querySelectorAll('.sortable');

    // Seleccionar/Deseleccionar todas las filas
    selectAllCheckbox?.addEventListener('change', () => {
        const isChecked = selectAllCheckbox.checked;
        rowCheckboxes.forEach((checkbox, index) => {
            const row = rows[index];
            if (row) {
                checkbox.checked = isChecked;
                toggleRowSelection(row, isChecked);
            }
        });
    });

    // Seleccionar/Deseleccionar una fila individual mediante checkbox
    rowCheckboxes.forEach((checkbox, index) => {
        const row = rows[index];
        checkbox?.addEventListener('change', (e) => {
            toggleRowSelection(row, checkbox.checked);
            updateSelectAllState();
            e.stopPropagation();
        });
    });

    rows.forEach((row) => {
        row?.addEventListener('click', (e) => {
            // Ignorar filas que tienen modales
            if (row.hasAttribute('data-has-modal')) {
                console.log('Fila con modal ignorada por tabla.js');
                return;
            }
    
            const checkbox = row.querySelector('.checkbox');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                toggleRowSelection(row, checkbox.checked);
                updateSelectAllState();
            }
        });
    });
    
    
    
    

    // Función para aplicar o quitar la clase seleccionada
    function toggleRowSelection(row, isSelected) {
        row.classList.toggle('seleccionada', isSelected);
    }

    // Actualiza el estado del checkbox "Seleccionar todo"
    function updateSelectAllState() {
        const allChecked = Array.from(rowCheckboxes).every((checkbox) => checkbox.checked);
        const someChecked = Array.from(rowCheckboxes).some((checkbox) => checkbox.checked);
        selectAllCheckbox.indeterminate = !allChecked && someChecked;
        selectAllCheckbox.checked = allChecked;
    }

    // Ordenar columnas
    sortableColumns.forEach((column) => {
        column?.addEventListener('click', () => {
            const index = column.dataset.column;
            const direction = column.classList.contains('asc') ? 'desc' : 'asc';

            sortTableByColumn(index, direction);
            updateSortIcons(column, direction);
        });
    });

    function sortTableByColumn(index, direction) {
        const tbody = document.querySelector('.tabla-dinamica tbody');
        const rowsArray = Array.from(tbody.querySelectorAll('tr'));

        rowsArray.sort((a, b) => {
            const aText = a.cells[index].innerText.trim();
            const bText = b.cells[index].innerText.trim();

            if (!isNaN(aText) && !isNaN(bText)) {
                return direction === 'asc' ? aText - bText : bText - aText;
            }

            return direction === 'asc'
                ? aText.localeCompare(bText, undefined, { numeric: true })
                : bText.localeCompare(aText, undefined, { numeric: true });
        });

        rowsArray.forEach((row) => tbody.appendChild(row));
    }

    // Actualiza los íconos de ordenación
    function updateSortIcons(column, direction) {
        sortableColumns.forEach((col) => col.classList.remove('asc', 'desc'));
        column.classList.add(direction);
    }
});
