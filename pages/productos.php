<?php
session_start();
include '../include/db_connect.php';
include '../include/header.php';
?>
<head>
    <link rel="stylesheet" href="../assets/css/productos.css">
    <link rel="stylesheet" href="../assets/css/modal.css">
</head>
<main>
    <div class="productos-grid"></div>
</main>
<?php include '../include/footer.php'; ?>
<script src="../assets/js/modalProducto.js"></script>
<script src="../assets/js/productos.js"></script>