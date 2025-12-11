let tablasDT = {}; // Para guardar las instancias DataTable

$(document).ready(function () {

    // Inicializar SOLO las tablas (no los DIV)
    $("table").each(function () {
        let idTabla = $(this).closest(".table-responsive").attr("id");

        tablasDT[idTabla] = $(this).DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 5,
            lengthChange: false
        });
    });

    // Mostrar "todos" al inicio
    mostrarTabla("todos");
});


// =============================
// FUNCION PARA CAMBIAR TABLA
// =============================
function mostrarTabla(tipo) {

    // Ocultar todas
    $(".table-responsive").hide();

    // Mostrar tabla elegida
    let idDiv = "tabla-" + tipo;
    $("#" + idDiv).show();

    // Recalcular total
    calcularTotal(idDiv);
}


// =============================
// CALCULAR TOTAL DE COSTOS
// =============================
function calcularTotal(idDiv) {

    let total = 0;

    // Obtener instancia DataTable correcta
    let tabla = tablasDT[idDiv];

    if (!tabla) return;

    // Recorrer filas visibles
    tabla.rows({ search: "applied" }).every(function () {
        let data = this.data();
        let costoCol = data[3]; // Columna costo ("S/ 10.50")

        if (costoCol && costoCol !== "-" && costoCol !== null) {
            let valor = parseFloat(costoCol.replace("S/", "").trim());
            if (!isNaN(valor)) total += valor;
        }
    });

    $("#totalMovimientos").text("Total: S/ " + total.toFixed(2));
}
