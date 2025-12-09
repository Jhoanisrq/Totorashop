<?php
include 'db_connect.php'; 
// Obtener categorías
$sql = "SELECT id_catgria, nombre FROM categoria";
$result = $conn->query($sql);
?>
<header>    
    <style>
        .search-results {
            position: absolute;
            top: 60px;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 90%;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        .search-result-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }
        .search-result-item:hover {
            background: #f9f9f9;
        }
        .search-result-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 10px;
        }
        .search-result-item h4 {
            margin: 0;
            font-size: 1rem;
            color: #333;
        }
        .search-result-item p {
            margin: 5px 0 0;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
    <link rel="stylesheet" href="../assets/css/header.css">
    <!-- Primera fila: Logo, búsqueda, Sign In, My Orders, Carrito -->
    <div class="header-top">
        <div class="logo">
            <a href="../pages/productos.php">
                <img src="../assets/img/logoTotorashop.png" alt="Totorashop">
            </a>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Buscar producto">
            <button>Buscar</button>
        </div>
        <div class="user-actions">
            <a href="<?php echo isset($_SESSION['id_cliente']) ? '../include/logout.php':'../pages/usuario.php'; ?>" class="sign-in">
                <img src="../assets/img/sign_in.jpg" alt="Sign In">
                <span>
                    <?php if (isset($_SESSION['id_cliente'])): ?>
                        Cerrar sesión
                    <?php else: ?>
                        Iniciar sesión
                    <?php endif; ?>
                </span>
            </a>
            <a href="#" class="pedido">
                <img src="../assets/img/pedidos.jpg" alt="pedidos">
                <span>Pedido (<span id="pedido-count">0</span>)</span>
            </a>
        </div>
    </div>
    <div class="espacio"></div>
    <!-- Segunda fila: Links a otras páginas -->
    <nav class="header-middle">
        <?php while($row = $result->fetch_assoc()): ?>
        <a href="categoria.php?id=<?php echo $row['id_catgria']; ?>">
            <?php echo htmlspecialchars($row['nombre']); ?>
        </a>
        <?php endwhile; ?>
    </nav>
   <!--<script src="../assets/js/search.js"></script>-->
</header>

<div class="user-actions">
    <?php if (isset($_SESSION['id_cliente'])): ?>
        <h4>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h4>
    <?php else: ?>
    <?php endif; ?>
</div>