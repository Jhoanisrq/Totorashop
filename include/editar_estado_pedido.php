<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("db_connect.php");

// Validar POST
if (!isset($_POST['num_pddo'], $_POST['estado'])) {
    header("Location: botonPedidos.php?error=Datos incompletos");
    exit();
}

$num_pddo = intval($_POST['num_pddo']);
$nuevoEstado = $_POST['estado'];

// ----------------------
// 1. Obtener pedido actual
// ----------------------
$stmt = $conn->prepare("SELECT estado FROM pedido WHERE num_pddo = ?");
$stmt->bind_param("i", $num_pddo);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$pedido) {
    header("Location: botonPedidos.php?error=Pedido no encontrado");
    exit();
}

// ----------------------
// 2. Actualizar estado
// ----------------------
$stmt = $conn->prepare("UPDATE pedido SET estado = ? WHERE num_pddo = ?");
$stmt->bind_param("si", $nuevoEstado, $num_pddo);
$stmt->execute();
$stmt->close();

// ----------------------
// 3. Si cambia a Enviado: generar entradas y actualizar inventario
// ----------------------
if ($nuevoEstado === "Enviado") {

    // Obtener detalle del pedido
    $stmt = $conn->prepare("
        SELECT dp.id_dtlle_pddo, dp.id_producto, dp.cantidad, dp.prcio_untr
        FROM detalle_pedido dp
        WHERE dp.num_pddo = ?
    ");
    $stmt->bind_param("i", $num_pddo);
    $stmt->execute();
    $detalles = $stmt->get_result();
    $stmt->close();

    while ($item = $detalles->fetch_assoc()) {

        $idProducto = $item['id_producto'];
        $cantidad = $item['cantidad'];
        $costo = $item['prcio_untr'];

        // Buscar inventario con mayor stock (o si no existe, crear uno temporal en almacén 1)
        $stmtInv = $conn->prepare("
            SELECT id_invntrio, id_almcen, cantidad
            FROM inventario
            WHERE id_producto = ?
            ORDER BY cantidad DESC
            LIMIT 1
        ");
        $stmtInv->bind_param("i", $idProducto);
        $stmtInv->execute();
        $inv = $stmtInv->get_result()->fetch_assoc();
        $stmtInv->close();

        if ($inv) {
            $idInventario = $inv['id_invntrio'];
            $idAlmacen = $inv['id_almcen'];

            // Actualizar cantidad
            $stmtUpd = $conn->prepare("UPDATE inventario SET cantidad = cantidad - ? WHERE id_invntrio = ?");
            $stmtUpd->bind_param("ii", $cantidad, $idInventario);
            $stmtUpd->execute();
            $stmtUpd->close();

        } else {
            // Si no hay inventario, asignar a almacén 1 temporal
            $idAlmacen = 1;
            $conn->query("INSERT INTO inventario (id_producto, id_almcen, cantidad) VALUES ($idProducto, $idAlmacen, 0)");
            $idInventario = $conn->insert_id;
        }

        // Insertar en tabla entrada
        $stmtEnt = $conn->prepare("
            INSERT INTO entrada (id_invntrio, id_dtlle_oc, cantidad, costo) 
            VALUES (?, ?, ?, ?)
        ");
        // Aquí usamos id_dtlle_oc = 0 porque es pedido de cliente, no OC
        $idDtlleOC = 0;
        $stmtEnt->bind_param("iiid", $idInventario, $idDtlleOC, $cantidad, $costo);
        $stmtEnt->execute();
        $stmtEnt->close();
    }
}

header("Location: botonPedidos.php?mensaje=estado_actualizado");
exit();
?>
