<?php
session_start();
include '../include/header.php';
?>

<link rel="stylesheet" href="../assets/css/pedido.css">
<link rel="stylesheet" href="../assets/css/pedidoModal.css">
<main>
    <h2 class="titulo-princ">Tu Pedido</h2>

    <div class="pedido-tabla">
        <table>
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Stock</th>
                    <th>Precio Unit.</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="pedido-body">
                <?php
                $total = 0;

                if (isset($_SESSION['pedido']) && count($_SESSION['pedido']) > 0):
                    foreach ($_SESSION['pedido'] as $id => $item):
                        $subtotal = $item['cantidad'] * $item['precio'];
                        $total += $subtotal;
                ?>
                <tr data-id="<?= $id ?>">
                    <td><img src="<?= $item['imagen'] ?>" class="pedido-img"></td>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td><?= $item['stock'] ?></td>
                    <td>S/ <?= number_format($item['precio'], 2) ?></td>
                    <td>
                        <input type="number"
                            class="pedido-cantidad"
                            value="<?= $item['cantidad'] ?>"
                            min="1"
                            max="<?= $item['stock'] ?>">
                    </td>
                    <td class="pedido-subtotal">S/ <?= number_format($subtotal, 2) ?></td>
                    <td>
                        <button class="btn-eliminar">
                            <img src="../assets/img/eliminar.png" alt="Eliminar">
                        </button>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    <div class="pedido-total">
        <h3>Total: S/ <span id="total-precio"><?= number_format($total, 2) ?></span></h3>
        <button class="btn-hacer-pedido">Hacer Pedido</button>
    </div>
</main>
<script src="../assets/js/pedidoModal.js"></script>
<script src="../assets/js/pedido.js"></script>
<?php include '../include/footer.php'; ?>