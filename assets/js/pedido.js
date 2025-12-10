document.addEventListener("DOMContentLoaded", () => {

    const pedidoBody = document.getElementById("pedido-body");
    const totalPrecio = document.getElementById("total-precio");

    // ====================== CAMBIAR CANTIDAD ======================
    pedidoBody.addEventListener("input", e => {
        if (e.target.classList.contains("pedido-cantidad")) {
            const fila = e.target.closest("tr");
            const id = fila.dataset.id;
            const cantidad = parseInt(e.target.value);

            fetch("../include/update_pedido.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${id}&cantidad=${cantidad}`
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    fila.querySelector(".pedido-subtotal").textContent = "S/ " + data.subtotal;
                    totalPrecio.textContent = data.total;
                    actualizarContadorHeader();
                }
            });
        }
    });

    // ====================== ELIMINAR PRODUCTO ======================
    pedidoBody.addEventListener("click", e => {
        if (e.target.closest(".btn-eliminar")) {
            const fila = e.target.closest("tr");
            const id = fila.dataset.id;

            fetch("../include/eliminar_pedido.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${id}`
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    fila.remove();
                    totalPrecio.textContent = data.total;
                    actualizarContadorHeader();
                }
            });
        }
    });

});

function actualizarContadorHeader() {
    fetch("../include/get_pedido_count.php")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("pedido-count").textContent = data.count;
            }
        });
}