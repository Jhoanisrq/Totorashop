document.addEventListener("DOMContentLoaded", () => {
    const contenedor = document.getElementById("productos-categoria");
    const idCat = contenedor.dataset.id;

    fetch(`../include/getProductosPorCategoria.php?id=${idCat}`)
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
                contenedor.appendChild(div);
            });
        })
        .catch(err => console.error("Error:", err));
});
