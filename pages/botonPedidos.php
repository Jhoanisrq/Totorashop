<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("../include/db_connect.php");

// Obtener todos los pedidos con cliente y dirección
$sql = "
SELECT 
    p.num_pddo,
    p.fecha_pddo,
    p.estado,
    p.costo,
    c.nombre AS cliente_nombre,
    c.apellido AS cliente_apellido,
    CONCAT(d.ciudad, ', ', d.distrito, ', ', d.nro_calle, IF(d.referencia IS NOT NULL, CONCAT(' (', d.referencia, ')'), '')) AS direccion
FROM pedido p
INNER JOIN cliente_direccion cd ON p.id_clnte_drccion = cd.id_clnte_drccion
INNER JOIN cliente c ON cd.id_cliente = c.id_cliente
INNER JOIN direccion d ON cd.id_drccion = d.id_drccion
ORDER BY p.fecha_pddo DESC
";

$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pedidos</title>

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
        <span class="welcome-message">Pedidos</span>
    </div>
    <div class="user-box">
        <a href="../include/logoutEmpleado.php" class="logout-button">Cerrar sesión</a>
        <img src="https://www.w3schools.com/howto/img_avatar.png" class="user-avatar-small" alt="Avatar">
    </div>
</div>

<div class="container my-4">
    <div class="table-responsive bg-white p-3 shadow-sm rounded-4">
        <table id="tablaPedidos" class="table table-bordered table-hover dt-responsive nowrap w-100">
            <thead class="table-light">
                <tr>
                    <th>Número</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Cliente</th>
                    <th>Dirección</th>
                    <th>Costo total</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['num_pddo'] ?></td>
                    <td><?= $fila['fecha_pddo'] ?></td>
                    <td>
                        <span class="badge bg-<?= 
                            $fila['estado'] === 'Pendiente' ? 'warning' :
                            ($fila['estado'] === 'Enviado' ? 'success' : 'secondary')
                        ?>">
                            <?= $fila['estado'] ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($fila['cliente_nombre']." ".$fila['cliente_apellido']) ?></td>
                    <td><?= htmlspecialchars($fila['direccion']) ?></td>
                    <td>S/ <?= number_format($fila['costo'], 2) ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-dark dropdown-toggle" data-bs-toggle="dropdown">
                                Opciones
                            </button>
                            <ul class="dropdown-menu">

                                <li>
                                    <a class="dropdown-item" 
                                       href="../include/detallePedido.php?id=<?= $fila['num_pddo'] ?>">
                                        📄 Ver detalle
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" 
                                       href="../include/editar_estado_pedido.php?id=<?= $fila['num_pddo'] ?>">
                                        ✏️ Editar estado
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(function() {
    $('#tablaPedidos').DataTable({
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
