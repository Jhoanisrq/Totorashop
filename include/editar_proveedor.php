<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: ../pages/login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("db_connect.php");

if (!isset($_GET['id'])) {
    header("Location: ../pages/botonProveedores.php?error=ID no proporcionado");
    exit();
}

$id_proveedor = intval($_GET['id']);
$mensaje = "";

// Obtener datos del proveedor y dirección
$stmt = $conn->prepare("
    SELECT pr.*, d.ciudad, d.distrito, d.nro_calle, d.referencia 
    FROM proveedor pr
    LEFT JOIN direccion d ON pr.id_drccion = d.id_drccion
    WHERE pr.id_provdor = ?
");
$stmt->bind_param("i", $id_proveedor);
$stmt->execute();
$result = $stmt->get_result();
$proveedor = $result->fetch_assoc();
$stmt->close();

if (!$proveedor) {
    header("Location: ../pages/botonProveedores.php?error=Proveedor no encontrado");
    exit();
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $ciudad = trim($_POST['ciudad']);
    $distrito = trim($_POST['distrito']);
    $nro_calle = trim($_POST['nro_calle']);
    $referencia = trim($_POST['referencia'] ?? '');

    // Actualizar dirección
    $stmt = $conn->prepare("UPDATE direccion SET ciudad=?, distrito=?, nro_calle=?, referencia=? WHERE id_drccion=?");
    $stmt->bind_param("ssssi", $ciudad, $distrito, $nro_calle, $referencia, $proveedor['id_drccion']);
    $stmt->execute();
    $stmt->close();

    // Actualizar proveedor
    $stmt = $conn->prepare("UPDATE proveedor SET nombre=?, telefono=?, correo=? WHERE id_provdor=?");
    $stmt->bind_param("sssi", $nombre, $telefono, $correo, $id_proveedor);
    if ($stmt->execute()) {
        header("Location: ../pages/botonProveedores.php?mensaje=actualizado");
        exit();
    } else {
        $mensaje = "Error al actualizar: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Proveedor</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="../assets/css/empleadoStyle.css">
</head>
<body>

<div class="top-bar">
    <div class="top-left">
        <a href="../pages/botonProveedores.php" class="back-button">&#8592;</a>
        <span class="welcome-message">Editar proveedor</span>
    </div>
</div>

<div class="w3-container w3-padding">
    <?php if ($mensaje): ?>
        <p style="color: red;"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <div class="w3-card-4 w3-white w3-container w3-padding">
        <form method="POST">
            <label>Nombre:</label>
            <input class="w3-input w3-margin-bottom" name="nombre" value="<?= htmlspecialchars($proveedor['nombre']) ?>" required>

            <label>Teléfono:</label>
            <input class="w3-input w3-margin-bottom" name="telefono" value="<?= htmlspecialchars($proveedor['telefono'] ?? '') ?>">

            <label>Correo:</label>
            <input class="w3-input w3-margin-bottom" name="correo" type="email" value="<?= htmlspecialchars($proveedor['correo'] ?? '') ?>">

            <label>Ciudad:</label>
            <input class="w3-input w3-margin-bottom" name="ciudad" value="<?= htmlspecialchars($proveedor['ciudad'] ?? '') ?>">

            <label>Distrito:</label>
            <input class="w3-input w3-margin-bottom" name="distrito" value="<?= htmlspecialchars($proveedor['distrito'] ?? '') ?>">

            <label>Nro. Calle:</label>
            <input class="w3-input w3-margin-bottom" name="nro_calle" value="<?= htmlspecialchars($proveedor['nro_calle'] ?? '') ?>">

            <label>Referencia (opcional):</label>
            <input class="w3-input w3-margin-bottom" name="referencia" value="<?= htmlspecialchars($proveedor['referencia'] ?? '') ?>">

            <button class="w3-button w3-black w3-margin-top" type="submit">Guardar cambios</button>
        </form>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>