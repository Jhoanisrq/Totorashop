<?php
// ../include/getProductosPorCategoria.php
include "db_connect.php";
header("Content-Type: application/json");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'Categoría inválida']);
    exit;
}

$id_categoria = intval($_GET['id']);

try {

    // 1. Obtener productos de la categoría + stock total
    $sql = "
        SELECT 
            p.id_producto,
            p.nombre,
            p.descripcion,
            p.imagen,
            p.precio AS precio_base,
            IFNULL(SUM(i.cantidad), 0) AS stock_total
        FROM producto p
        LEFT JOIN inventario i ON p.id_producto = i.id_producto
        WHERE p.id_catgria = ?
        GROUP BY p.id_producto
        ORDER BY p.nombre ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $result = $stmt->get_result();

    $productos = [];

    // 2. Obtener último precio de orden recibida
    $sqlPrecio = "
        SELECT d.prcio_untr
        FROM detalle_compra d
        INNER JOIN orden_compra oc ON oc.id_ordcmpra = d.id_ordcmpra
        WHERE d.id_producto = ?
        AND oc.estado = 'Recibido'
        ORDER BY oc.fecha_ordcmpra DESC
        LIMIT 1
    ";
    $stmtPrecio = $conn->prepare($sqlPrecio);

    while ($row = $result->fetch_assoc()) {

        $precio = floatval($row["precio_base"]);
        $idProd = $row["id_producto"];

        // Precio desde últimas órdenes recibidas
        $stmtPrecio->bind_param("i", $idProd);
        $stmtPrecio->execute();
        $resP = $stmtPrecio->get_result();

        if ($resP && $resP->num_rows > 0) {
            $pRow = $resP->fetch_assoc();
            if ($pRow["prcio_untr"] !== null) {
                $precio = floatval($pRow["prcio_untr"]);
            }
        }

        // Armar producto final
        $productos[] = [
            "id_producto" => intval($row["id_producto"]),
            "nombre"      => $row["nombre"],
            "descripcion" => $row["descripcion"],
            "imagen"      => $row["imagen"],
            "precio"      => $precio,
            "stock_total" => intval($row["stock_total"])
        ];
    }

    echo json_encode($productos, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}