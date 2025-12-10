<?php
require_once("db_connect.php");

if (!isset($_POST['id'])) {
    header("Location: ../pages/botonEmpleados.php?error=no_id");
    exit();
}

$id = $_POST['id'];

// Obtener id de dirección
$stmt = $conn->prepare("SELECT id_direccion FROM empleado WHERE id_empldo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    header("Location: ../pages/botonEmpleados.php?error=not_found");
    exit();
}

$row = $res->fetch_assoc();
$id_dir = $row['id_direccion'];

// Eliminar empleado
$stmt = $conn->prepare("DELETE FROM empleado WHERE id_empldo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Eliminar dirección asociada
$stmt = $conn->prepare("DELETE FROM direccion WHERE id_drccion = ?");
$stmt->bind_param("i", $id_dir);
$stmt->execute();

header("Location: ../pages/botonEmpleados.php?success=empleado_eliminado");
?>
