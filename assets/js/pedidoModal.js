let direccionTemporal = null; // Guardar dirección temporalmente

document.addEventListener("DOMContentLoaded", () => {
    const btnPedido = document.querySelector(".btn-hacer-pedido");
    if (!btnPedido) return;

    btnPedido.addEventListener("click", abrirPedidoModal);
});

function abrirPedidoModal() {

    const overlay = document.createElement("div");
    overlay.classList.add("pedido-overlay");

    const modal = document.createElement("div");
    modal.classList.add("pedido-modal");

    modal.innerHTML = `
        <div class="modal-header">
            <h2>Realizar Pedido</h2>
            <span class="modal-close-x">×</span>
        </div>

        <p class="modal-info">
            Si pides ahora, tu pedido será entregado en 
            <strong>3 a 10 días</strong>
        </p>

        <h3>Elige tu ubicación</h3>
        <div class="ubicacion-container" id="ubicacion-container">
            Cargando ubicaciones...
        </div>

        <button class="btn-add-ubicacion">Añadir ubicación</button>

        <!-- FORMULARIO DE DIRECCIÓN -->
        <div class="form-ubicacion hidden" id="form-ubicacion">
            <h3>Nueva Dirección</h3>
            
            <input type="text" id="ciudad" placeholder="Ciudad">
            <input type="text" id="distrito" placeholder="Distrito">
            <input type="text" id="nro_calle" placeholder="Calle / Número">
            <input type="text" id="referencia" placeholder="Referencia (opcional)">

            <button class="btn-guardar-ubicacion">Guardar ubicación</button>
        </div>

        <div class="pedido-final">
            <button class="btn-confirmar">Pedir</button>
        </div>
    `;

    document.body.appendChild(overlay);
    document.body.appendChild(modal);

    // Cerrar modal
    modal.querySelector(".modal-close-x").onclick = cerrarModal;
    overlay.onclick = cerrarModal;

    // Cargar ubicaciones del usuario
    fetch("../include/get_direcciones.php")
        .then(res => res.json())
        .then(data => mostrarDirecciones(data));

    // Mostrar el formulario de nueva ubicación
    modal.querySelector(".btn-add-ubicacion").addEventListener("click", () => {
        document.getElementById("form-ubicacion").classList.remove("hidden");
    });

    // Guardar ubicación temporal
    modal.querySelector(".btn-guardar-ubicacion").addEventListener("click", guardarUbicacionTemporal);

    // Enviar pedido final
    modal.querySelector(".btn-confirmar").addEventListener("click", enviarPedidoFinal);
}

function cerrarModal() {
    document.querySelector(".pedido-modal")?.remove();
    document.querySelector(".pedido-overlay")?.remove();
}

function mostrarDirecciones(data) {
    const cont = document.getElementById("ubicacion-container");

    if (!data.success || data.direcciones.length === 0) {
        cont.innerHTML = `<p>No tienes ubicaciones registradas.</p>`;
        return;
    }

    cont.innerHTML = data.direcciones.map(dir => `
        <label class="radio-dir">
            <input type="radio" name="direccion" value="${dir.id}">
            <span>${dir.ciudad}, ${dir.distrito}, ${dir.nro_calle}</span>
        </label>
    `).join("");
}

// ========================================================
//   GUARDAR UBICACIÓN TEMPORAL (NO BD)
// ========================================================
function guardarUbicacionTemporal() {
    const ciudad = document.getElementById("ciudad").value.trim();
    const distrito = document.getElementById("distrito").value.trim();
    const nro_calle = document.getElementById("nro_calle").value.trim();
    const referencia = document.getElementById("referencia").value.trim();

    if (!ciudad || !distrito || !nro_calle) {
        alert("Completa ciudad, distrito y calle.");
        return;
    }

    direccionTemporal = {
        ciudad,
        distrito,
        nro_calle,
        referencia
    };

    alert("Ubicación guardada correctamente (aún no en BD).");

    // Ocultar formulario
    document.getElementById("form-ubicacion").classList.add("hidden");

    // Limpiar selección actual
    document.querySelectorAll("input[name='direccion']").forEach(i => i.checked = false);
}

// ========================================================
//   ENVIAR PEDIDO FINAL
// ========================================================
function enviarPedidoFinal() {

    // Si elige una dirección existente
    const direccionSeleccionada = document.querySelector("input[name='direccion']:checked");

    let dataToSend = new URLSearchParams();

    if (direccionSeleccionada) {
        dataToSend.append("direccion", direccionSeleccionada.value);
    } 

    // Si se creó una dirección nueva
    else if (direccionTemporal) {
        dataToSend.append("ciudad", direccionTemporal.ciudad);
        dataToSend.append("distrito", direccionTemporal.distrito);
        dataToSend.append("nro_calle", direccionTemporal.nro_calle);
        dataToSend.append("referencia", direccionTemporal.referencia);
    }

    else {
        alert("Selecciona una dirección o añade una nueva.");
        return;
    }
    fetch("../include/realizar_pedido.php", {
        method: "POST",
        body: dataToSend
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);

        if (data.success) {
            cerrarModal();
            window.location.href = "../pages/productos.php";
        }
    })
    .catch(err => {
        console.error("Error:", err);
    });
}