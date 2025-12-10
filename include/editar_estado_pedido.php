<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: ../pages/login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("db_connect.php");

// Verificar ID
if (!isset($_GET['id'])) {
    header("Location: ../pages/botonPedidos.php?error=ID no proporcionado");
    exit();
}

$num_pddo = intval($_GET['id']);
$mensaje = "";

// Obtener datos del pedido + cliente + dirección
$stmt = $conn->prepare("
    SELECT p.*, 
           c.nombre AS cliente_nombre,
           c.apellido AS cliente_apellido,
           CONCAT(d.ciudad, ', ', d.distrito, ', ', d.nro_calle, IF(d.referencia IS NOT NULL, CONCAT(' (', d.referencia, ')'), '')) AS direccion
    FROM pedido p
    INNER JOIN cliente_direccion cd ON p.id_clnte_drccion = cd.id_clnte_drccion
    INNER JOIN cliente c ON cd.id_cliente = c.id_cliente
    INNER JOIN direccion d ON cd.id_drccion = d.id_drccion
    WHERE p.num_pddo = ?
");
$stmt->bind_param("i", $num_pddo);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();
$stmt->close();

if (!$pedido) {
    header("Location: ../pages/botonPedidos.php?error=Pedido no encontrado");
    exit();
}

// Estados finales — no modificables
$estadosFinales = ["Entregado", "Cancelado"];

// Función para actualizar inventario al enviar el pedido
function enviarPedido($numP, $conn) {
    $stmt_det = $conn->prepare("SELECT * FROM detalle_pedido WHERE num_pddo = ?");
    $stmt_det->bind_param("i", $numP);
    $stmt_det->execute();
    $res_det = $stmt_det->get_result();

    while($item = $res_det->fetch_assoc()) {
        $id_producto = $item['id_producto'];
        $cantidad = $item['cantidad'];

        // Buscar el inventario con mayor stock
        $stmt_inv = $conn->prepare("SELECT id_almcen, stock FROM inventario WHERE id_producto = ? ORDER BY stock DESC LIMIT 1");
        $stmt_inv->bind_param("i", $id_producto);
        $stmt_inv->execute();
        $inv = $stmt_inv->get_result()->fetch_assoc();
        $stmt_inv->close();

        if ($inv && $inv['stock'] >= $cantidad) {
            // Restar stock
            $stmt_upd = $conn->prepare("UPDATE inventario SET stock = stock - ? WHERE id_producto = ? AND id_almcen = ?");
            $stmt_upd->bind_param("iii", $cantidad, $id_producto, $inv['id_almcen']);
            $stmt_upd->execute();
            $stmt_upd->close();
        }
    }

    $stmt_det->close();
}

// Si se envió POST: actualizar
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nuevo = $_POST["estado"] ?? "";

    if (in_array($pedido['estado'], $estadosFinales)) {
        $mensaje = "Este pedido ya no puede modificarse.";
    } else {
        // Actualizar estado
        $stmt = $conn->prepare("UPDATE pedido SET estado = ? WHERE num_pddo = ?");
        $stmt->bind_param("si", $nuevo, $num_pddo);

        if ($stmt->execute()) {
            // Si cambió a Enviado, actualizar inventario
            if ($nuevo === "Enviado") {
                enviarPedido($num_pddo, $conn);
            }
            header("Location: ../pages/botonPedidos.php?mensaje=estado_actualizado");
            exit();
        } else {
            $mensaje = "Error al actualizar estado.";
        }
        $stmt->close();
    }
}

// Estados posibles
$estados = ["Pendiente", "Procesando", "Enviado", "Entregado", "Cancelado"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar estado Pedido</title>
<link rel="stylesheet" href="../assets/css/empleadoStyle.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>

<div class="top-bar">
    <div class="top-left">
        <a href="../pages/botonPedidos.php" class="back-button">&#8592;</a>
        <span class="welcome-message">Editar estado del Pedido #<?= $num_pddo ?></span>
    </div>
</div>

<div class="w3-container w3-padding">

    <?php if ($mensaje): ?>
        <p class="w3-text-red"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <div class="w3-card-4 w3-white w3-padding">
        <h4>Cliente: <?= htmlspecialchars($pedido['cliente_nombre'] . " " . $pedido['cliente_apellido']) ?></h4>
        <h4>Dirección: <?= htmlspecialchars($pedido['direccion']) ?></h4>
        <h4>Estado actual: <b><?= htmlspecialchars($pedido['estado']) ?></b></h4>

        <form method="POST">
            <label>Nuevo estado:</label>
            <select class="w3-input w3-margin-bottom" name="estado"
                <?= in_array($pedido['estado'], $estadosFinales) ? "disabled" : "" ?>
            >
                <?php foreach($estados as $e): ?>
                    <option value="<?= $e ?>" <?= $pedido['estado'] === $e ? "selected" : "" ?>>
                        <?= $e ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if (!in_array($pedido['estado'], $estadosFinales)): ?>
                <button class="w3-button w3-black w3-margin-top" type="submit">Guardar cambios</button>
            <?php else: ?>
                <p class="w3-text-grey">Este pedido no puede ser editado.</p>
            <?php endif; ?>
        </form>

    </div>
</div>

</body>
</html>
