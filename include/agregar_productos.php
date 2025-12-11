<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: ../pages/login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("db_connect.php");

// ==================================
// Registrar nueva categoría
// ==================================
if (isset($_POST['nueva_categoria'])) {
    $nombre_cat = trim($_POST['nombre_cat']);
    $descripcion_cat = trim($_POST['descripcion_cat'] ?? '');

    if ($nombre_cat !== "") {
        $stmtCat = $conn->prepare("INSERT INTO categoria (nombre, descripcion) VALUES (?, ?)");
        $stmtCat->bind_param("ss", $nombre_cat, $descripcion_cat);
        $stmtCat->execute();
    }
}

// ==================================
// Obtener categorías
// ==================================
$categorias = [];
$res = $conn->query("SELECT id_catgria, nombre FROM categoria ORDER BY nombre");
while ($row = $res->fetch_assoc()) {
    $categorias[] = $row;
}

// ==================================
// Obtener almacenes
// ==================================
$almacenes = [];
$res = $conn->query("SELECT id_almcen, nombre FROM almacen");
while ($row = $res->fetch_assoc()) {
    $almacenes[] = $row;
}

$mensaje = "";

// ==================================
// Registrar nuevo producto
// ==================================
if (isset($_POST['nombre'])) {

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = floatval($_POST['precio']);       // <-- NUEVO
    $id_catgria = intval($_POST['id_catgria']);
    $perecible = isset($_POST['perecible']) ? 1 : 0;
    $fch_vncmnto = $_POST['fch_vncmnto'] ?? null;
    $grntia_meses = !empty($_POST['grntia_meses']) ? intval($_POST['grntia_meses']) : null;
    $id_almcen = intval($_POST['id_almcen']);
    $cantidad = intval($_POST['cantidad']);

    // ==================================
    // Subida de imagen
    // ==================================
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

    // ==================================
    // Insertar producto
    // ==================================
    $stmt = $conn->prepare("
        INSERT INTO producto 
        (nombre, descripcion, imagen, id_catgria, perecible, fch_vncmnto, grntia_meses, fecha_rgstro, precio)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "sssiiissd",
        $nombre, $descripcion, $imagen, $id_catgria, $perecible,
        $fch_vncmnto, $grntia_meses, $fecha_rgstro, $precio
    );

    if ($stmt->execute()) {
        $id_producto = $stmt->insert_id;

        // ==================================
        // Insertar inventario inicial
        // ==================================
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

            <label>Precio (S/):</label>
            <input class="w3-input w3-margin-bottom" type="number" step="0.01" min="0" name="precio" required>

            <!-- CATEGORÍA -->
            <label>Categoría:</label>
            <select class="w3-select w3-margin-bottom" name="id_catgria" id="select-categoria" required>
                <option value="" disabled selected>Selecciona una categoría</option>
                <?php foreach($categorias as $c): ?>
                    <option value="<?= $c['id_catgria'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                <?php endforeach; ?>
                <option value="crear">➕ Crear nueva categoría</option>
            </select>

            <!-- FORMULARIO CREAR CATEGORÍA -->
            <div id="crear-categoria-box" class="w3-padding w3-border" style="display:none;">
                <h5>Crear nueva categoría</h5>

                <label>Nombre:</label>
                <input class="w3-input w3-margin-bottom" id="nombre_cat" name="nombre_cat">

                <label>Descripción (opcional):</label>
                <input class="w3-input w3-margin-bottom" id="descripcion_cat" name="descripcion_cat">

                <button type="button" class="w3-button w3-blue" id="btn-guardar-categoria">
                    Guardar categoría
                </button>
            </div>

            <!-- PERECIBLE -->
            <label>Perecible:</label>
            <input type="checkbox" name="perecible" id="perecible-checkbox" class="w3-check">

            <div id="campo-vencimiento" style="display:none;">
                <label>Fecha de vencimiento:</label>
                <input class="w3-input w3-margin-bottom" type="date" name="fch_vncmnto">
            </div>

            <div id="campo-garantia">
                <label>Garantía (meses):</label>
                <input class="w3-input w3-margin-bottom" type="number" name="grntia_meses" min="0">
            </div>

            <label>Almacén:</label>
            <select class="w3-select w3-margin-bottom" name="id_almcen" required>
                <option disabled selected>Selecciona un almacén</option>
                <?php foreach($almacenes as $a): ?>
                    <option value="<?= $a['id_almcen'] ?>"><?= htmlspecialchars($a['nombre']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Cantidad inicial:</label>
            <input class="w3-input w3-margin-bottom" type="number" name="cantidad" min="1" value="1">

            <label>Imagen:</label>
            <input class="w3-input w3-margin-bottom" type="file" name="imagen" accept="image/*">

            <button class="w3-button w3-black w3-margin-top" type="submit">Registrar producto</button>
        </form>
    </div>
</div>


<script>
// Mostrar formulario de categoría
document.getElementById("select-categoria").addEventListener("change", function () {
    document.getElementById("crear-categoria-box").style.display =
        this.value === "crear" ? "block" : "none";
});

// Mostrar vencimiento y ocultar garantía
document.getElementById("perecible-checkbox").addEventListener("change", function () {
    if (this.checked) {
        document.getElementById("campo-vencimiento").style.display = "block";
        document.getElementById("campo-garantia").style.display = "none";
    } else {
        document.getElementById("campo-vencimiento").style.display = "none";
        document.getElementById("campo-garantia").style.display = "block";
    }
});

// Guardar categoría sin recargar
document.getElementById("btn-guardar-categoria").addEventListener("click", function () {
    let nombre = document.getElementById("nombre_cat").value.trim();
    if (nombre === "") {
        alert("Debe ingresar un nombre.");
        return;
    }

    let form = document.createElement("form");
    form.method = "POST";

    let inputs = {
        nueva_categoria: "1",
        nombre_cat: nombre,
        descripcion_cat: document.getElementById("descripcion_cat").value.trim()
    };

    for (let key in inputs) {
        let i = document.createElement("input");
        i.type = "hidden";
        i.name = key;
        i.value = inputs[key];
        form.appendChild(i);
    }

    document.body.appendChild(form);
    form.submit();
});
</script>

</body>
</html>