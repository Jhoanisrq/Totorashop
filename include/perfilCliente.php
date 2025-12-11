<?php
// ../include/perfilCliente.php
session_start();

if (!isset($_SESSION['id_cliente'])) {
    header("Location: ../pages/usuario.php?error=Debes iniciar sesión");
    exit();
}

require_once("db_connect.php");

$id_cliente = $_SESSION['id_cliente'];

// =====================
// OBTENER DATOS DEL CLIENTE
// =====================
$sqlCliente = "SELECT * FROM cliente WHERE id_cliente = ?";
$stmt = $conn->prepare($sqlCliente);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$cliente = $stmt->get_result()->fetch_assoc();

// =====================
// OBTENER DIRECCIONES
// =====================
$sqlDir = "
    SELECT d.id_drccion, d.ciudad, d.distrito, d.nro_calle, d.referencia
    FROM cliente_direccion cd
    INNER JOIN direccion d ON cd.id_drccion = d.id_drccion
    WHERE cd.id_cliente = ?
";
$stmt2 = $conn->prepare($sqlDir);
$stmt2->bind_param("i", $id_cliente);
$stmt2->execute();
$direcciones = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Perfil del Cliente</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .perfil-container {
        max-width: 800px;
        margin: auto;
        margin-top: 30px;
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .titulo {
        font-weight: bold;
        margin-bottom: 20px;
    }
    .seccion {
        margin-top: 30px;
    }
</style>
</head>
<body>

<?php include "header.php"; ?>

<div class="perfil-container">

    <h3 class="titulo">Mi Perfil</h3>

    <!-- DATOS DEL CLIENTE -->
    <div>
        <h5>Datos personales</h5>
        <hr>
        <p><strong>Nombre:</strong> <?= htmlspecialchars($cliente['nombre']) . " " . htmlspecialchars($cliente['apellido']) ?></p>
        <p><strong>Correo:</strong> <?= htmlspecialchars($cliente['correo']) ?></p>
        <p><strong>Fecha de nacimiento:</strong> <?= $cliente['fecha_ncmnto'] ?></p>
        <p><strong>Teléfono:</strong> <?= $cliente['telefono'] ?: "No registrado" ?></p>
    </div>


    <!-- DIRECCIONES -->
    <div class="seccion">
        <h5>Mis direcciones</h5>
        <hr>

        <?php if ($direcciones->num_rows > 0): ?>
            <?php while($d = $direcciones->fetch_assoc()): ?>
                <div class="border rounded p-3 mb-2">
                    <p><strong>Ciudad:</strong> <?= htmlspecialchars($d['ciudad']) ?></p>
                    <p><strong>Distrito:</strong> <?= htmlspecialchars($d['distrito']) ?></p>
                    <p><strong>Calle / Nro:</strong> <?= htmlspecialchars($d['nro_calle']) ?></p>
                    <p><strong>Referencia:</strong> <?= htmlspecialchars($d['referencia']) ?: "Sin referencia" ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">No tienes direcciones registradas.</p>
        <?php endif; ?>

        <!-- BOTÓN PARA AGREGAR DIRECCIÓN -->
        <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalDireccion">Agregar dirección</button>
    </div>

</div>


<!-- =====================
     MODAL AGREGAR DIRECCIÓN
===================== -->
<div class="modal fade" id="modalDireccion" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" action="registrar_direccion.php" method="POST">
      
      <div class="modal-header">
        <h5 class="modal-title">Agregar nueva dirección</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="mb-3">
            <label>Ciudad</label>
            <input type="text" name="ciudad" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Distrito</label>
            <input type="text" name="distrito" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Número y calle</label>
            <input type="text" name="nro_calle" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Referencia (opcional)</label>
            <textarea name="referencia" class="form-control"></textarea>
        </div>

      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>

    </form>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
