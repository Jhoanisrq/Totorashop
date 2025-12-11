<?php
session_start();
include 'db_connect.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn->set_charset("utf8mb4");

try {

    if (!isset($_SESSION['id_cliente'])) {
        echo json_encode(["success" => false, "message" => "Debes iniciar sesión."]);
        exit;
    }

    $id_cliente = $_SESSION['id_cliente'];

    if (!isset($_SESSION['pedido']) || empty($_SESSION['pedido'])) {
        echo json_encode(["success" => false, "message" => "Tu carrito está vacío."]);
        exit;
    }

    // -------------------------------
    // OBTENER DIRECCIÓN
    // -------------------------------

    $direccion = $_POST['direccion'] ?? null; // id_clnte_drccion si es existente

    $ciudad = $_POST['ciudad'] ?? null;
    $distrito = $_POST['distrito'] ?? null;
    $nro_calle = $_POST['nro_calle'] ?? null;
    $referencia = $_POST['referencia'] ?? null;

    // Si no se seleccionó dirección existente, crear nueva
    if (!$direccion && $ciudad && $distrito && $nro_calle) {

        // Insertar en tabla direccion
        $stmt = $conn->prepare("INSERT INTO direccion(ciudad, distrito, nro_calle, referencia) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $ciudad, $distrito, $nro_calle, $referencia);
        $stmt->execute();
        $id_direccion_nueva = $stmt->insert_id;

        // Insertar en cliente_direccion y obtener id_clnte_drccion
        $stmt2 = $conn->prepare("INSERT INTO cliente_direccion(id_cliente, id_drccion) VALUES (?, ?)");
        $stmt2->bind_param("ii", $id_cliente, $id_direccion_nueva);
        $stmt2->execute();
        $id_clnte_drccion_nuevo = $stmt2->insert_id;

        $direccion = $id_clnte_drccion_nuevo; // usar este id para pedido
    }

    if (!$direccion) {
        echo json_encode(["success" => false, "message" => "Debes seleccionar o registrar una dirección"]);
        exit;
    }

    // -------------------------------
    // CALCULAR TOTAL
    // -------------------------------
    $total = 0;
    foreach ($_SESSION['pedido'] as $item) {
        $total += floatval($item['precio']) * intval($item['cantidad']);
    }

    $fecha_pedido = date("Y-m-d");
    $randomDias = rand(3, 10);
    $fecha_envio = date("Y-m-d", strtotime("+$randomDias days"));

    // -------------------------------
    // INSERTAR PEDIDO
    // -------------------------------
    $stmt = $conn->prepare("
        INSERT INTO pedido(id_clnte_drccion, fecha_pddo, estado, costo, fecha_envio)
        VALUES (?, ?, 'Procesando', ?, ?)
    ");
    $stmt->bind_param("isds", $direccion, $fecha_pedido, $total, $fecha_envio);
    $stmt->execute();
    $num_pedido = $stmt->insert_id;

    // -------------------------------
    // INSERTAR DETALLES DEL PEDIDO
    // -------------------------------
    foreach ($_SESSION['pedido'] as $item) {
        $cantidad = intval($item['cantidad']);
        $precio = floatval($item['precio']);
        $id_producto = intval($item['id']);

        $stmtD = $conn->prepare("
            INSERT INTO detalle_pedido(num_pddo, cantidad, prcio_untr, id_producto)
            VALUES (?, ?, ?, ?)
        ");
        $stmtD->bind_param("iidi", $num_pedido, $cantidad, $precio, $id_producto);
        $stmtD->execute();
    }

    // -------------------------------
    // LIMPIAR PEDIDO TEMPORAL EN SESIÓN
    // -------------------------------
    $_SESSION['pedido'] = []; // elimina todo lo temporal del carrito
    
    // -------------------------------
    // RESPUESTA JSON
    // -------------------------------
    echo json_encode([
        "success" => true,
        "message" => "Pedido registrado correctamente",
        "fecha_envio" => $fecha_envio,
        "num_pedido" => $num_pedido
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "ERROR SQL: " . $e->getMessage()
    ]);
}
?>