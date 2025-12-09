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