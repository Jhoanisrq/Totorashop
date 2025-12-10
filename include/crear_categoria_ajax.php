<?php
require_once "../db_connect.php";

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

$stmt = $conn->prepare("INSERT INTO categoria (nombre, descripcion) VALUES (?, ?)");
$stmt->bind_param("ss", $nombre, $descripcion);
$stmt->execute();

echo json_encode([
    "success" => true,
    "id" => $stmt->insert_id,
    "nombre" => $nombre
]);
