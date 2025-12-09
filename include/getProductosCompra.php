<?php
include 'db_connect.php';

$sql = "
    SELECT 
        p.id_producto,
        p.nombre,
        p.descripcion,
        p.imagen,
        dc.prcio_untr AS precio,
        IFNULL(SUM(i.cantidad), 0) AS stock_total
    FROM producto p
    JOIN detalle_compra dc ON p.id_producto = dc.id_producto
    JOIN orden_compra oc ON oc.id_ordcmpra = dc.id_ordcmpra
    LEFT JOIN inventario i ON i.id_producto = p.id_producto
    WHERE oc.estado = 'Recibido'
    GROUP BY p.id_producto
";

$result = $conn->query($sql);

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($productos);