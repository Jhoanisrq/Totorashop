<?php
session_start();
$action = $_POST['action'];

if (!isset($_SESSION['oc_tmp'])) {
    $_SESSION['oc_tmp'] = [];
}

if ($action == "add") {
    $id = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $precio_u = $_POST['precio_u'];

    $_SESSION['oc_tmp'][] = [
        "id_producto" => $id,
        "nombre" => $nombre,
        "cantidad" => $cantidad,
        "precio_u" => $precio_u,
        "subtotal" => $cantidad * $precio_u
    ];
}

if ($action == "delete") {
    $index = $_POST['index'];
    unset($_SESSION['oc_tmp'][$index]);
}

$total = 0;
foreach ($_SESSION['oc_tmp'] as $item) {
    $total += $item['subtotal'];
}

echo json_encode([
    "detalle" => $_SESSION['oc_tmp'],
    "total" => number_format($total, 2)
]);
