<?php
session_start();
include 'db_connect.php'; // conexión a tu base de datos

$dni = isset($_POST['dni']) ? trim($_POST['dni']) : '';
$pass = isset($_POST['contraseña']) ? trim($_POST['contraseña']) : '';

if (!$dni || !$pass) {
    header("Location: ../pages/login_empleado.php?error=Por favor completa ambos campos");
    exit();
}

// Buscar empleado por DNI
$stmt = $conn->prepare("SELECT id_empldo, contraseña, nombre, apellido FROM empleado WHERE dni = ?");
$stmt->bind_param("s", $dni);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../pages/login_empleado.php?error=DNI no registrado");
    exit();
}

$empleado = $result->fetch_assoc();

// Detectar si la contraseña almacenada está hasheada
$stored_password = $empleado['contraseña'];
$login_ok = false;

if (password_get_info($stored_password)['algo'] !== 0) {
    // Contraseña está hasheada, usamos password_verify
    if (password_verify($pass, $stored_password)) {
        $login_ok = true;
    }
} else {
    // Contraseña en texto plano
    if ($pass === $stored_password) {
        $login_ok = true;

        // Opcional: re-hashear automáticamente para seguridad
        $nuevo_hash = password_hash($pass, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE empleado SET contraseña = ? WHERE id_empldo = ?");
        $update->bind_param("si", $nuevo_hash, $empleado['id_empldo']);
        $update->execute();
        $update->close();
    }
}

if (!$login_ok) {
    header("Location: ../pages/login_empleado.php?error=Contraseña incorrecta");
    exit();
}

// Guardar datos en sesión
$_SESSION['id_empleado'] = $empleado['id_empldo'];
$_SESSION['nombre_empleado'] = $empleado['nombre'] . ' ' . $empleado['apellido'];

// Redirigir al panel
header("Location: ../pages/panel_empleado.php");
exit();
?>
