document.addEventListener('DOMContentLoaded', function() {

    const searchInput = document.querySelector('.search-bar input');
    const searchButton = document.querySelector('.search-bar button');

    // Caja donde aparecerán los resultados
    const searchResults = document.createElement('div');
    searchResults.classList.add('search-results');
    document.body.appendChild(searchResults);

    function performSearch() {
        const query = searchInput.value.trim();

        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        fetch(`../include/search_products.php?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {

                searchResults.innerHTML = "";

                if (!data.success || data.productos.length === 0) {
                    searchResults.innerHTML = '<p>No se encontraron productos.</p>';
                    searchResults.style.display = 'block';
                    return;
                }

                data.productos.forEach(producto => {

                    const item = document.createElement('div');
                    item.classList.add('search-result-item');

                    item.innerHTML = `
                        <img src="${producto.imagen}" alt="${producto.nombre}">
                        <div>
                            <h4>${producto.nombre}</h4>
                            <p>${producto.categoria}</p>
                            <p>S/ ${producto.precio}</p>
                        </div>
                    `;

                    // Al hacer clic → abrir modal de modalProducto.js
                    item.addEventListener('click', () => {
                        openProductModal({
                            id_producto: producto.id,
                            nombre: producto.nombre,
                            descripcion: producto.descripcion,
                            imagen: producto.imagen,
                            precio: producto.precio,
                            stock: producto.stock
                        });
                        searchResults.style.display = "none";
                    });

                    searchResults.appendChild(item);
                });

                searchResults.style.display = 'block';
            })
            .catch(err => {
                console.error("Error en búsqueda:", err);
                searchResults.innerHTML = "<p>Error al buscar productos.</p>";
                searchResults.style.display = "block";
            });
    }

    // Eventos
    searchButton.addEventListener('click', performSearch);
    searchInput.addEventListener('input', performSearch);
    searchInput.addEventListener('focus', performSearch);

    // Ocultar cuando se hace clic afuera
    document.addEventListener('click', (e) => {
        if (!searchResults.contains(e.target) && !searchInput.contains(e.target)) {
            searchResults.style.display = "none";
        }
    });

});