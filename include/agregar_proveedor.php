<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: ../pages/login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("../include/db_connect.php");

$mensaje = "";

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Campos de proveedor
    $nombre     = $_POST['nombre'];
    $telefono   = $_POST['telefono'] ?? null;
    $correo     = $_POST['correo'] ?? null;

    // Campos de dirección
    $ciudad     = $_POST['ciudad'];
    $distrito   = $_POST['distrito'];
    $nro_calle  = $_POST['nro_calle'];
    $referencia = $_POST['referencia'] ?? null;

    // 1️⃣ Insertar la dirección primero
    $stmt_dir = $conn->prepare("
        INSERT INTO direccion (ciudad, distrito, nro_calle, referencia)
        VALUES (?, ?, ?, ?)
    ");
    $stmt_dir->bind_param("ssss", $ciudad, $distrito, $nro_calle, $referencia);

    if ($stmt_dir->execute()) {
        $id_direccion = $stmt_dir->insert_id;

        // 2️⃣ Luego insertar el proveedor con el ID de dirección
        $stmt_prov = $conn->prepare("
            INSERT INTO proveedor (nombre, telefono, correo, id_drccion)
            VALUES (?, ?, ?, ?)
        ");
        $stmt_prov->bind_param("sssi", $nombre, $telefono, $correo, $id_direccion);

        if ($stmt_prov->execute()) {
            header("Location: ../pages/botonProveedores.php?mensaje=agregado");
            exit();
        } else {
            $mensaje = "Error al guardar proveedor: " . $stmt_prov->error;
        }

        $stmt_prov->close();
    } else {
        $mensaje = "Error al guardar dirección: " . $stmt_dir->error;
    }

    $stmt_dir->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar Proveedor</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="../assets/css/empleadoStyle.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="top-bar">
    <div class="top-left">
        <a href="../pages/botonProveedores.php" class="back-button">&#8592;</a>
        <span class="welcome-message">Agregar proveedor</span>
    </div>
    <div class="user-box">
        <a href="../include/logoutEmpleado.php" class="logout-button">Cerrar sesión</a>
        <img src="https://www.w3schools.com/howto/img_avatar.png" class="user-avatar-small" alt="Avatar">
    </div>
</div>

<div class="container my-4">

    <?php if ($mensaje): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <div class="bg-white p-4 shadow-sm rounded-4">

        <form method="POST">

            <h4 class="mb-3">Información del proveedor</h4>

            <label>Nombre:</label>
            <input class="form-control mb-3" name="nombre" required>

            <label>Teléfono:</label>
            <input class="form-control mb-3" name="telefono">

            <label>Correo:</label>
            <input class="form-control mb-3" name="correo" type="email">

            <h4 class="mt-4 mb-3">Dirección</h4>

            <label>Ciudad:</label>
            <input class="form-control mb-3" name="ciudad" required>

            <label>Distrito:</label>
            <input class="form-control mb-3" name="distrito" required>

            <label>Número / Calle:</label>
            <input class="form-control mb-3" name="nro_calle" required>

            <label>Referencia:</label>
            <input class="form-control mb-3" name="referencia">

            <button class="btn btn-dark mt-3" type="submit">Guardar proveedor</button>

        </form>
    </div>
</div>
</body>
</html>
<?php $conn->close(); ?>