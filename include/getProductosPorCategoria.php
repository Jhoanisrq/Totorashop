<?php
include 'db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'Categoría inválida']);
    exit;
}

$id_categoria = intval($_GET['id']);

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
        AND oc.estado = 'Recibido'
    LEFT JOIN inventario i ON i.id_producto = p.id_producto
    WHERE p.id_catgria = ?
    GROUP BY p.id_producto, dc.prcio_untr
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_categoria);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($productos);
