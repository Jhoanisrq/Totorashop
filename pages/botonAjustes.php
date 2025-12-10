<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("../include/db_connect.php");

$id_empleado = $_SESSION['id_empleado'];

// Obtener almacén del empleado
$stmt = $conn->prepare("SELECT id_almcen FROM empleado WHERE id_empldo = ?");
$stmt->bind_param("i", $id_empleado);
$stmt->execute();
$id_almcen = $stmt->get_result()->fetch_assoc()['id_almcen'];
$stmt->close();

// Procesar ajuste si viene POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_invntrio = intval($_POST['id_invntrio']);
    $cantidad = intval($_POST['cantidad']);
    $motivo = $_POST['motivo'];

    // Insertar en tabla ajuste
    $stmt = $conn->prepare("INSERT INTO ajuste (id_invntrio, id_empldo, cantidad, motivo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $id_invntrio, $id_empleado, $cantidad, $motivo);
    $stmt->execute();
    $stmt->close();

    // Actualizar inventario
    $stmt = $conn->prepare("UPDATE inventario SET cantidad = cantidad + ? WHERE id_invntrio = ?");
    $stmt->bind_param("ii", $cantidad, $id_invntrio);
    $stmt->execute();
    $stmt->close();

    $mensaje = "Ajuste registrado correctamente.";
}

// Obtener productos del inventario de este almacén
$stmt = $conn->prepare("
    SELECT i.id_invntrio, p.nombre, i.cantidad
    FROM inventario i
    INNER JOIN producto p ON i.id_producto = p.id_producto
    WHERE i.id_almcen = ?
");
$stmt->bind_param("i", $id_almcen);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ajustes de Inventario</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/empleadoStyle.css">
</head>
<body class="bg-light">
<div class="top-bar">
    <div class="top-left">
        <a href="../pages/panelEmpleado.php" class="back-button">&#8592;</a>
        <span class="welcome-message">Ajustes de Inventario</span>
    </div>
    <div class="user-box">
        <a href="../include/logoutEmpleado.php" class="logout-button">Cerrar sesión</a>
        <img src="https://www.w3schools.com/howto/img_avatar.png" class="user-avatar-small" alt="Avatar">
    </div>
</div>
<div class="container my-4">

    <h3>Ajustes de Inventario</h3>

    <?php if(!empty($mensaje)) echo "<p class='text-success'>$mensaje</p>"; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Stock actual</th>
                <th>Ajustar</th>
            </tr>
        </thead>
        <tbody>
        <?php while($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($fila['nombre']) ?></td>
                <td><?= $fila['cantidad'] ?></td>
                <td>
                    <form method="POST" class="d-flex gap-2">
                        <input type="hidden" name="id_invntrio" value="<?= $fila['id_invntrio'] ?>">
                        <input type="number" name="cantidad" placeholder="± cantidad" class="form-control" required>
                        <input type="text" name="motivo" placeholder="Motivo" class="form-control" required>
                        <button class="btn btn-primary">Ajustar</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</div>

</body>
</html>
<?php $conn->close(); ?>
