<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: ../pages/login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("db_connect.php");

if (!isset($_GET['id'])) {
    header("Location: ../pages/botonEmpleados.php?error=ID no proporcionado");
    exit();
}

$id = intval($_GET['id']);
$mensaje = "";

// Obtener datos del empleado y dirección
$stmt = $conn->prepare("
    SELECT e.*, d.ciudad, d.distrito, d.nro_calle, d.referencia
    FROM empleado e
    LEFT JOIN direccion d ON e.id_direccion = d.id_drccion
    WHERE e.id_empldo = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$empleado = $res->fetch_assoc();
$stmt->close();

if (!$empleado) {
    header("Location: ../pages/botonEmpleados.php?error=Empleado no encontrado");
    exit();
}

// Procesar edición
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $dni = trim($_POST['dni']);
    $telefono = trim($_POST['telefono']);
    $fecha_n = trim($_POST['fecha_ncmnto']);
    $id_tipo_crg = intval($_POST['id_tipo_crg']);
    $fecha_cntrto = trim($_POST['fecha_cntrto']);
    $salario = trim($_POST['salario']);
    $id_almcen = intval($_POST['id_almcen']);
    $new_password = trim($_POST['password']);

    // Dirección
    $ciudad = trim($_POST['ciudad']);
    $distrito = trim($_POST['distrito']);
    $nro_calle = trim($_POST['nro_calle']);
    $referencia = trim($_POST['referencia']);

    // Actualizar dirección
    $stmt = $conn->prepare("UPDATE direccion SET ciudad=?, distrito=?, nro_calle=?, referencia=? WHERE id_drccion=?");
    $stmt->bind_param("ssssi", $ciudad, $distrito, $nro_calle, $referencia, $empleado['id_direccion']);
    $stmt->execute();

    // Actualizar empleado
    if ($new_password !== "") {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            UPDATE empleado 
            SET dni=?, nombre=?, apellido=?, fecha_ncmnto=?, telefono=?, id_tipo_crg=?, fecha_cntrto=?, salario=?, id_almcen=?, contraseña=?
            WHERE id_empldo=?
        ");
        $stmt->bind_param(
            "sssssisissi",
            $dni, $nombre, $apellido, $fecha_n, $telefono,
            $id_tipo_crg, $fecha_cntrto, $salario, $id_almcen, $hashed, $id
        );
    } else {
        $stmt = $conn->prepare("
            UPDATE empleado 
            SET dni=?, nombre=?, apellido=?, fecha_ncmnto=?, telefono=?, id_tipo_crg=?, fecha_cntrto=?, salario=?, id_almcen=?
            WHERE id_empldo=?
        ");
        $stmt->bind_param(
            "sssssisisi",
            $dni, $nombre, $apellido, $fecha_n, $telefono,
            $id_tipo_crg, $fecha_cntrto, $salario, $id_almcen, $id
        );
    }

    if ($stmt->execute()) {
        header("Location: ../pages/botonEmpleados.php?mensaje=empleado_actualizado");
        exit();
    } else {
        $mensaje = "Error al actualizar.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar empleado</title>
<link rel="stylesheet" href="../assets/css/empleadoStyle.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>

<div class="top-bar">
    <div class="top-left">
        <a href="../pages/botonEmpleados.php" class="back-button">&#8592;</a>
        <span class="welcome-message">Editar empleado</span>
    </div>
</div>

<div class="w3-container w3-padding">

    <?php if ($mensaje): ?>
        <p class="w3-text-red"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <div class="w3-card-4 w3-white w3-padding">

        <form method="POST">

            <label>DNI:</label>
            <input class="w3-input w3-margin-bottom" name="dni" value="<?= htmlspecialchars($empleado['dni']) ?>" required>

            <label>Nombre:</label>
            <input class="w3-input w3-margin-bottom" name="nombre" value="<?= htmlspecialchars($empleado['nombre']) ?>" required>

            <label>Apellido:</label>
            <input class="w3-input w3-margin-bottom" name="apellido" value="<?= htmlspecialchars($empleado['apellido']) ?>" required>

            <label>Fecha nacimiento:</label>
            <input class="w3-input w3-margin-bottom" type="date" name="fecha_ncmnto" value="<?= $empleado['fecha_ncmnto'] ?>">

            <label>Teléfono:</label>
            <input class="w3-input w3-margin-bottom" name="telefono" value="<?= htmlspecialchars($empleado['telefono']) ?>">

            <label>ID cargo:</label>
            <input class="w3-input w3-margin-bottom" name="id_tipo_crg" type="number" value="<?= $empleado['id_tipo_crg'] ?>">

            <label>Fecha contrato:</label>
            <input class="w3-input w3-margin-bottom" type="date" name="fecha_cntrto" value="<?= $empleado['fecha_cntrto'] ?>">

            <label>Salario:</label>
            <input class="w3-input w3-margin-bottom" name="salario" type="number" step="0.01" value="<?= $empleado['salario'] ?>">

            <label>ID almacén:</label>
            <input class="w3-input w3-margin-bottom" name="id_almcen" type="number" value="<?= $empleado['id_almcen'] ?>">

            <label>Nueva contraseña (opcional):</label>
            <input class="w3-input w3-margin-bottom" type="password" name="password" placeholder="Dejar vacío para no cambiar">


            <h4 class="w3-margin-top">Dirección</h4>

            <label>Ciudad:</label>
            <input class="w3-input w3-margin-bottom" name="ciudad" value="<?= $empleado['ciudad'] ?>">

            <label>Distrito:</label>
            <input class="w3-input w3-margin-bottom" name="distrito" value="<?= $empleado['distrito'] ?>">

            <label>Número de calle:</label>
            <input class="w3-input w3-margin-bottom" name="nro_calle" value="<?= $empleado['nro_calle'] ?>">

            <label>Referencia:</label>
            <input class="w3-input w3-margin-bottom" name="referencia" value="<?= $empleado['referencia'] ?>">

            <button class="w3-button w3-black w3-margin-top" type="submit">Guardar cambios</button>
        </form>
    </div>
</div>

</body>
</html>