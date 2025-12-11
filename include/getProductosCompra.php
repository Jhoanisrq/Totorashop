<?php
require_once "db_connect.php";
header("Content-Type: application/json");

try {
    // 1. Consulta principal: todos los productos + stock total
    $sql = "
        SELECT 
            p.id_producto,
            p.nombre,
            p.descripcion,
            p.imagen,
            p.id_catgria,
            p.perecible,
            p.precio AS precio_base,
            IFNULL(SUM(i.cantidad), 0) AS stock_total
        FROM producto p
        LEFT JOIN inventario i ON p.id_producto = i.id_producto
        GROUP BY p.id_producto
        ORDER BY p.nombre ASC
    ";

    $result = $conn->query($sql);

    $productos = [];

    // 2. Consulta corregida: obtener el precio más reciente de una orden RECIBIDA
    $sqlPrecio = "
        SELECT d.prcio_untr
        FROM detalle_compra d
        INNER JOIN orden_compra oc ON oc.id_ordcmpra = d.id_ordcmpra
        WHERE d.id_producto = ?
        AND oc.estado = 'RECIBIDO'
        ORDER BY oc.fecha_ordcmpra DESC
        LIMIT 1
    ";

    $stmtPrecio = $conn->prepare($sqlPrecio);

    while ($row = $result->fetch_assoc()) {

        $idProd = $row["id_producto"];
        $precio = floatval($row["precio_base"]); // precio respaldo si no hay órdenes

        // 3. Buscar precio desde órdenes recibidas
        if ($stmtPrecio) {
            $stmtPrecio->bind_param("i", $idProd);
            $stmtPrecio->execute();
            $resP = $stmtPrecio->get_result();

            if ($resP && $resP->num_rows > 0) {
                $pRow = $resP->fetch_assoc();
                if ($pRow["prcio_untr"] !== null) {
                    $precio = floatval($pRow["prcio_untr"]);
                }
            }
        }

        // 4. Armar respuesta del producto
        $productos[] = [
            "id_producto" => intval($row["id_producto"]),
            "nombre"      => $row["nombre"],
            "descripcion" => $row["descripcion"],
            "imagen"      => $row["imagen"],
            "id_catgria"  => intval($row["id_catgria"]),
            "perecible"   => intval($row["perecible"]),
            "stock_total" => intval($row["stock_total"]),
            "precio"      => $precio
        ];
    }

    echo json_encode($productos, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}