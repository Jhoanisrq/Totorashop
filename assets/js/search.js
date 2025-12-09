document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-bar input');
    const searchButton = document.querySelector('.search-bar button');
    const searchResults = document.createElement('div');
    searchResults.classList.add('search-results');
    document.body.appendChild(searchResults);

    function performSearch() {
        const query = searchInput.value.trim();
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        fetch(`../includes/search_products.php?query=${encodeURIComponent(query)}`)
        //fetch(`../includes/search_products.php?query=${encodeURIComponent(query)}`)
            .then(response => {
                if (!response.ok) throw new Error('Error en la búsqueda');
                return response.json();
            })
            .then(data => {
                searchResults.innerHTML = '';
                if (!data.success || data.productos.length === 0) {
                    searchResults.innerHTML = '<p>No se encontraron productos.</p>';
                    searchResults.style.display = 'block';
                    return;
                }

                data.productos.forEach(producto => {
                    const resultItem = document.createElement('div');
                    resultItem.classList.add('search-result-item');
                    resultItem.innerHTML = `
                        <img src="${producto.imagen}" alt="${producto.nombre}">
                        <div>
                            <h4>${producto.nombre}</h4>
                            <p>${producto.categoria}</p>
                            <p>S/ ${producto.price}</p>
                        </div>
                    `;
                    resultItem.addEventListener('click', () => {
                        openProductModal(producto);
                        searchResults.style.display = 'none';
                    });
                    searchResults.appendChild(resultItem);
                });

                searchResults.style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                searchResults.innerHTML = '<p>Error al realizar la búsqueda.</p>';
                searchResults.style.display = 'block';
            });
    }

    searchButton.addEventListener('click', performSearch);
    searchInput.addEventListener('input', performSearch);
    searchInput.addEventListener('focus', performSearch);

    document.addEventListener('click', (e) => {
        if (!searchResults.contains(e.target) && !searchInput.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    // Reutilizar la función openProductModal (de scripts.js o scriptCatg.js)
    function openProductModal(producto) {
        const overlay = document.createElement('div');
        overlay.classList.add('modal-overlay');

        const modal = document.createElement('div');
        modal.classList.add('modal');

        const ratingHTML = `
            <div class="modal-rating">
                <span>★★★★★</span>
            </div>
        `;

        modal.innerHTML = `
            <div class="modal-content">
                <span class="modal-close">×</span>
                <div class="modal-left">
                    <img src="${producto.imagen}" alt="${producto.nombre}">
                </div>
                <div class="modal-right">
                    <h2>${producto.nombre}</h2>
                    ${ratingHTML}
                    <p class="modal-price">Costo: S/ ${producto.price}</p>
                    <div class="modal-description">
                        <h3>Descripción:</h3>
                        <p>${producto.descripcion}</p>
                    </div>
                    <div class="modal-options">
                        <h3>Cantidad:</h3>
                        <input type="number" class="modal-quantity" value="1" min="1">
                    </div>
                    <button class="modal-add-to-cart" data-id="${producto.id}">Agregar al carrito</button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);
        document.body.appendChild(modal);

        const closeBtn = modal.querySelector('.modal-close');
        closeBtn.addEventListener('click', () => {
            modal.remove();
            overlay.remove();
        });

        overlay.addEventListener('click', () => {
            modal.remove();
            overlay.remove();
        });

        const addToCartBtn = modal.querySelector('.modal-add-to-cart');
        addToCartBtn.addEventListener('click', () => {
            const cantidad = parseInt(modal.querySelector('.modal-quantity').value);
            fetch('../includes/add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id_producto=${producto.id}&cantidad=${cantidad}`
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        modal.remove();
                        overlay.remove();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al añadir al carrito');
                });
        });
    }
});