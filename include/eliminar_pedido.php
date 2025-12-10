<?php
session_start();

$id = $_POST['id'];
unset($_SESSION['pedido'][$id]);

$total = 0;
foreach ($_SESSION['pedido'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

echo json_encode([
    "success" => true,
    "total" => number_format($total, 2)
]);