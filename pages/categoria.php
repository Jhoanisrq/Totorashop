<?php
session_start();
include '../includes/db_connect.php';
include '../includes/header.php';
?>
<head>
    <link rel="stylesheet" href="../assets/css/productos.css">
    <link rel="stylesheet" href="../assets/css/modal.css">
</head>
<main>
    <div class="filter-container">
        <label for="filter-categoria">Categoría:</label>
        <select id="filter-categoria">
            <option value="">Todas</option>
            <?php
            $stmt = $conn->query("SELECT nombr_catg FROM categorias");
            while ($row = $stmt->fetch_assoc()) {
                $selected = (isset($_GET['categoria']) && $_GET['categoria'] == $row['nombr_catg']) ? 'selected' : '';
                echo "<option value='{$row['nombr_catg']}' $selected>{$row['nombr_catg']}</option>";
            }
            ?>
        </select>
        <label for="filter-min-price">Precio Mínimo:</label>
        <input type="number" id="filter-min-price" min="0" step="0.1" placeholder="0">
        <label for="filter-max-price">Precio Máximo:</label>
        <input type="number" id="filter-max-price" min="0" step="0.1" placeholder="1000">
        <label for="filter-sort">Ordenar por:</label>
        <select id="filter-sort">
            <option value="nombre_asc">Nombre (A-Z)</option>
            <option value="precio_asc">Precio (Menor a Mayor)</option>
            <option value="precio_desc">Precio (Mayor a Menor)</option>
        </select>
    </div>
    <div class="categoria-grid" data-categoria="<?php echo isset($_GET['categoria']) ? htmlspecialchars($_GET['categoria']) : ''; ?>"></div>
</main>
<?php include '../includes/footer.php'; ?>
<script src="../assets/js/scriptCatg.js"></script>
<script src="../assets/js/filter.js"></script>