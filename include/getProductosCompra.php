<?php
include 'db_connect.php';

/*
    OBJETIVO:
    - Listar productos que provienen de órdenes de compra con estado 'Recibido'
    - Mostrar su precio unitario (prcio_untr)
    - Calcular el stock total sumando inventarios (todos los almacenes)
*/

$sql = "
    SELECT 
        p.id_producto,
        p.nombre,
        p.descripcion,
        p.imagen,
        dc.prcio_untr AS precio,

        -- Stock total (sumando inventarios)
        IFNULL(SUM(i.cantidad), 0) AS stock_total

    FROM producto p

    -- Viene de detalle_compra
    JOIN detalle_compra dc 
        ON p.id_producto = dc.id_producto

    -- Solo si la orden está recibida
    JOIN orden_compra oc 
        ON oc.id_ordcmpra = dc.id_ordcmpra
        AND oc.estado = 'Recibido'

    -- Inventario puede no existir aún
    LEFT JOIN inventario i 
        ON i.id_producto = p.id_producto

    GROUP BY p.id_producto, dc.prcio_untr
";

$result = $conn->query($sql);

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($productos);