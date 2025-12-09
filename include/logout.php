<?php
session_start();
session_destroy();
header('Location: ../pages/productos.php');
exit;
?>