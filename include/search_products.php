<?php
// ../include/search_products.php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_GET['query'])) {
    echo json_encode(['success' => false, 'message' => 'No se recibió el texto de búsqueda']);
    exit;
}

$busqueda = "%" . $conn->real_escape_string($_GET['query']) . "%";

try {

    // 1. Buscar productos + stock total (sin exigir orden de compra)
    $sql = "
        SELECT 
            p.id_producto,
            p.nombre,
            p.descripcion,
            p.imagen,
            c.nombre AS categoria,
            p.precio AS precio_base,
            IFNULL(SUM(i.cantidad), 0) AS stock_total
        FROM producto p
        JOIN categoria c ON p.id_catgria = c.id_catgria
        LEFT JOIN inventario i ON i.id_producto = p.id_producto
        WHERE p.nombre LIKE ?
           OR c.nombre LIKE ?
        GROUP BY p.id_producto
        LIMIT 20
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $busqueda, $busqueda);
    $stmt->execute();
    $result = $stmt->get_result();

    $productos = [];

    // 2. Consulta preparada para obtener último precio de orden recibida
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

        // Obtener precio DINÁMICO desde compras
        $stmtPrecio->bind_param("i", $idProd);
        $stmtPrecio->execute();
        $resP = $stmtPrecio->get_result();

        if ($resP && $resP->num_rows > 0) {
            $pRow = $resP->fetch_assoc();
            if ($pRow["prcio_untr"] !== null) {
                $precio = floatval($pRow["prcio_untr"]);
            }
        }

        $productos[] = [
            'id'        => intval($row['id_producto']),
            'nombre'    => $row['nombre'],
            'descripcion'=> $row['descripcion'],
            'precio'    => number_format($precio, 2, '.', ''),
            'imagen'    => $row['imagen'],
            'categoria' => $row['categoria'],
            'stock'     => intval($row['stock_total'])
        ];
    }

    echo json_encode(['success' => true, 'productos' => $productos], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}