<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("db_connect.php");

if (!isset($_GET['id'])) {
    echo "ID no válido";
    exit();
}

$id = intval($_GET['id']);

// Obtener datos generales de la orden
$sqlOrden = "
SELECT 
    oc.*,
    p.nombre AS proveedor,
    e.nombre AS emp_nombre,
    e.apellido AS emp_apellido
FROM orden_compra oc
INNER JOIN proveedor p ON oc.id_provdor = p.id_provdor
INNER JOIN empleado e ON oc.id_empldo = e.id_empldo
WHERE oc.id_ordcmpra = ?
";

$stmt = $conn->prepare($sqlOrden);
$stmt->bind_param("i", $id);
$stmt->execute();
$orden = $stmt->get_result()->fetch_assoc();

if (!$orden) {
    echo "Orden no encontrada";
    exit();
}

// Obtener detalle de productos
$sqlDetalle = "
SELECT 
    dc.cantidad,
    dc.prcio_untr AS precio_unit,
    pr.nombre AS producto,
    pr.id_producto
FROM detalle_compra dc
INNER JOIN producto pr ON dc.id_producto = pr.id_producto
WHERE dc.id_ordcmpra = ?
";

$stmt2 = $conn->prepare($sqlDetalle);
$stmt2->bind_param("i", $id);
$stmt2->execute();
$detalles = $stmt2->get_result();

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalle Orden de Compra</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container my-4">

    <a href="../pages/botonOrdenCompra.php" class="btn btn-secondary mb-3">← Volver</a>

    <div class="card shadow-sm p-4">
        <h3>Orden de Compra #<?= $orden['id_ordcmpra'] ?></h3>
        <p><strong>Fecha:</strong> <?= $orden['fecha_ordcmpra'] ?></p>
        <p><strong>Estado:</strong> <?= $orden['estado'] ?></p>
        <p><strong>Proveedor:</strong> <?= htmlspecialchars($orden['proveedor']) ?></p>
        <p><strong>Empleado que solicitó:</strong> <?= htmlspecialchars($orden['emp_nombre'] . " " . $orden['emp_apellido']) ?></p>
        <p><strong>Precio Total:</strong> S/ <?= number_format($orden['precio'],2) ?></p>
    </div>

    <div class="card shadow-sm p-4 mt-4">
        <h4>Detalle de Productos</h4>

        <table class="table table-bordered mt-3">
            <thead class="table-light">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unit.</th>
                    <th>Total</th>
                    <th>Almacén</th>
                </tr>
            </thead>

            <tbody>
            <?php while ($fila = $detalles->fetch_assoc()): ?>

                <?php
                // Buscar en inventario si el producto ya está asignado a algún almacén
                $sqlInv = "
                SELECT i.*, a.nombre AS almacen_nombre
                FROM inventario i
                INNER JOIN almacen a ON i.id_almcen = a.id_almcen
                WHERE i.id_producto = ?
                LIMIT 1
                ";
                $stmt3 = $conn->prepare($sqlInv);
                $stmt3->bind_param("i", $fila['id_producto']);
                $stmt3->execute();
                $inv = $stmt3->get_result()->fetch_assoc();

                $almacen = $inv 
                    ? $inv['almacen_nombre']
                    : "Aún no asignado (esperando recepción)";
                ?>

                <tr>
                    <td><?= htmlspecialchars($fila['producto']) ?></td>
                    <td><?= $fila['cantidad'] ?></td>
                    <td>S/ <?= number_format($fila['precio_unit'], 2) ?></td>
                    <td>S/ <?= number_format($fila['precio_unit'] * $fila['cantidad'], 2) ?></td>
                    <td><?= htmlspecialchars($almacen) ?></td>
                </tr>

            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>

<?php
$stmt->close();
$stmt2->close();
$conn->close();
?>