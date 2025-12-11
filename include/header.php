<?php
//../include/header.php
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
            <div class="user-dropdown">
        <button class="user-btn">
            <img src="../assets/img/sign_in.jpg" alt="User">
            <span>
                <?php echo isset($_SESSION['id_cliente']) ? $_SESSION['nombre'] : "Cuenta"; ?>
            </span>
            <i class="arrow">&#9662;</i>
        </button>

        <div class="dropdown-menu">
            <?php if (!isset($_SESSION['id_cliente'])): ?>
                <a href="../pages/usuario.php">Iniciar sesión</a>
             
            <?php else: ?>
                <a href="../include/perfilCliente.php">Mi perfil</a>
                <a href="../include/logout.php">Cerrar sesión</a>
            <?php endif; ?>
        </div>
    </div>

            <a href="../pages/pedido.php" class="pedido">
                <img src="../assets/img/pedidos.jpg" alt="pedidos">
                <?php 
                $pedidoCount = 0;
                if (isset($_SESSION['pedido'])) {
                    foreach ($_SESSION['pedido'] as $item) {
                        $pedidoCount += $item['cantidad'];
                    }
                }
                ?>
                <span>Pedido (<span id="pedido-count"><?= $pedidoCount ?></span>)</span>
            </a>
        </div>
    </div>
    <div class="espacio"></div>
    <!-- Segunda fila: Links a otras páginas -->
    <nav class="header-middle">
        <?php while($row = $result->fetch_assoc()): ?>
        <a href="categoria.php?nombre=<?php echo $row['nombre']; ?>"><?php echo htmlspecialchars($row['nombre']); ?></a>
        <?php endwhile; ?>
    </nav>
    <script src="../assets/js/search.js"></script>
    
</header>

<div class="user-actions">
    <?php if (isset($_SESSION['user_id'])): ?>
        <h4>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h4>
    <?php else: ?>
    <?php endif; ?>
</div>

<script>
document.querySelector(".user-btn").addEventListener("click", function(e) {
    e.stopPropagation();
    document.querySelector(".dropdown-menu").classList.toggle("show");
});

document.addEventListener("click", function() {
    document.querySelector(".dropdown-menu").classList.remove("show");
});
</script>