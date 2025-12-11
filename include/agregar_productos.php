<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: ../pages/login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("db_connect.php");

// =======================
// Crear nueva categoría
// =======================
if (isset($_POST['nueva_categoria'])) {
    $nombre_cat = trim($_POST['nombre_cat']);
    $descripcion_cat = trim($_POST['descripcion_cat'] ?? '');

    if ($nombre_cat !== "") {
        $stmtCat = $conn->prepare("INSERT INTO categoria (nombre, descripcion) VALUES (?, ?)");
        $stmtCat->bind_param("ss", $nombre_cat, $descripcion_cat);
        $stmtCat->execute();
    }
}

// =======================
// Obtener categorías y almacenes
// =======================
$categorias = [];
$res = $conn->query("SELECT id_catgria, nombre FROM categoria");
while($row = $res->fetch_assoc()){
    $categorias[] = $row;
}

$almacenes = [];
$res = $conn->query("SELECT id_almcen, nombre FROM almacen");
while($row = $res->fetch_assoc()){
    $almacenes[] = $row;
}

$mensaje = "";

// =======================
// Procesar agregar producto
// =======================
if (isset($_POST['nombre'])) {

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $id_catgria = intval($_POST['id_catgria']);
    $perecible = isset($_POST['perecible']) ? 1 : 0;
    $fch_vncmnto = $_POST['fch_vncmnto'] ?? null;
    $grntia_meses = !empty($_POST['grntia_meses']) ? intval($_POST['grntia_meses']) : null;
    $id_almcen = intval($_POST['id_almcen']);
    $cantidad = intval($_POST['cantidad']);

    // ===========================
    // Manejo de imagen
    // ===========================
    $folder = "../assets/img/productos/";
    if (!file_exists($folder)) mkdir($folder, 0777, true);

    $imagen = null;
    if (!empty($_FILES['imagen']['name'])) {
        $img_name = time() . "_" . basename($_FILES['imagen']['name']);
        $ruta_guardar = $folder . $img_name;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_guardar)) {
            $imagen = "../assets/img/productos/" . $img_name;
        }
    }

    $fecha_rgstro = date("Y-m-d");

    // ===========================
    // Insertar producto
    // ===========================
    $stmt = $conn->prepare("
        INSERT INTO producto (nombre, descripcion, imagen, id_catgria, perecible, fch_vncmnto, grntia_meses, fecha_rgstro)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "sssiiiss",
        $nombre, $descripcion, $imagen, $id_catgria, $perecible, $fch_vncmnto, $grntia_meses, $fecha_rgstro
    );

    if ($stmt->execute()) {
        $id_producto = $stmt->insert_id;

        // ===========================
        // Insertar inventario
        // ===========================
        $stmtInv = $conn->prepare("
            INSERT INTO inventario (id_producto, id_almcen, cantidad)
            VALUES (?, ?, ?)
        ");
        $stmtInv->bind_param("iii", $id_producto, $id_almcen, $cantidad);
        $stmtInv->execute();

        $mensaje = "Producto registrado correctamente.";
    } else {
        $mensaje = "Error al registrar el producto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar producto</title>
<link rel="stylesheet" href="../assets/css/empleadoStyle.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>

<div class="top-bar">
    <div class="top-left">
        <a href="../pages/botonInventario.php" class="back-button">&#8592;</a>
        <span class="welcome-message">Agregar producto</span>
    </div>
</div>

<div class="w3-container w3-padding">
    <?php if ($mensaje): ?>
        <p class="w3-text-green"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <div class="w3-card-4 w3-white w3-padding">
        <form method="POST" enctype="multipart/form-data">

            <h4>Datos del producto</h4>

            <label>Nombre:</label>
            <input class="w3-input w3-margin-bottom" name="nombre" required>
                
            <label>Descripción:</label>
            <input class="w3-input w3-margin-bottom" name="descripcion">
                
            <!-- CATEGORÍA -->
            <label>Categoría:</label>
            <div style="display:flex; gap:10px; align-items:center; margin-bottom:10px;">
                <select class="w3-select" name="id_catgria" id="select-categoria" required style="flex:1;">
                    <option value="" disabled selected>Selecciona una categoría</option>
                    <?php foreach($categorias as $c): ?>
                        <option value="<?= $c['id_catgria'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                    <?php endforeach; ?>
                    <option value="crear">➕ Crear nueva categoría</option>
                </select>
            </div>
                    
            <!-- FORMULARIO OCULTO PARA CREAR CATEGORÍA -->
            <div id="crear-categoria-box" class="w3-padding w3-border" style="display:none; margin-bottom:15px;">
                <h5>Crear nueva categoría</h5>
                    
                <label>Nombre categoría:</label>
                <input class="w3-input w3-margin-bottom" name="nombre_cat" id="nombre_cat">
                    
                <label>Descripción (opcional):</label>
                <input class="w3-input w3-margin-bottom" name="descripcion_cat" id="descripcion_cat">
                    
                <button type="button" class="w3-button w3-blue" id="btn-guardar-categoria">Guardar categoría</button>
            </div>
                    
                    
            <!-- PERECIBLE -->
            <label>Perecible:</label>
            <input type="checkbox" name="perecible" id="perecible-checkbox" class="w3-check">
                    
            <!-- CAMPOS QUE CAMBIAN DEPENDIENDO SI ES PERECIBLE -->
            <div id="campo-vencimiento" style="display:none;">
                <label>Fecha de vencimiento:</label>
                <input class="w3-input w3-margin-bottom" type="date" name="fch_vncmnto">
            </div>
                    
            <div id="campo-garantia">
                <label>Garantía (meses):</label>
                <input class="w3-input w3-margin-bottom" type="number" name="grntia_meses" min="0">
            </div>
                    
            <!-- ALMACÉN -->
            <label>Almacén:</label>
            <select class="w3-select w3-margin-bottom" name="id_almcen" required>
                <option disabled selected>Selecciona un almacén</option>
                <?php foreach($almacenes as $a): ?>
                    <option value="<?= $a['id_almcen'] ?>"><?= htmlspecialchars($a['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
                
            <label>Cantidad inicial:</label>
            <input class="w3-input w3-margin-bottom" type="number" name="cantidad" value="1" min="1">
                
            <label>Imagen:</label>
            <input class="w3-input w3-margin-bottom" type="file" name="imagen" accept="image/*">
                
            <button class="w3-button w3-black w3-margin-top" type="submit">Registrar producto</button>
        </form>
    </div>
</div>

<script>
const selectCategoria = document.getElementById('select-categoria');
const crearBox = document.getElementById('crear-categoria-box');
const btnGuardarCat = document.getElementById('btn-guardar-categoria');

const perecibleCheck = document.getElementById('perecible-checkbox');
const campoVenc = document.getElementById('campo-vencimiento');
const campoGarantia = document.getElementById('campo-garantia');

// Mostrar formulario de categoría al elegir "crear"
selectCategoria.addEventListener('change', function () {
    if (this.value === "crear") {
        crearBox.style.display = "block";
    } else {
        crearBox.style.display = "none";
    }
});

// Alternar entre perecible y garantía
perecibleCheck.addEventListener('change', function () {
    if (this.checked) {
        campoVenc.style.display = "block";
        campoGarantia.style.display = "none";
    } else {
        campoVenc.style.display = "none";
        campoGarantia.style.display = "block";
    }
});

// Guardar categoría sin recargar (AJAX opcional)
btnGuardarCat.addEventListener('click', function(){
    let nombre = document.getElementById('nombre_cat').value.trim();
    let desc = document.getElementById('descripcion_cat').value.trim();

    if(nombre === ""){
        alert("Debe ingresar un nombre.");
        return;
    }

    let form = document.createElement("form");
    form.method = "POST";

    let i1 = document.createElement("input");
    i1.type = "hidden";
    i1.name = "nueva_categoria";
    i1.value = "1";

    let i2 = document.createElement("input");
    i2.type = "hidden";
    i2.name = "nombre_cat";
    i2.value = nombre;

    let i3 = document.createElement("input");
    i3.type = "hidden";
    i3.name = "descripcion_cat";
    i3.value = desc;

    form.appendChild(i1);
    form.appendChild(i2);
    form.appendChild(i3);

    document.body.appendChild(form);
    form.submit();
});
</script>

</body>
</html>