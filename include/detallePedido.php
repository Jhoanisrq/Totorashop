<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("../include/db_connect.php");

if (!isset($_GET['id'])) {
    echo "ID no válido";
    exit();
}

$id = intval($_GET['id']);

// Datos generales del pedido
$sqlPedido = "
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
WHERE p.num_pddo = ?
";

$stmt = $conn->prepare($sqlPedido);
$stmt->bind_param("i", $id);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();

if (!$pedido) {
    echo "Pedido no encontrado";
    exit();
}

// Detalle de productos
$sqlDetalle = "
SELECT 
    dp.cantidad,
    dp.prcio_untr AS precio_unit,
    pr.nombre AS producto,
    pr.id_producto
FROM detalle_pedido dp
INNER JOIN producto pr ON dp.id_producto = pr.id_producto
WHERE dp.num_pddo = ?
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
<title>Detalle del Pedido</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-4">

    <a href="../pages/botonPedidos.php" class="btn btn-secondary mb-3">← Volver</a>

    <div class="card shadow-sm p-4">
        <h3>Pedido #<?= $pedido['num_pddo'] ?></h3>
        <p><strong>Fecha:</strong> <?= $pedido['fecha_pddo'] ?></p>
        <p><strong>Estado:</strong> <?= $pedido['estado'] ?></p>
        <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['cliente_nombre']." ".$pedido['cliente_apellido']) ?></p>
        <p><strong>Dirección:</strong> <?= htmlspecialchars($pedido['direccion']) ?></p>
        <p><strong>Costo Total:</strong> S/ <?= number_format($pedido['costo'],2) ?></p>
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
                </tr>
            </thead>
            <tbody>
            <?php while ($fila = $detalles->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['producto']) ?></td>
                    <td><?= $fila['cantidad'] ?></td>
                    <td>S/ <?= number_format($fila['precio_unit'], 2) ?></td>
                    <td>S/ <?= number_format($fila['precio_unit'] * $fila['cantidad'], 2) ?></td>
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
