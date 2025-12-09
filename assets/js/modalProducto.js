function abrirModal(prod) {
    // --- Soporte flexible para distintos nombres de campo ---
    const imagen = prod.imagen || prod.image || prod.imagen_url || prod.imagenUrl || "";
    const nombre = prod.nombre || prod.name || "";
    const descripcion = prod.descripcion || prod.description || "";
    const precioNum = parseFloat(prod.precio || prod.price || prod.prcio_untr || 0);
    const precioStr = isNaN(precioNum) ? "0.00" : precioNum.toFixed(2);
    const stock_total = (typeof prod.stock_total !== "undefined") ? prod.stock_total
                      : (typeof prod.stock !== "undefined") ? prod.stock
                      : (typeof prod.cantidad !== "undefined") ? prod.cantidad
                      : 0;

    // Crear overlay y modal
    const overlay = document.createElement("div");
    overlay.classList.add("modal-overlay");

    const modal = document.createElement("div");
    modal.classList.add("modal");

    modal.innerHTML = `
        <div class="modal-content">
            <span class="modal-close" role="button" aria-label="Cerrar">×</span>

            <div class="modal-left">
                <img class="modal-image" src="${imagen}" alt="${escapeHtml(nombre)}">
            </div>

            <div class="modal-right">
                <h2>${escapeHtml(nombre)}</h2>

                <p class="modal-description"><strong>Descripción:</strong><br>${escapeHtml(descripcion)}</p>

                <p class="modal-price"><strong>Precio:</strong> S/ ${precioStr}</p>

                <p class="modal-stock"><strong>Stock disponible:</strong> ${stock_total}</p>

                <label><strong>Cantidad:</strong></label>
                <input type="number" class="modal-quantity" value="1" min="1" max="${stock_total}">

                <button class="modal-add-to-pedido">Agregar al pedido</button>
            </div>
        </div>
    `;

    document.body.appendChild(overlay);
    document.body.appendChild(modal);

    // Cerrar modal
    const closeModal = () => {
        modal.remove();
        overlay.remove();
    };

    modal.querySelector(".modal-close").addEventListener("click", closeModal);
    overlay.addEventListener("click", closeModal);

    // ===== ZOOM =====
    const modalImage = modal.querySelector(".modal-image");
    if (modalImage) {
        modalImage.style.cursor = "zoom-in";
        modalImage.addEventListener("click", () => {
            const zoomOverlay = document.createElement("div");
            zoomOverlay.classList.add("modal-overlay");

            const zoomModal = document.createElement("div");
            zoomModal.classList.add("image-zoom-modal");

            zoomModal.innerHTML = `
                <span class="modal-close zoom-close" role="button" aria-label="Cerrar">×</span>
                <img src="${imagen}" alt="${escapeHtml(nombre)}">
            `;

            document.body.appendChild(zoomOverlay);
            document.body.appendChild(zoomModal);

            const closeZoom = () => {
                zoomModal.remove();
                zoomOverlay.remove();
            };

            zoomModal.querySelector(".zoom-close").addEventListener("click", closeZoom);
            zoomOverlay.addEventListener("click", closeZoom);
        });
    }

    // Botón (todavía sin lógica de pedido)
    const addBtn = modal.querySelector(".modal-add-to-pedido");
    if (addBtn) {
        addBtn.addEventListener("click", () => {
            const qtyInput = modal.querySelector(".modal-quantity");
            const cantidad = qtyInput ? parseInt(qtyInput.value || "1", 10) : 1;
            if (cantidad <= 0 || cantidad > Number(stock_total)) {
                alert("Cantidad inválida o mayor al stock disponible.");
                return;
            }
            alert(`Producto agregado (sin lógica aún). Cantidad: ${cantidad}`);
            // aquí luego iría la llamada fetch para guardar en sesión
            closeModal();
        });
    }
}

// Alias en inglés para compatibilidad con search.js que llama openProductModal(...)
function openProductModal(prod) {
    return abrirModal(prod);
}

// Hacemos ambas funciones globales (por si se cargan en diferentes contextos)
window.abrirModal = abrirModal;
window.openProductModal = openProductModal;

// --- Helper: escape simple para evitar inyección HTML en el modal ---
function escapeHtml(str) {
    if (!str) return "";
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}