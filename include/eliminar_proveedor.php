<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: ../pages/login_empleado.php");
    exit();
}

require_once("db_connect.php");

if (!isset($_POST['id'])) {
    header("Location: ../pages/botonProveedores.php?error=ID no válido");
    exit();
}

$id = intval($_POST['id']);

// obtener dirección asociada
$stmt = $conn->prepare("SELECT id_drccion FROM proveedor WHERE id_provdor = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($id_dir);
$stmt->fetch();
$stmt->close();

if (!$id_dir) {
    header("Location: ../pages/botonProveedores.php?error=Proveedor no encontrado");
    exit();
}

// eliminar proveedor
$stmt = $conn->prepare("DELETE FROM proveedor WHERE id_provdor = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// eliminar dirección si no está en uso
$stmt = $conn->prepare("SELECT COUNT(*) FROM proveedor WHERE id_drccion = ?");
$stmt->bind_param("i", $id_dir);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count == 0) {
    $stmt = $conn->prepare("DELETE FROM direccion WHERE id_drccion = ?");
    $stmt->bind_param("i", $id_dir);
    $stmt->execute();
    $stmt->close();
}

header("Location: ../pages/botonProveedores.php?mensaje=eliminado");
exit();