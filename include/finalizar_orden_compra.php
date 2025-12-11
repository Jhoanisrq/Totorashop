<?php
//../include/finalizar_orden_compra.php
session_start();
require_once "../include/db_connect.php";

// =============================
// MODO DEBUG
// =============================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

$debugData = [
    "POST" => $_POST,
    "SESSION_temp_OC" => $_SESSION['oc_tmp'] ?? "NO EXISTE",
    "SESSION_full" => $_SESSION
];

// Validar proveedor
if (!isset($_POST['proveedor'])) {
    echo json_encode([
        "success" => false,
        "message" => "No se envió proveedor",
        "debug" => $debugData
    ]);
    exit;
}

$proveedor = intval($_POST['proveedor']);

// Validar detalle temporal
if (!isset($_SESSION['oc_tmp']) || count($_SESSION['oc_tmp']) === 0) {
    echo json_encode([
        "success" => false,
        "message" => "No hay productos agregados",
        "debug" => $debugData
    ]);
    exit;
}

// Validar sesión de empleado (CORREGIDO)
if (!isset($_SESSION['id_empleado'])) {
    echo json_encode([
        "success" => false,
        "message" => "No existe id_empleado en sesión",
        "debug" => $debugData
    ]);
    exit;
}

$idEmpleado = intval($_SESSION['id_empleado']); // CORRECTO

try {

    // Calcular total
    $total = 0;
    foreach ($_SESSION['oc_tmp'] as $item) {
        $total += floatval($item['subtotal']);
    }

    $estado = "Pendiente";

    // =============================
    // INSERTAR ORDEN DE COMPRA
    // =============================
    $sql = "INSERT INTO orden_compra (id_provdor, precio, fecha_ordcmpra, estado, id_empldo)
            VALUES (?, ?, NOW(), ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("Error preparando SQL: " . $conn->error);

    // "i" proveedor, "d" precio total, "s" estado, "i" id_empleado
    $stmt->bind_param("idsi", $proveedor, $total, $estado, $idEmpleado);

    if (!$stmt->execute()) {
        throw new Exception("Error ejecutando SQL: " . $stmt->error);
    }

    $idOC = $stmt->insert_id;

    // =============================
    // INSERTAR DETALLE
    // =============================
    // Insertar detalle
foreach ($_SESSION['oc_tmp'] as $item) {

    $id_producto = intval($item['id_producto']); 
    $cantidad    = intval($item['cantidad']);
    $precio_u    = floatval($item['precio_u']);

    $sql2 = "INSERT INTO detalle_compra (id_ordcmpra, id_producto, cantidad, prcio_untr)
             VALUES (?, ?, ?, ?)";

    $stmt2 = $conn->prepare($sql2);
    if (!$stmt2) throw new Exception("Error preparando detalle: " . $conn->error);

    $stmt2->bind_param("iiid", $idOC, $id_producto, $cantidad, $precio_u);

    if (!$stmt2->execute()) {
        throw new Exception("Error ejecutando detalle: " . $stmt2->error);
    }
}


    // ÉXITO
    echo json_encode([
        "success" => true,
        "message" => "Orden registrada correctamente",
        "id_oc" => $idOC,
        "debug" => $debugData
    ]);

    unset($_SESSION['oc_tmp']); // limpiar temporal

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => "ERROR EN PROCESO",
        "error_detail" => $e->getMessage(),
        "debug" => $debugData
    ]);
}
