<?php
session_start();
include '../include/db_connect.php';
include '../include/header.php';

if (!isset($_GET['nombre'])) {
    die("Categoría inválida");
}

// Guardar nombre recibido
$nombre = $_GET['nombre'];

// Buscar categoría por el nombre
$catQuery = $conn->prepare("SELECT id_catgria, nombre FROM categoria WHERE nombre = ? LIMIT 1");
$catQuery->bind_param("s", $nombre);
$catQuery->execute();
$catResult = $catQuery->get_result();

if ($catResult->num_rows === 0) {
    die("Categoría no encontrada");
}

$categoria = $catResult->fetch_assoc();
$id = $categoria['id_catgria']; // ID que necesitamos para filtrar productos
?>
<head>
    <link rel="stylesheet" href="../assets/css/productos.css">
    <link rel="stylesheet" href="../assets/css/modal.css">
</head>

<main>
    <h2 class="titulo-princ">
        Categoría: <?php echo htmlspecialchars($categoria['nombre']); ?>
    </h2>

    <div class="productos-grid" 
         id="productos-categoria"
         data-id="<?php echo $id; ?>">
    </div>
</main>

<?php include '../include/footer.php'; ?>
<script src="../assets/js/modalProducto.js"></script>
<script src="../assets/js/categoria.js"></script>
