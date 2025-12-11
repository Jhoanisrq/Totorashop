<?php
session_start();

// Verificar si el empleado ha iniciado sesión
if (!isset($_SESSION['id_empleado'])) {
    header("Location: ../pages/login_empleado.php?error=Debes iniciar sesión primero.");
    exit();
}

$nombre_empleado = htmlspecialchars($_SESSION['nombre_empleado'] ?? '');
?>

<!DOCTYPE html>
<head>
    <title>Panel Empleado</title>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../assets/css/empleadoStyle.css">
</head>
<body>

<!-- Franja superior -->
<div class="top-bar" >
    <span class="welcome-message">Bienvenid@, <?= $nombre_empleado ?></span>
    <div class="user-box">
        <a href="../include/logoutEmpleado.php" class="logout-button">Cerrar sesión</a>
        <img src="https://www.w3schools.com/howto/img_avatar.png" class="user-avatar-small" alt="Avatar">
    </div>
</div>

<!-- Contenedor principal -->
<div class="w3-container w3-padding">

    <div class="w3-row-padding w3-margin-top">
        <!-- Botón 1: Proveedores -->
        <div class="w3-col l3 m6 s12">
            <a href="../pages/botonProveedores.php" class="option-link">    
                <div class="w3-card option-card w3-center">
                    <img src="../assets/img/Proveedores.jpg" alt="Proveedores" class="option-icon">
                    <div class="option-label">Proveedores</div>
                </div>
            </a>
        </div>
        <!-- Botón 2: Inventario -->
        <div class="w3-col l3 m6 s12">
            <a href="../pages/botonInventario.php" class="option-link">
                <div class="w3-card option-card w3-center">
                    <img src="../assets/img/Insumos.jpg" alt="Inventario" class="option-icon">
                    <div class="option-label">Inventario</div>
                </div>
            </a>
        </div>
        <!-- Botón 3: Orden de Compra -->
        <div class="w3-col l3 m6 s12">
            <a href="../pages/botonOrdenCompra.php" class="option-link">
                <div class="w3-card option-card w3-center">
                    <img src="../assets/img/orden_compra.png" alt="Orden de Compra" class="option-icon">
                    <div class="option-label">Orden de Compra</div>
                </div>
            </a>
        </div>

        <!-- Botón 4: Crear Empleado -->
        <div class="w3-col l3 m6 s12">
            <a href="../pages/botonEmpleados.php" class="option-link">
                <div class="w3-card option-card w3-center">
                    <img src="../assets/img/empleados.png" alt="Crear Empleado" class="option-icon">
                    <div class="option-label">Empleados</div>
                </div>
            </a>
        </div>

        <!-- Botón 5: Pedido -->
        <div class="w3-col l3 m6 s12">
            <a href="../pages/botonPedidos.php" class="option-link">
                <div class="w3-card option-card w3-center">
                    <img src="../assets/img/pedidos.jpg" alt="Pedidos" class="option-icon">
                    <div class="option-label">Pedidos</div>
                </div>
            </a>
        </div>

        <!-- Botón 6: Ajustes -->
        <div class="w3-col l3 m6 s12">
            <a href="../pages/botonAjustes.php" class="option-link">
                <div class="w3-card option-card w3-center">
                    <img src="../assets/img/ajuste.png" alt="Ajuste inventario" class="option-icon">
                    <div class="option-label">Ajuste</div>
                </div>
            </a>
        </div>

        <!-- Botón 7: Ajustes -->
        <div class="w3-col l3 m6 s12">
            <a href="../pages/botonMovimiento.php" class="option-link">
                <div class="w3-card option-card w3-center">
                    <img src="../assets/img/salidas.jpg" alt="Movimiento de inventario" class="option-icon">
                    <div class="option-label">Movimiento</div>
                </div>
            </a>
        </div>

        <!-- Botón 8: Exportar BD -->
        <div class="w3-col l3 m6 s12">
            <a href="../pages/botonExportarBD.php" class="option-link">
                <div class="w3-card option-card w3-center">
                    <img src="../assets/img/exportar_bd.png" alt="Exportar BD" class="option-icon">
                    <div class="option-label">Exportar BD</div>
                </div>
            </a>
        </div>

    </div>
</div>

</body>
</html>
