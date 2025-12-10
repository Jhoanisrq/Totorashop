<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("../include/db_connect.php");

// Obtener datos de todas las tablas
$tablas = [
    'todos' => "
        SELECT 'Entrada' AS tipo, e.id_entrada AS id, i.id_producto, p.nombre AS producto, e.cantidad, e.costo
        FROM entrada e
        INNER JOIN inventario i ON e.id_invntrio = i.id_invntrio
        INNER JOIN producto p ON i.id_producto = p.id_producto
        UNION ALL
        SELECT 'Salida', s.id_salida, i.id_producto, p.nombre, s.cantidad, s.costo
        FROM salida s
        INNER JOIN inventario i ON s.id_invntrio = i.id_invntrio
        INNER JOIN producto p ON i.id_producto = p.id_producto
        UNION ALL
        SELECT 'Ajuste', a.id_ajuste, i.id_producto, p.nombre, a.cantidad, NULL
        FROM ajuste a
        INNER JOIN inventario i ON a.id_invntrio = i.id_invntrio
        INNER JOIN producto p ON i.id_producto = p.id_producto
    ",
    'entrada' => "
        SELECT e.id_entrada AS id, i.id_producto, p.nombre AS producto, e.cantidad, e.costo
        FROM entrada e
        INNER JOIN inventario i ON e.id_invntrio = i.id_invntrio
        INNER JOIN producto p ON i.id_producto = p.id_producto
    ",
    'salida' => "
        SELECT s.id_salida AS id, i.id_producto, p.nombre AS producto, s.cantidad, s.costo
        FROM salida s
        INNER JOIN inventario i ON s.id_invntrio = i.id_invntrio
        INNER JOIN producto p ON i.id_producto = p.id_producto
    ",
    'ajuste' => "
        SELECT a.id_ajuste AS id, i.id_producto, p.nombre AS producto, a.cantidad, NULL AS costo
        FROM ajuste a
        INNER JOIN inventario i ON a.id_invntrio = i.id_invntrio
        INNER JOIN producto p ON i.id_producto = p.id_producto
    "
];
$datos = [];
foreach($tablas as $key => $sql) {
    $res = $conn->query($sql);
    $datos[$key] = [];
    while($fila = $res->fetch_assoc()) {
        $datos[$key][] = $fila;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Movimientos de Inventario</title>
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
        <span class="welcome-message">Movimientos de Inventario</span>
    </div>
    <div class="user-box">
        <a href="../include/logoutEmpleado.php" class="logout-button">Cerrar sesión</a>
        <img src="https://www.w3schools.com/howto/img_avatar.png" class="user-avatar-small" alt="Avatar">
    </div>
</div>

<div class="container my-4">

    <div class="mb-3">
        <button class="btn btn-primary me-2" onclick="mostrarTabla('todos')">Todos</button>
        <button class="btn btn-success me-2" onclick="mostrarTabla('entrada')">Entrada</button>
        <button class="btn btn-danger me-2" onclick="mostrarTabla('salida')">Salida</button>
        <button class="btn btn-warning me-2" onclick="mostrarTabla('ajuste')">Ajuste</button>
    </div>

    <?php foreach($datos as $tipo => $filas): ?>
    <div class="table-responsive mb-4" id="tabla-<?= $tipo ?>" style="display:none;">
        <table class="table table-bordered table-hover dt-responsive nowrap w-100">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Costo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($filas as $fila): ?>
                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= htmlspecialchars($fila['producto']) ?></td>
                    <td><?= $fila['cantidad'] ?></td>
                    <td><?= isset($fila['costo']) && $fila['costo']!==NULL ? 'S/ '.number_format($fila['costo'],2) : '-' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endforeach; ?>

    <h4 id="totalMovimientos" class="mt-3">Total: S/ 0.00</h4>

</div>

<script src="../assets/js/movimientos.js"></script>
</body>
</html>
<?php $conn->close(); ?>
