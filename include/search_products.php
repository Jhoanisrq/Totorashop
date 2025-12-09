<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_GET['query'])) {
    echo json_encode(['success' => false, 'message' => 'No se recibió el texto de búsqueda']);
    exit;
}

$busqueda = "%" . $conn->real_escape_string($_GET['query']) . "%";

// Buscar productos que ya fueron recibidos
$sql = "
    SELECT 
        p.id_producto,
        p.nombre,
        p.descripcion,
        p.imagen,
        c.nombre AS categoria,
        dc.prcio_untr AS precio,
        IFNULL(SUM(i.cantidad), 0) AS stock_total
    FROM producto p
    JOIN categoria c ON p.id_catgria = c.id_catgria
    JOIN detalle_compra dc ON dc.id_producto = p.id_producto
    JOIN orden_compra oc ON oc.id_ordcmpra = dc.id_ordcmpra
    LEFT JOIN inventario i ON i.id_producto = p.id_producto
    WHERE oc.estado = 'Recibido'
      AND (p.nombre LIKE ? OR c.nombre LIKE ?)
    GROUP BY p.id_producto
    LIMIT 20
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $busqueda, $busqueda);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];

while ($row = $result->fetch_assoc()) {
    $productos[] = [
        'id' => $row['id_producto'],
        'nombre' => $row['nombre'],
        'descripcion' => $row['descripcion'],
        'precio' => number_format($row['precio'], 2, '.', ''),
        'imagen' => $row['imagen'],
        'categoria' => $row['categoria'],
        'stock' => $row['stock_total']
    ];
}

echo json_encode(['success' => true, 'productos' => $productos]);