<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("../include/db_connect.php");

$sql = "
SELECT 
    p.id_provdor,
    p.nombre,
    p.telefono,
    p.correo,
    d.ciudad,
    d.distrito,
    d.nro_calle,
    d.referencia
FROM proveedor p
LEFT JOIN direccion d ON p.id_drccion = d.id_drccion
";

$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Proveedores</title>

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="../assets/css/empleadoStyle.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
</head>
<body>

<div class="top-bar">
    <div class="top-left">
        <a href="panelEmpleado.php" class="back-button">&#8592;</a>
        <span class="welcome-message">Proveedores</span>
    </div>
    <div class="user-box">
        <a href="../include/logoutEmpleado.php" class="logout-button">Cerrar sesión</a>
        <img src="https://www.w3schools.com/howto/img_avatar.png" class="user-avatar-small" alt="Avatar">
    </div>
</div>

<div class="container my-4">
    <div class="table-responsive bg-white p-3 shadow-sm rounded-4">
        <table id="tablaProveedores" class="table table-bordered table-hover dt-responsive nowrap w-100">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección completa</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= $fila['id_provdor'] ?></td>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= $fila['telefono'] ?? '-' ?></td>
                        <td><?= htmlspecialchars($fila['correo'] ?? '-') ?></td>
                        <td>
                            <?= htmlspecialchars(
                                "{$fila['ciudad']}, {$fila['distrito']}, Calle {$fila['nro_calle']}" .
                                ($fila['referencia'] ? " (Ref: {$fila['referencia']})" : "")
                            ) ?>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    Opciones
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="../include/editar_proveedor.php?id=<?= $fila['id_provdor'] ?>">✏️ Editar</a></li>
                                    <li>
                                        <form method="POST" action="../include/eliminar_proveedor.php" onsubmit="return confirm('¿Seguro de eliminar este proveedor?');">
                                            <input type="hidden" name="id" value="<?= $fila['id_provdor'] ?>">
                                            <button class="dropdown-item text-danger">🗑️ Eliminar</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <a href="../include/agregar_proveedor.php" class="add-button" title="Agregar proveedor">+</a>
</div>

<script>
$(function() {
    $('#tablaProveedores').DataTable({
        responsive: true,
        language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
    });
});
</script>

</body>
</html>

<?php $conn->close(); ?>