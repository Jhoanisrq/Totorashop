<?php
//agregar_orden_compra.php
session_start();
require_once "../include/db_connect.php";

if (!isset($_SESSION['oc_tmp'])) {
    $_SESSION['oc_tmp'] = [];
}

$proveedores = $conn->query("SELECT id_provdor, nombre FROM proveedor");
$productos   = $conn->query("SELECT id_producto, nombre FROM producto");
$categorias  = $conn->query("SELECT id_catgria, nombre FROM categoria");

if (!isset($_SESSION['id_empleado'])) {
    die("Error: no hay sesión iniciada para el empleado.");
}

$idEmpleado = intval($_SESSION['id_empleado']);
$q = $conn->query("SELECT id_almcen FROM empleado WHERE id_empldo = $idEmpleado");
if (!$q || $q->num_rows === 0) {
    die("Error: no se encontró el almacén del empleado.");
}
$almacenEmpleado = $q->fetch_assoc()['id_almcen'];

// Nombre para mostrar en top-bar (si existe)
$nombreEmpleado = htmlspecialchars($_SESSION['nombre_empleado'] ?? ($_SESSION['nombre'] ?? 'Empleado'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Orden de Compra</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="../assets/css/orden_compra.css">
<link rel="stylesheet" href="../assets/css/empleadoStyle.css">
</head>

<body class="w3-light-grey">

<!-- TOP BAR -->
<div class="top-bar">
    <div class="top-left">
        <a href="../pages/botonOrdenCompra.php" class="back-button">&#8592;</a>
        <span class="welcome-message">Crear Orden de Compra</span>
    </div>
    <div class="user-box">
        <span style="color:#fff; margin-right:12px; font-weight:600;"><?= $nombreEmpleado ?></span>
        <a href="../include/logoutEmpleado.php" class="logout-button">Cerrar sesión</a>
        <img src="https://www.w3schools.com/howto/img_avatar.png" class="user-avatar-small" alt="Avatar">
    </div>
</div>

<!-- MAIN CARD -->
<div class="w3-card w3-white w3-padding w3-margin w3-round-large" style="max-width:100%;margin:20px auto;">
    <h2 class="w3-center w3-text-teal">Crear Orden de Compra</h2>

    <div class="w3-section">
        <label class="w3-text-black">Proveedor:</label>
        <select id="proveedor" class="w3-select w3-border w3-round">
            <option value="">Seleccione proveedor</option>
            <?php while($p = $proveedores->fetch_assoc()): ?>
                <option value="<?= $p['id_provdor'] ?>"><?= $p['nombre'] ?></option>
            <?php endwhile ?>
        </select>
    </div>

    <div class="w3-section">
        <label class="w3-text-black">Producto:</label>
        <select id="producto_select" class="w3-select w3-border w3-round">
            <option value="">Seleccione producto</option>
            <?php while($pr = $productos->fetch_assoc()): ?>
                <option value="<?= $pr['id_producto'] ?>"><?= $pr['nombre'] ?></option>
            <?php endwhile ?>
        </select>
        <button id="btnNuevoProducto" class="w3-button w3-blue w3-round w3-small w3-margin-top">Nuevo producto</button>
    </div>

    <div class="w3-row-padding">
        <div class="w3-half">
            <label>Cantidad:</label>
            <input type="number" id="cantidad" min="1" value="1" class="w3-input w3-border w3-round">
        </div>

        <div class="w3-half">
            <label>Precio unitario:</label>
            <input type="number" id="precio_u" min="0" step="0.01" class="w3-input w3-border w3-round">
        </div>
    </div>

    <button onclick="agregarItem()" class="w3-button w3-green w3-margin-top w3-round w3-block">Agregar</button>

    <hr>

    <h3 class="w3-text-teal">Detalle temporal de la OC</h3>
    <div id="tabla_detalle" class="w3-responsive w3-margin-bottom"></div>

    <h3 class="w3-text-teal">Total: S/ <span id="total_oc">0.00</span></h3>

    <button id="btnFinalizar" class="w3-button w3-teal w3-round w3-xlarge w3-block">Ordenar Compra</button>
</div>

<!-- MODAL CATEGORÍA -->
<div id="modalCategoria" class="w3-modal">
    <div class="w3-modal-content w3-round-large w3-padding">
        <h3>Nueva Categoría</h3>

        <label>Nombre:</label>
        <input type="text" id="cat_nombre" class="w3-input w3-border w3-round">

        <label>Descripción:</label>
        <textarea id="cat_desc" class="w3-input w3-border w3-round"></textarea>

        <button onclick="guardarCategoria()" class="w3-button w3-green w3-round w3-margin-top">Guardar</button>
        <button onclick="cerrarModal('modalCategoria')" class="w3-button w3-red w3-round w3-margin-top">Cerrar</button>
    </div>
</div>

<!-- MODAL PRODUCTO -->
<div id="modalProducto" class="w3-modal">
    <div class="w3-modal-content w3-round-large w3-padding">
        <h3>Nuevo Producto</h3>

        <label>Categoría:</label>
        <select id="prod_categoria" class="w3-select w3-border w3-round">
            <?php
            // Re-query categorias because the previous loop consumed it
            $cats2 = $conn->query("SELECT id_catgria, nombre FROM categoria");
            while ($c = $cats2->fetch_assoc()): ?>
                <option value="<?= $c['id_catgria'] ?>"><?= $c['nombre'] ?></option>
            <?php endwhile; ?>
        </select>

        <button onclick="abrirModal('modalCategoria')" class="w3-button w3-blue w3-small w3-round w3-margin-top">Nueva categoría</button>

        <label>Nombre:</label>
        <input type="text" id="prod_nombre" class="w3-input w3-border w3-round">

        <label>Descripción:</label>
        <textarea id="prod_desc" class="w3-input w3-border w3-round"></textarea>

        <label>Imagen:</label>
            <input type="file" id="prod_imagen" accept="image/*" class="w3-input w3-border w3-round">
        
        <label>Perecible:</label>
            <select id="prod_perecible" class="w3-select w3-border w3-round">
                <option value="0">No</option>
                <option value="1">Sí</option>
            </select>

            <div id="campo_fecha">
                <label>Fecha vencimiento:</label>
                <input type="date" id="prod_fecha_v" class="w3-input w3-border w3-round">
            </div>

            <div id="campo_garantia">
                <label>Garantía (meses):</label>
                <input type="number" id="prod_garantia" min="0" class="w3-input w3-border w3-round">
            </div>

        <button onclick="guardarProducto()" class="w3-button w3-green w3-round w3-margin-top">Guardar</button>
        <button onclick="cerrarModal('modalProducto')" class="w3-button w3-red w3-round w3-margin-top">Cerrar</button>
    </div>
</div>

<script src="../assets/js/ordenCompra.js"></script>

</body>
</html>