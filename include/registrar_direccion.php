<?php
session_start();
if (!isset($_SESSION['id_cliente'])) {
    header("Location: ../pages/usuario.php");
    exit();
}

require_once("db_connect.php");

$id_cliente = $_SESSION['id_cliente'];

$ciudad = $_POST['ciudad'];
$distrito = $_POST['distrito'];
$nro_calle = $_POST['nro_calle'];
$ref = $_POST['referencia'];

// Insertar en direccion
$sql1 = "INSERT INTO direccion (ciudad, distrito, nro_calle, referencia)
         VALUES (?, ?, ?, ?)";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("ssss", $ciudad, $distrito, $nro_calle, $ref);
$stmt1->execute();

$idDireccion = $conn->insert_id;

// Insertar relación con cliente
$sql2 = "INSERT INTO cliente_direccion (id_cliente, id_drccion)
         VALUES (?, ?)";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("ii", $id_cliente, $idDireccion);
$stmt2->execute();

header("Location: perfilCliente.php?ok=1");
exit();
