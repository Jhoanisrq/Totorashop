<?php
session_start();
include 'db_connect.php'; // tu conexión MySQL

// Obtener y limpiar los datos del formulario
$dni = isset($_POST['dni']) ? trim($_POST['dni']) : '';
$pass = isset($_POST['contraseña']) ? trim($_POST['contraseña']) : '';

// Validar que no estén vacíos
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

// Verificar contraseña (texto plano o hash)
$stored = $empleado['contraseña'];
$is_valid = false;

if (substr($stored, 0, 4) === '$2y$') {
    // Contraseña hasheada
    $is_valid = password_verify($pass, $stored);
} else {
    // Contraseña en texto plano
    $is_valid = ($pass === $stored);
    
    // Si es correcta, actualizar a hash automáticamente
    if ($is_valid) {
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        $stmt_upd = $conn->prepare("UPDATE empleado SET contraseña=? WHERE id_empldo=?");
        $stmt_upd->bind_param("si", $hashed, $empleado['id_empldo']);
        $stmt_upd->execute();
    }
}

if (!$is_valid) {
    header("Location: ../pages/login_empleado.php?error=Contraseña incorrecta");
    exit();
}

// Guardar datos en sesión
$_SESSION['id_empleado'] = $empleado['id_empldo'];
$_SESSION['nombre_empleado'] = $empleado['nombre'] . ' ' . $empleado['apellido'];

// Redirigir al panel del empleado
header("Location: ../pages/panelEmpleado.php");
exit();
?>
