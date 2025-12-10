<?php
session_start();

$total = 0;

if (isset($_SESSION['pedido'])) {
    foreach ($_SESSION['pedido'] as $item) {
        $total += $item['cantidad'];
    }
}

echo json_encode([
    "success" => true,
    "count" => $total
]);
?>