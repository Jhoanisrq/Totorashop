document.addEventListener('DOMContentLoaded', () => {

    const cont = document.querySelector('.productos-grid');

    fetch('../includes/getProductosCompra.php')
        .then(res => res.json())
        .then(productos => {
            productos.forEach(p => {
                const div = document.createElement('div');
                div.className = 'producto';
                div.innerHTML = `
                    <img src="../imagenes/${p.imagen}">
                    <h3>${p.nombre}</h3>
                    <div class="price">S/ ${p.precio}</div>
                `;
                div.onclick = () => abrirModal(p);
                cont.appendChild(div);
            });
        });

    function abrirModal(p) {

        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';

        const modal = document.createElement('div');
        modal.className = 'modal';

        modal.innerHTML = `
            <div class="modal-content">
                <span class="modal-close">×</span>
                
                <div class="modal-left">
                    <img src="../imagenes/${p.imagen}">
                </div>

                <div class="modal-right">
                    <h2>${p.nombre}</h2>
                    <p class="modal-price">Precio: S/ ${p.precio}</p>
                    <p><strong>Stock disponible: ${p.stock_total}</strong></p>

                    <div class="modal-description">
                        <h3>Descripción:</h3>
                        <p>${p.descripcion}</p>
                    </div>

                    <div>
                        <h3>Cantidad:</h3>
                        <input type="number" id="cant" class="modal-quantity" min="1" value="1">
                    </div>

                    <button class="modal-add-to-cart">Agregar a pedido</button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);
        document.body.appendChild(modal);

        modal.querySelector('.modal-close').onclick = () => {
            modal.remove();
            overlay.remove();
        };

        overlay.onclick = () => {
            modal.remove();
            overlay.remove();
        };

        modal.querySelector('.modal-add-to-cart').onclick = () => {
            const cantidad = document.getElementById('cant').value;

            fetch('../includes/add_to_temp_pedido.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id_producto=${p.id_producto}&cantidad=${cantidad}`
            })
            .then(r => r.text())
            .then(msg => {
                alert("Agregado al pedido");
                modal.remove();
                overlay.remove();
            });
        };
    }

});
