<?php
session_start();
include 'db_connect.php';

if (!isset($_POST['correo']) || !isset($_POST['password'])) {
    header('Location: ../pages/usuarios.php?error=missing_data');
    exit;
}

$correo = $_POST['correo'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id_cliente, nombre, apellido, contraseña FROM cliente WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();
$stored_pass = $user['contraseña'];

/*
-------------------------------------------------------
 1) SI LA CONTRASEÑA EN BD NO ESTÁ HASHEADA
    (texto plano), entonces:
      - verificamos directo
      - si coincide → la convertimos a password_hash()
-------------------------------------------------------
*/

if (!password_verify($password, $stored_pass)) {

    // Caso: la contraseña almacenada NO es hash (texto plano)
    if ($password === $stored_pass) {

        // Convertimos automáticamente
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE cliente SET contraseña = ? WHERE id_cliente = ?");
        $update->bind_param("si", $hashed, $user['id_cliente']);
        $update->execute();
        $update->close();

    } else {
        // Contraseña incorrecta
        header('Location: ../pages/usuarios.php?error=incorrect_password');
        exit;
    }
}

// ---- LOGIN EXITOSO ----
session_regenerate_id(true);
$_SESSION['id_cliente'] = $user['id_cliente'];
$_SESSION['nombre'] = $user['nombre'];
$_SESSION['apellido'] = $user['apellido'];

header('Location: ../pages/productos.php');
exit;

?>