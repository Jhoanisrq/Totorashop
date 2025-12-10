function cargarTabla(detalle, total) {
    let html = `
        <table border="1">
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>PU</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
    `;

    detalle.forEach((item, index) => {
        html += `
            <tr>
                <td>${item.nombre}</td>
                <td>${item.cantidad}</td>
                <td>${item.precio_u}</td>
                <td>${item.subtotal.toFixed(2)}</td>
                <td><button onclick="eliminarItem(${index})">X</button></td>
            </tr>
        `;
    });

    html += `</table>`;

    document.getElementById("tabla_detalle").innerHTML = html;
    document.getElementById("total_oc").textContent = total;
}

function agregarItem() {
    const id = document.getElementById("producto_select").value;
    const nombre = document.getElementById("producto_select").options[document.getElementById("producto_select").selectedIndex].text;
    const cantidad = document.getElementById("cantidad").value;
    const precio_u = document.getElementById("precio_u").value;

    if (!id || cantidad <= 0 || precio_u <= 0) {
        alert("Complete todos los campos");
        return;
    }

    const datos = new FormData();
    datos.append("action", "add");
    datos.append("id_producto", id);
    datos.append("nombre", nombre);
    datos.append("cantidad", cantidad);
    datos.append("precio_u", precio_u);

    fetch("temp_oc.php", {
        method: "POST",
        body: datos
    })
    .then(res => res.json())
    .then(data => cargarTabla(data.detalle, data.total));
}

function eliminarItem(index) {
    const datos = new FormData();
    datos.append("action", "delete");
    datos.append("index", index);

    fetch("temp_oc.php", {
        method: "POST",
        body: datos
    })
    .then(res => res.json())
    .then(data => cargarTabla(data.detalle, data.total));
}

document.getElementById("btnFinalizar").onclick = () => {
    const proveedor = document.getElementById("proveedor").value;

    if (!proveedor) {
        alert("Seleccione proveedor");
        return;
    }

    const datos = new FormData();
    datos.append("proveedor", proveedor);

    fetch("finalizar_orden_compra.php", {
        method: "POST",
        body: datos
    })
    .then(res => res.json())
    .then(data => {
    if (data.success) {
            alert("Orden de compra registrada ✔");
            window.location.href = "../pages/botonOrdenCompra.php";
        } else {
            alert("Error: " + data.message);
        }
    });
};
// ======================
// MODALES
// ======================
function abrirModal(id) {
    document.getElementById(id).style.display = "block";
}

function cerrarModal(id) {
    document.getElementById(id).style.display = "none";
}

document.getElementById("prod_perecible").onchange = () => {
    const per = document.getElementById("prod_perecible").value;

    if (per === "1") {
        document.getElementById("campo_fecha").style.display = "block";
        document.getElementById("campo_garantia").style.display = "none";
    } else {
        document.getElementById("campo_fecha").style.display = "none";
        document.getElementById("campo_garantia").style.display = "block";
    }
};

// ======================
// GUARDAR CATEGORÍA
// ======================
function guardarCategoria() {
    const nombre = document.getElementById("cat_nombre").value;
    const desc = document.getElementById("cat_desc").value;

    if (!nombre.trim()) {
        alert("Ingrese nombre");
        return;
    }

    const datos = new FormData();
    datos.append("nombre", nombre);
    datos.append("descripcion", desc);

    fetch("crear_categoria_ajax.php", {
        method: "POST",
        body: datos
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            // Agregar a select de categorías del modal producto
            const sel = document.getElementById("prod_categoria");
            sel.innerHTML += `<option value="${d.id}" selected>${d.nombre}</option>`;
            alert("Categoría creada ✔");
            cerrarModal("modalCategoria");
        }
    });
}


// ======================
// GUARDAR PRODUCTO
// ======================
function guardarProducto() {
    const datos = new FormData();
    datos.append("categoria", document.getElementById("prod_categoria").value);
    datos.append("nombre", document.getElementById("prod_nombre").value);
    datos.append("descripcion", document.getElementById("prod_desc").value);
    datos.append("perecible", document.getElementById("prod_perecible").value);
    datos.append("fecha_v", document.getElementById("prod_fecha_v").value);
    datos.append("garantia", document.getElementById("prod_garantia").value);

    const imgFile = document.getElementById("prod_imagen").files[0];
    if (imgFile) datos.append("imagen", imgFile);

    fetch("crear_producto_ajax.php", {
        method: "POST",
        body: datos
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            const sel = document.getElementById("producto_select");
            sel.innerHTML += `<option value="${d.id}" selected>${d.nombre}</option>`;
            alert("Producto creado ✔");
            cerrarModal("modalProducto");
        }
    });
}

// Abrir modal producto con botón
document.getElementById("btnNuevoProducto").onclick = () => {
    abrirModal("modalProducto");
};

function cancelarProducto() {
    document.getElementById("prod_nombre").value = "";
    document.getElementById("prod_desc").value = "";
    document.getElementById("prod_imagen").value = "";
    cerrarModal("modalProducto");
}