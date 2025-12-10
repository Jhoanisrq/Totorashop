<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_cliente'])) {
    echo json_encode(["success" => false, "direcciones" => []]);
    exit;
}

$id = $_SESSION['id_cliente'];

$sql = "
    SELECT d.id_drccion AS id, d.ciudad, d.distrito, d.nro_calle, d.referencia
    FROM cliente_direccion cd
    JOIN direccion d ON cd.id_drccion = d.id_drccion
    WHERE cd.id_cliente = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$direcciones = [];
while ($row = $result->fetch_assoc()) {
    $direcciones[] = $row;
}

echo json_encode([
    "success" => true,
    "direcciones" => $direcciones
]);