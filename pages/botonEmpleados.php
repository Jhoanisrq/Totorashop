<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("../include/db_connect.php");

// Traer empleados con su dirección y cargo
$sql = "
SELECT 
    e.id_empldo,
    e.dni,
    e.nombre,
    e.apellido,
    e.telefono,
    e.fecha_cntrto,
    e.salario,
    c.nombre AS cargo,
    d.ciudad,
    d.distrito,
    d.nro_calle,
    d.referencia
FROM empleado e
LEFT JOIN direccion d ON e.id_direccion = d.id_drccion
LEFT JOIN tipo_cargo c ON e.id_tipo_crg = c.id_tipo_crg
";

$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Empleados</title>

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
        <span class="welcome-message">Empleados</span>
    </div>
    <div class="user-box">
        <a href="../include/logoutEmpleado.php" class="logout-button">Cerrar sesión</a>
        <img src="https://www.w3schools.com/howto/img_avatar.png" class="user-avatar-small" alt="Avatar">
    </div>
</div>

<div class="container my-4">
    <div class="table-responsive bg-white p-3 shadow-sm rounded-4">
        <table id="tablaEmpleados" class="table table-bordered table-hover dt-responsive nowrap w-100">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Cargo</th>
                    <th>Fecha Contrato</th>
                    <th>Salario</th>
                    <th>Dirección</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['id_empldo'] ?></td>
                    <td><?= htmlspecialchars($fila['dni'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($fila['nombre'] . " " . $fila['apellido']) ?></td>
                    <td><?= htmlspecialchars($fila['telefono'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($fila['cargo'] ?? '-') ?></td>
                    <td><?= $fila['fecha_cntrto'] ?></td>
                    <td>S/ <?= number_format($fila['salario'], 2) ?></td>
                    <td>
                        <?= htmlspecialchars(
                            "{$fila['ciudad']}, {$fila['distrito']}, Calle {$fila['nro_calle']}" .
                            ($fila['referencia'] ? " (Ref: {$fila['referencia']})" : "")
                        ) ?>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-dark dropdown-toggle" data-bs-toggle="dropdown">
                                Opciones
                            </button>
                            <ul class="dropdown-menu shadow-sm">
                                <li>
                                    <a class="dropdown-item" href="../include/editar_empleado.php?id=<?= $fila['id_empldo'] ?>">
                                        ✏️ Editar empleado
                                    </a>
                                </li>
                                            
                                <li><hr class="dropdown-divider"></li>
                                            
                                <li>
                                    <form action="../include/eliminar_empleado.php" method="POST"
                                          onsubmit="return confirm('¿Eliminar este empleado? Esta acción no se puede deshacer.');">
                                        <input type="hidden" name="id" value="<?= $fila['id_empldo'] ?>">
                                        <button class="dropdown-item text-danger">
                                            🗑️ Eliminar empleado
                                        </button>
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

    <a href="../include/agregar_empleado.php" class="add-button" title="Agregar empleado">+</a>
</div>

<script>
$(function() {
    $('#tablaEmpleados').DataTable({
        responsive: true,
        language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
    });
});
</script>

</body>
</html>

<?php $conn->close(); ?>
