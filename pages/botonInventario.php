<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: ../pages/login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("../include/db_connect.php");

// Consulta para mostrar productos con stock recibido por almacén
$sql = "
SELECT 
    p.id_producto,
    p.nombre AS nombre_producto,
    CASE WHEN p.perecible = 1 THEN 'Sí' ELSE 'No' END AS perecible,
    c.nombre AS categoria,
    pr.nombre AS proveedor,
    a.nombre AS almacen,
    COALESCE(i.cantidad, 0) AS cantidad_en_almacen
FROM producto p
LEFT JOIN categoria c ON c.id_catgria = p.id_catgria
LEFT JOIN inventario i ON i.id_producto = p.id_producto
LEFT JOIN almacen a ON a.id_almcen = i.id_almcen

-- Proveedor SI EXISTE (solo si el producto vino de una orden)
LEFT JOIN entrada e ON e.id_invntrio = i.id_invntrio
LEFT JOIN detalle_compra dc ON dc.id_dtlle_oc = e.id_dtlle_oc
LEFT JOIN orden_compra oc ON oc.id_ordcmpra = dc.id_ordcmpra
LEFT JOIN proveedor pr ON pr.id_provdor = oc.id_provdor

ORDER BY p.id_producto, a.id_almcen
";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Estilos -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../assets/css/empleadoStyle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
</head>
<body>

<!-- Franja superior -->
<div class="top-bar">
    <div class="top-left">
        <a href="panelEmpleado.php" class="back-button">&#8592;</a>
        <span class="welcome-message">Inventario</span>
    </div>
    <div class="user-box">
        <a href="../include/logoutEmpleado.php" class="logout-button">Cerrar sesión</a>
        <img src="https://www.w3schools.com/howto/img_avatar.png" class="user-avatar-small" alt="Avatar">
    </div>
</div>

<!-- Contenido principal -->
<div class="container my-4">
    <div class="table-responsive bg-white p-3 shadow-sm rounded-4">
        <table id="tablaInventario" class="table table-bordered table-hover dt-responsive nowrap w-100">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Perecible</th>
                    <th>Categoría</th>
                    <th>Proveedor</th>
                    <th>Almacén</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado->num_rows > 0): ?>
                    <?php while($fila = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?= $fila['id_producto'] ?></td>
                            <td><?= htmlspecialchars($fila['nombre_producto']) ?></td>
                            <td><?= $fila['perecible'] ?></td>
                            <td><?= htmlspecialchars($fila['categoria'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($fila['proveedor'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($fila['almacen'] ?? '-') ?></td>
                            <td><?= $fila['cantidad_en_almacen'] ?? 0 ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7">No hay productos recibidos aún.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<a href="../include/agregar_productos.php" class="add-button" title="Agregar producto">+</a>
<!-- Activar DataTable -->
<script>
$(document).ready(function() {
    $('#tablaInventario').DataTable({
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