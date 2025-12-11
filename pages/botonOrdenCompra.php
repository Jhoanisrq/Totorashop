<?php
//../pages/botonOrdenCompra/
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("../include/db_connect.php");

// Obtener todas las órdenes de compra con proveedor y empleado
$sql = "
SELECT 
    oc.id_ordcmpra,
    oc.fecha_ordcmpra,
    oc.estado,
    oc.precio,
    p.nombre AS proveedor,
    e.nombre AS empleado_nombre,
    e.apellido AS empleado_apellido
FROM orden_compra oc
INNER JOIN proveedor p ON oc.id_provdor = p.id_provdor
INNER JOIN empleado e ON oc.id_empldo = e.id_empldo
ORDER BY oc.fecha_ordcmpra DESC
";

$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Órdenes de Compra</title>

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
        <span class="welcome-message">Órdenes de Compra</span>
    </div>
    <div class="user-box">
        <a href="../include/logoutEmpleado.php" class="logout-button">Cerrar sesión</a>
        <img src="https://www.w3schools.com/howto/img_avatar.png" class="user-avatar-small" alt="Avatar">
    </div>
</div>

<div class="container my-4">

    <div class="table-responsive bg-white p-3 shadow-sm rounded-4">
        <table id="tablaOC" class="table table-bordered table-hover dt-responsive nowrap w-100">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Proveedor</th>
                    <th>Empleado</th>
                    <th>Precio total</th>
                    <th>Opciones</th>
                </tr>
            </thead>

            <tbody>
                <?php while($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['id_ordcmpra'] ?></td>
                    <td><?= $fila['fecha_ordcmpra'] ?></td>
                    <td>
                        <span class="badge bg-<?=
                            $fila['estado'] === 'Pendiente' ? 'warning' :
                            ($fila['estado'] === 'Recibido' ? 'success' : 'secondary')
                        ?>">
                            <?= $fila['estado'] ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($fila['proveedor']) ?></td>
                    <td><?= htmlspecialchars($fila['empleado_nombre']." ".$fila['empleado_apellido']) ?></td>
                    <td>S/ <?= number_format($fila['precio'], 2) ?></td>

                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-dark dropdown-toggle" data-bs-toggle="dropdown">
                                Opciones
                            </button>
                            <ul class="dropdown-menu">

                                <li>
                                    <a class="dropdown-item" 
                                       href="../include/detalleOrdenCompra.php?id=<?= $fila['id_ordcmpra'] ?>">
                                        📄 Ver detalle
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" 
                                       href="../include/editar_estado_oc.php?id=<?= $fila['id_ordcmpra'] ?>">
                                        ✏️ Editar estado
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <form action="../include/eliminar_oc.php" method="POST"
                                          onsubmit="return confirm('¿Eliminar orden de compra?');">
                                        <input type="hidden" name="id" value="<?= $fila['id_ordcmpra'] ?>">
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

    <a href="../include/agregar_orden_compra.php" class="add-button" title="Agregar orden de compra">+</a>

</div>

<script>
$(function() {
    $('#tablaOC').DataTable({
        responsive: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});
</script>

</body>
</html>

<?php $conn->close(); ?>
