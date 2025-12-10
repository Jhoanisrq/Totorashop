<?php
session_start();

// Asegurar que el arreglo de pedido exista
if (!isset($_SESSION['pedido'])) {
    $_SESSION['pedido'] = [];
}

$id = $_POST['id_producto'] ?? null;
$nombre = $_POST['nombre'] ?? "";
$imagen = $_POST['imagen'] ?? "";
$descripcion = $_POST['descripcion'] ?? "";
$precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
$stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;

if (!$id) {
    echo json_encode(["success" => false, "message" => "Producto inválido"]);
    exit;
}

// Si ya existe → aumentar cantidad
if (isset($_SESSION['pedido'][$id])) {
    $nuevaCantidad = $_SESSION['pedido'][$id]['cantidad'] + $cantidad;

    if ($nuevaCantidad > $stock) {
        echo json_encode(["success" => false, "message" => "Cantidad supera el stock disponible."]);
        exit;
    }

    $_SESSION['pedido'][$id]['cantidad'] = $nuevaCantidad;
} else {
    // Agregar nuevo
    $_SESSION['pedido'][$id] = [
        "id" => $id,
        "nombre" => $nombre,
        "imagen" => $imagen,
        "descripcion" => $descripcion,
        "precio" => $precio,
        "cantidad" => $cantidad,
        "stock" => $stock
    ];
}

echo json_encode(["success" => true, "message" => "Producto agregado al pedido"]);
?>
