<body>

<!-- TOP BAR -->
<div class="top-bar">
    <div class="top-left">
        <a href="../pages/botonOrdenCompra.php" class="back-button">&#8592;</a>
        <span class="welcome-message">Crear Orden de Compra</span>
    </div>

    <div class="user-box">
        <a href="../include/logoutEmpleado.php" class="logout-button">Cerrar sesión</a>
        <img src="https://www.w3schools.com/howto/img_avatar.png" class="user-avatar-small" alt="Avatar">
    </div>
</div>

<div class="container-oc">

    <h2>Crear Orden de Compra</h2>

    <!-- Seleccionar proveedor -->
    <div class="form-row">
        <label>Proveedor:</label>
        <select id="proveedor">
            <option value="">Seleccione proveedor</option>
            <?php while($p = $proveedores->fetch_assoc()): ?>
                <option value="<?= $p['id_provdor'] ?>"><?= $p['nombre'] ?></option>
            <?php endwhile ?>
        </select>
    </div>

    <!-- Seleccionar producto + nuevo -->
    <div class="form-row">
        <label>Producto:</label>
        <select id="producto_select">
            <option value="">Seleccione producto</option>
            <?php while($pr = $productos->fetch_assoc()): ?>
                <option value="<?= $pr['id_producto'] ?>"><?= $pr['nombre'] ?></option>
            <?php endwhile ?>
        </select>
        <button class="btn-green" id="btnNuevoProducto">Nuevo producto</button>
    </div>

    <div class="form-row">
        <label>Cantidad:</label>
        <input type="number" id="cantidad" min="1" value="1">

        <label>Precio unitario:</label>
        <input type="number" id="precio_u" min="0" step="0.01">

        <button class="btn-blue" onclick="agregarItem()">Agregar</button>
    </div>

    <hr>

    <h3>Detalle temporal de la OC</h3>
    <div id="tabla_detalle" class="detalle-box"></div>

    <h3>Total: S/ <span id="total_oc">0.00</span></h3>

    <button id="btnFinalizar" class="btn-final">Ordenar Compra</button>

</div>

<!-- ============================
      MODAL CATEGORÍA
============================ -->
<div id="modalCategoria" class="modal">
    <div class="modal-content">
        <h3>Nueva Categoría</h3>

        <label>Nombre:</label>
        <input type="text" id="cat_nombre">

        <label>Descripción:</label>
        <textarea id="cat_desc"></textarea>

        <button class="btn-green" onclick="guardarCategoria()">Guardar</button>
        <button class="close" onclick="cerrarModal('modalCategoria')">Cerrar</button>
    </div>
</div>

<!-- ============================
      MODAL PRODUCTO
============================ -->
<div id="modalProducto" class="modal">
    <div class="modal-content">
        <h3>Nuevo Producto</h3>

        <label>Categoría:</label>
        <select id="prod_categoria">
            <?php while ($c = $categorias->fetch_assoc()): ?>
                <option value="<?= $c['id_catgria'] ?>"><?= $c['nombre'] ?></option>
            <?php endwhile; ?>
        </select>

        <button class="btn-green" onclick="abrirModal('modalCategoria')">Nueva categoría</button>
        <br><br>

        <label>Nombre:</label>
        <input type="text" id="prod_nombre">

        <label>Descripción:</label>
        <textarea id="prod_desc"></textarea>

        <label>Perecible:</label>
        <select id="prod_perecible">
            <option value="0">No</option>
            <option value="1">Sí</option>
        </select>

        <label>Fecha vencimiento (si aplica):</label>
        <input type="date" id="prod_fecha_v">

        <label>Garantía (meses):</label>
        <input type="number" id="prod_garantia" min="0">

        <button class="btn-blue" onclick="guardarProducto()">Guardar</button>
        <button class="close" onclick="cerrarModal('modalProducto')">Cerrar</button>
    </div>
</div>

<script src="../assets/js/ordenCompra.js"></script>
</body>