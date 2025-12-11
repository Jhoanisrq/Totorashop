<?php
// ../include/editar_estado_oc.php
session_start();

if (!isset($_SESSION['id_empleado'])) {
    header("Location: ../pages/login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("db_connect.php");

// Validación de ID
if (!isset($_GET['id'])) {
    header("Location: ../pages/botonOrdenCompra.php?error=ID no proporcionado");
    exit();
}

$id_oc = intval($_GET['id']);
$mensaje = "";

// =========================
// OBTENER DATOS DE LA OC
// =========================
$stmt = $conn->prepare("
    SELECT 
        oc.*, 
        p.nombre AS proveedor,
        e.nombre AS emp_nombre,
        e.apellido AS emp_apellido,
        e.id_almcen AS almacen_empleado
    FROM orden_compra oc
    INNER JOIN proveedor p ON oc.id_provdor = p.id_provdor
    INNER JOIN empleado e ON oc.id_empldo = e.id_empldo
    WHERE oc.id_ordcmpra = ?
");
$stmt->bind_param("i", $id_oc);
$stmt->execute();
$res = $stmt->get_result();
$orden = $res->fetch_assoc();
$stmt->close();

if (!$orden) {
    header("Location: ../pages/botonOrdenCompra.php?error=Orden no encontrada");
    exit();
}

$id_almacen_empleado = $orden["almacen_empleado"];

// Estados que ya no se pueden cambiar
$estadosFinales = ["Recibido"];

// ===============================
// FUNCION PARA REGISTRAR INVENTARIO
// ===============================
function registrarEntradaInventario($id_oc, $id_almacen, $conn) {

    // Obtener detalles
    $stmt = $conn->prepare("
        SELECT d.*, p.precio
        FROM detalle_compra d
        INNER JOIN producto p ON d.id_producto = p.id_producto
        WHERE d.id_ordcmpra = ?
    ");
    $stmt->bind_param("i", $id_oc);
    $stmt->execute();
    $detalles = $stmt->get_result();
    $stmt->close();

    while ($item = $detalles->fetch_assoc()) {

        $id_producto = $item["id_producto"];
        $cantidad = $item["cantidad"];
        $precioUnit = $item["prcio_untr"];

        // Verificar inventario existente
        $stmt_inv = $conn->prepare("
            SELECT id_invntrio, cantidad
            FROM inventario
            WHERE id_producto = ? AND id_almcen = ?
        ");
        $stmt_inv->bind_param("ii", $id_producto, $id_almacen);
        $stmt_inv->execute();
        $inv = $stmt_inv->get_result()->fetch_assoc();
        $stmt_inv->close();

        if ($inv) {
            // Actualizar inventario
            $stmt_upd = $conn->prepare("
                UPDATE inventario SET cantidad = cantidad + ?
                WHERE id_invntrio = ?
            ");
            $stmt_upd->bind_param("ii", $cantidad, $inv["id_invntrio"]);
            $stmt_upd->execute();
            $stmt_upd->close();

            $id_inventario = $inv["id_invntrio"];

        } else {
            // Crear inventario
            $stmt_new = $conn->prepare("
                INSERT INTO inventario (id_producto, id_almcen, cantidad)
                VALUES (?, ?, ?)
            ");
            $stmt_new->bind_param("iii", $id_producto, $id_almacen, $cantidad);
            $stmt_new->execute();
            $id_inventario = $stmt_new->insert_id;
            $stmt_new->close();
        }

        // Registrar ENTRADA
        $costo = $precioUnit * $cantidad;

        $stmt_ent = $conn->prepare("
            INSERT INTO entrada (id_invntrio, id_dtlle_oc, cantidad, costo)
            VALUES (?, ?, ?, ?)
        ");
        $stmt_ent->bind_param("iiid", $id_inventario, $item["id_dtlle_oc"], $cantidad, $costo);
        $stmt_ent->execute();
        $stmt_ent->close();
    }
}

// =========================
// SI SE ENVÍA FORMULARIO
// =========================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nuevoEstado = $_POST["estado"];

    if (in_array($orden["estado"], $estadosFinales)) {
        $mensaje = "Este pedido ya no puede modificarse.";
    } else {

        // Actualizar estado
        $stmt = $conn->prepare("
            UPDATE orden_compra 
            SET estado = ?
            WHERE id_ordcmpra = ?
        ");
        $stmt->bind_param("si", $nuevoEstado, $id_oc);

        if ($stmt->execute()) {

            if ($nuevoEstado === "Recibido") {
                registrarEntradaInventario($id_oc, $id_almacen_empleado, $conn);
            }

            header("Location: ../pages/botonOrdenCompra.php?mensaje=estado_actualizado");
            exit();
        }

        $stmt->close();
    }
}

// Lista de estados permitidos
$estados = ["Pendiente", "En camino", "Recibido"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar estado OC</title>
<link rel="stylesheet" href="../assets/css/empleadoStyle.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>

<div class="top-bar">
    <div class="top-left">
        <a href="../pages/botonOrdenCompra.php" class="back-button">&#8592;</a>
        <span class="welcome-message">Editar estado OC #<?= $id_oc ?></span>
    </div>
</div>

<div class="w3-container w3-padding">

    <?php if ($mensaje): ?>
        <p class="w3-text-red"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <div class="w3-card-4 w3-white w3-padding">

        <h4>Proveedor: <?= htmlspecialchars($orden['proveedor']) ?></h4>
        <h4>Empleado: <?= htmlspecialchars($orden['emp_nombre']." ".$orden['emp_apellido']) ?></h4>
        <h4>Estado actual: <b><?= htmlspecialchars($orden['estado']) ?></b></h4>

        <form method="POST">
            <label>Nuevo estado:</label>

            <select class="w3-input w3-margin-bottom" name="estado"
                <?= in_array($orden['estado'], $estadosFinales) ? "disabled" : "" ?>>
                
                <?php foreach($estados as $e): ?>
                    <option value="<?= $e ?>" <?= $orden['estado'] === $e ? "selected" : "" ?>>
                        <?= $e ?>
                    </option>
                <?php endforeach; ?>

            </select>

            <?php if (!in_array($orden["estado"], $estadosFinales)): ?>
                <button class="w3-button w3-black w3-margin-top" type="submit">Guardar cambios</button>
            <?php else: ?>
                <p class="w3-text-grey">Este pedido ya no puede ser editado.</p>
            <?php endif; ?>

        </form>
    </div>
</div>

</body>
</html>