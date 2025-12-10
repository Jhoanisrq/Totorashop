<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: ../pages/login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("db_connect.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Datos principales
    $dni = trim($_POST['dni']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $fecha_n = trim($_POST['fecha_ncmnto']);
    $telefono = trim($_POST['telefono']);
    $id_tipo_crg = intval($_POST['id_tipo_crg']);
    $fecha_cntrto = trim($_POST['fecha_cntrto']);
    $salario = trim($_POST['salario']);
    $id_almcen = intval($_POST['id_almcen']);
    $password = trim($_POST['password']);

    // Dirección
    $ciudad = trim($_POST['ciudad']);
    $distrito = trim($_POST['distrito']);
    $nro_calle = trim($_POST['nro_calle']);
    $referencia = trim($_POST['referencia'] ?? '');

    // Insertar dirección
    $stmt = $conn->prepare("
        INSERT INTO direccion (ciudad, distrito, nro_calle, referencia)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("ssss", $ciudad, $distrito, $nro_calle, $referencia);

    if ($stmt->execute()) {
        $id_direccion = $conn->insert_id;

        // Encriptar contraseña
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Insertar empleado
        $stmt2 = $conn->prepare("
            INSERT INTO empleado (dni, nombre, apellido, fecha_ncmnto, telefono, id_tipo_crg, fecha_cntrto, salario, id_direccion, id_almcen, contraseña)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt2->bind_param(
            "sssssisidss",
            $dni, $nombre, $apellido, $fecha_n, $telefono,
            $id_tipo_crg, $fecha_cntrto, $salario, $id_direccion, $id_almcen, $hashed
        );

        if ($stmt2->execute()) {
            header("Location: ../pages/botonEmpleados.php?mensaje=empleado_creado");
            exit();
        } else {
            $mensaje = "Error al registrar el empleado.";
        }

    } else {
        $mensaje = "Error al registrar la dirección.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar empleado</title>
<link rel="stylesheet" href="../assets/css/empleadoStyle.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>

<div class="top-bar">
    <div class="top-left">
        <a href="../pages/botonEmpleados.php" class="back-button">&#8592;</a>
        <span class="welcome-message">Agregar empleado</span>
    </div>
</div>

<div class="w3-container w3-padding">

    <?php if ($mensaje): ?>
        <p class="w3-text-red"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <div class="w3-card-4 w3-white w3-padding">

        <form method="POST">

            <label>DNI:</label>
            <input class="w3-input w3-margin-bottom" name="dni" required>

            <label>Nombre:</label>
            <input class="w3-input w3-margin-bottom" name="nombre" required>

            <label>Apellido:</label>
            <input class="w3-input w3-margin-bottom" name="apellido" required>

            <label>Fecha nacimiento:</label>
            <input class="w3-input w3-margin-bottom" type="date" name="fecha_ncmnto" required>

            <label>Teléfono:</label>
            <input class="w3-input w3-margin-bottom" name="telefono">

            <label>ID cargo:</label>
            <input class="w3-input w3-margin-bottom" name="id_tipo_crg" type="number" required>

            <label>Fecha contrato:</label>
            <input class="w3-input w3-margin-bottom" type="date" name="fecha_cntrto">

            <label>Salario:</label>
            <input class="w3-input w3-margin-bottom" name="salario" type="number" step="0.01">

            <label>ID almacén:</label>
            <input class="w3-input w3-margin-bottom" name="id_almcen" type="number" required>

            <label>Contraseña:</label>
            <input class="w3-input w3-margin-bottom" type="password" name="password" required>


            <h4 class="w3-margin-top">Dirección</h4>

            <label>Ciudad:</label>
            <input class="w3-input w3-margin-bottom" name="ciudad" required>

            <label>Distrito:</label>
            <input class="w3-input w3-margin-bottom" name="distrito" required>

            <label>Número de calle:</label>
            <input class="w3-input w3-margin-bottom" name="nro_calle" required>

            <label>Referencia (opcional):</label>
            <input class="w3-input w3-margin-bottom" name="referencia">

            <button class="w3-button w3-black w3-margin-top" type="submit">Registrar empleado</button>
        </form>
    </div>
</div>
</body>
</html>
