<?php
include 'db_connect.php';

if (
    !isset($_POST['nombre']) ||
    !isset($_POST['apellido']) ||
    !isset($_POST['correo']) ||
    !isset($_POST['fech_nacmnto']) ||
    !isset($_POST['password'])
) {
    header('Location: ../pages/usuario.php?error=missing_data');
    exit;
}

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$fecha = $_POST['fech_nacmnto'];
$telefono = $_POST['telefono'] ?? null;
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id_cliente FROM cliente WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header('Location: ../pages/usuario.php?error=email_exists');
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO cliente (nombre, apellido, correo, fecha_ncmnto, telefono, contraseña)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("ssssss", $nombre, $apellido, $correo, $fecha, $telefono, $hashed_password);

if ($stmt->execute()) {
    header('Location: ../pages/usuario.php?registered=true');
} else {
    header('Location: ../pages/usuario.php?error=registration_failed');
}

$stmt->close();
$conn->close();
?>