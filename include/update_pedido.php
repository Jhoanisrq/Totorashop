<?php
session_start();

if (!isset($_POST['id']) || !isset($_POST['cantidad'])) {
    echo json_encode(["success" => false]);
    exit;
}

$id = $_POST['id'];
$cantidad = intval($_POST['cantidad']);

if ($cantidad < 1) $cantidad = 1;

$_SESSION['pedido'][$id]['cantidad'] = $cantidad;

$precio = $_SESSION['pedido'][$id]['precio'];
$subtotal = $precio * $cantidad;

$total = 0;
foreach ($_SESSION['pedido'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

echo json_encode([
    "success" => true,
    "subtotal" => number_format($subtotal, 2),
    "total" => number_format($total, 2)
]);