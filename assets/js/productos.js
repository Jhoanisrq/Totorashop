document.addEventListener("DOMContentLoaded", () => {
    const productosGrid = document.querySelector(".productos-grid");

    if (!productosGrid) {
        console.error("No se encontró el contenedor .productos-grid");
        return;
    }

    fetch("../include/getProductosCompra.php")
        .then(res => res.json())
        .then(productos => {
            productos.forEach(prod => {
                const div = document.createElement("div");
                div.classList.add("producto");

                div.innerHTML = `
                    <img src="${prod.imagen}" alt="${prod.nombre}">
                    <h3>${prod.nombre}</h3>
                    <p class="precio">S/. ${parseFloat(prod.precio).toFixed(2)}</p>
                `;

                div.addEventListener("click", () => abrirModal(prod));
                productosGrid.appendChild(div);
            });
        })
        .catch(err => console.error("Error cargando productos:", err));
});

// =============================
// ====== MODAL PRODUCTO ======
// =============================

function abrirModal(prod) {
    const overlay = document.createElement("div");
    overlay.classList.add("modal-overlay");

    const modal = document.createElement("div");
    modal.classList.add("modal");

    modal.innerHTML = `
        <div class="modal-content">
            <span class="modal-close">×</span>

            <div class="modal-left">
                <img class="modal-image" src="${prod.imagen}" alt="${prod.nombre}">
            </div>

            <div class="modal-right">
                <h2>${prod.nombre}</h2>

                <p class="modal-description"><strong>Descripción:</strong><br>${prod.descripcion}</p>

                <p class="modal-price"><strong>Precio:</strong> S/ ${parseFloat(prod.precio).toFixed(2)}</p>

                <p class="modal-stock"><strong>Stock disponible:</strong> ${prod.stock_total}</p>

                <label><strong>Cantidad:</strong></label>
                <input type="number" class="modal-quantity" value="1" min="1" max="${prod.stock_total}">

                <button class="modal-add-to-pedido">Agregar al pedido</button>
            </div>
        </div>
    `;

    document.body.appendChild(overlay);
    document.body.appendChild(modal);

    // Cerrar modal
    modal.querySelector(".modal-close").addEventListener("click", () => {
        modal.remove();
        overlay.remove();
    });

    overlay.addEventListener("click", () => {
        modal.remove();
        overlay.remove();
    });

    // ================================
    //         ZOOM DE IMAGEN
    // ================================
    const modalImage = modal.querySelector(".modal-image");

    modalImage.addEventListener("click", () => {
        const zoomOverlay = document.createElement("div");
        zoomOverlay.classList.add("modal-overlay");

        const zoomModal = document.createElement("div");
        zoomModal.classList.add("image-zoom-modal");

        zoomModal.innerHTML = `
            <span class="modal-close zoom-close">×</span>
            <img src="${prod.imagen}" alt="${prod.nombre}">
        `;

        document.body.appendChild(zoomOverlay);
        document.body.appendChild(zoomModal);

        // Cerrar zoom
        zoomModal.querySelector(".zoom-close").addEventListener("click", () => {
            zoomModal.remove();
            zoomOverlay.remove();
        });

        zoomOverlay.addEventListener("click", () => {
            zoomModal.remove();
            zoomOverlay.remove();
        });
    });

    // Botón (todavía sin función real)
    modal.querySelector(".modal-add-btn").addEventListener("click", () => {
        alert("Producto agregado al pedido (aún sin lógica)");
    });
}