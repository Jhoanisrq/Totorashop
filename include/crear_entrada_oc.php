<?php
function crearEntradaOC($id_oc, $conn) {

    // Obtener detalles de la orden
    $sql = "SELECT id_producto, cantidad FROM detalle_compra WHERE id_ordcmpra = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_oc);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($item = $result->fetch_assoc()) {
        $id_producto = intval($item['id_producto']);
        $cantidad    = intval($item['cantidad']);

        // Crear registro de salida/entrada (si tu sistema usa tabla entrada)
        $sql_ins = "INSERT INTO entrada_producto (id_producto, cantidad, fecha, tipo)
                    VALUES (?, ?, NOW(), 'OC')";
        $stmt_ins = $conn->prepare($sql_ins);
        $stmt_ins->bind_param("ii", $id_producto, $cantidad);
        $stmt_ins->execute();

        // Actualizar stock en tabla producto o inventario
        $sql_upd = "UPDATE producto SET stock = stock + ? WHERE id_producto = ?";
        $stmt_upd = $conn->prepare($sql_upd);
        $stmt_upd->bind_param("ii", $cantidad, $id_producto);
        $stmt_upd->execute();
    }

    $stmt->close();
}
