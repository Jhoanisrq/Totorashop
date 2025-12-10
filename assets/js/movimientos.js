$(document).ready(function() {
    let dataTables = {}; // Guardar instancias de DataTables

    // Función para inicializar DataTable si aún no está
    function initDataTable(id) {
        if (!dataTables[id]) {
            dataTables[id] = $('#' + id).DataTable({
                responsive: true,
                language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
            });
        }
    }

    // Función para mostrar tabla según tipo y calcular total
    window.mostrarTabla = function(tipo) {
        // Ocultar todas
        $('#tabla-todos, #tabla-entrada, #tabla-salida, #tabla-ajuste').hide();

        // Mostrar seleccionada
        let tablaId = 'tabla-' + tipo;
        $('#' + tablaId).show();

        // Inicializar DataTable si no existe
        initDataTable(tablaId);

        // Calcular total a partir de la columna Costo (índice 3)
        let total = 0;
        dataTables[tablaId].rows({ search: 'applied' }).every(function() {
            let col4 = this.data()[3]; // columna 4
            if (col4) {
                col4 = col4.toString().replace('S/', '').replace(/\s/g,'').replace(',','.');
                let val = parseFloat(col4);
                if (!isNaN(val)) total += val;
            }
        });

        $('#totalMovimientos').text('Total: S/ ' + total.toFixed(2));
    }

    // Inicial: mostrar Todos
    mostrarTabla('todos');
});
