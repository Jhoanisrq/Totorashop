-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 09-12-2025 a las 03:16:40
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `totorashop`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajuste`
--

CREATE TABLE `ajuste` (
  `id_ajuste` int(11) NOT NULL,
  `id_invntrio` int(11) NOT NULL,
  `id_empldo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `motivo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacen`
--

CREATE TABLE `almacen` (
  `id_almcen` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `id_drccion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `almacen`
--

INSERT INTO `almacen` (`id_almcen`, `nombre`, `telefono`, `id_drccion`) VALUES
(1, 'Almacén Principal', '923567678', 1),
(2, 'Almacén Secundario', '914512233', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_catgria` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_catgria`, `nombre`, `descripcion`) VALUES
(1, 'Electrónicos', 'Dispositivos electrónicos y accesorios'),
(2, 'Ropa y Moda', 'Ropa y accesorios para todas las edades'),
(3, 'Hogar y Cocina', 'Productos domésticos y utensilios'),
(4, 'Belleza y Cuidado Personal', 'Cosmética, higiene y bienestar'),
(5, 'Juguetes y Niños', 'Juguetes y artículos infantiles');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `apellido` varchar(40) NOT NULL,
  `correo` varchar(40) NOT NULL,
  `fecha_ncmnto` date NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nombre`, `apellido`, `correo`, `fecha_ncmnto`, `telefono`, `contraseña`) VALUES
(1, 'Julia', 'Fernández', 'julia.fernandez@gmail.com', '2000-01-10', '987111222', 'julia123'),
(2, 'Marco', 'Salazar', 'marco.salazar@gmail.com', '1999-04-15', '922333444', 'marcoPass'),
(3, 'Lucía', 'Martínez', 'lucia.martinez@gmail.com', '2001-09-20', NULL, 'lucia2025'),
(4, 'Diego', 'Ramos', 'diego.ramos@gmail.com', '1998-12-05', '955789321', 'diego456'),
(5, 'Elena', 'García', 'elena.garcia@gmail.com', '2002-06-30', NULL, 'elena789'),
(6, 'ClienteP', 'R', 'pruebac1@gmail.com', '2005-06-12', '', '$2y$10$1D4w03PwffVeRL.4c4y8P.hAFHQSDVNPBZM4WCIzcctnut4HTDaTe');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_direccion`
--

CREATE TABLE `cliente_direccion` (
  `id_clnte_drccion` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_drccion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id_dtlle_oc` int(11) NOT NULL,
  `id_ordcmpra` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `prcio_untr` decimal(10,2) NOT NULL,
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_compra`
--

INSERT INTO `detalle_compra` (`id_dtlle_oc`, `id_ordcmpra`, `cantidad`, `prcio_untr`, `id_producto`) VALUES
(1, 1, 10, 85.00, 1),
(2, 2, 5, 84.10, 2),
(3, 3, 20, 61.00, 3),
(4, 4, 7, 44.39, 4),
(5, 5, 8, 120.05, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_dtlle_pddo` int(11) NOT NULL,
  `num_pddo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `prcio_untr` decimal(10,2) NOT NULL,
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion`
--

CREATE TABLE `direccion` (
  `id_drccion` int(11) NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `distrito` varchar(50) NOT NULL,
  `nro_calle` varchar(20) NOT NULL,
  `referencia` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direccion`
--

INSERT INTO `direccion` (`id_drccion`, `ciudad`, `distrito`, `nro_calle`, `referencia`) VALUES
(1, 'Lima', 'San Martín de Porres', 'Av. Perú 1450', 'Almacén principal - esquina'),
(2, 'Callao', 'Bellavista', 'Jr. Guardia Chalaca ', 'Almacén secundario'),
(3, 'Lima', 'Los Olivos', 'Av. Palmeras 234', 'Frente al parque'),
(4, 'Lima', 'Comas', 'Mz L Lt 12', 'A media cuadra del paradero'),
(5, 'Lima', 'Independencia', 'Av. Tupac Amaru 5222', 'Cerca a MegaPlaza'),
(6, 'Lima', 'San Juan de Lurigancho', 'Calle Los Geranios 8', '3er piso'),
(7, 'Lima', 'Pueblo Libre', 'Av. Bolívar 430', 'Departamento 201'),
(8, 'Arequipa', 'Yanahuara', 'Av. Ejercito 450', 'Frente al colegio Militar'),
(9, 'Cusco', 'Wanchaq', 'Calle Zaguán del Cie', 'A media cuadra del parque'),
(10, 'Trujillo', 'Vista Alegre', 'Av. Mansiche 980', 'Edificio azul'),
(11, 'Piura', 'Castilla', 'Jr. Comercio 321', 'Cerca del puente Sánchez Cerro'),
(12, 'Chiclayo', 'José Leonardo Ortiz', 'Av. Balta 602', 'Frente al mercado mayorista');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id_empldo` int(11) NOT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `apellido` varchar(80) DEFAULT NULL,
  `fecha_ncmnto` date DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `id_tipo_crg` int(11) NOT NULL,
  `fecha_cntrto` date DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `id_direccion` int(11) NOT NULL,
  `id_almcen` int(11) NOT NULL,
  `contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id_empldo`, `dni`, `nombre`, `apellido`, `fecha_ncmnto`, `telefono`, `id_tipo_crg`, `fecha_cntrto`, `salario`, `id_direccion`, `id_almcen`, `contraseña`) VALUES
(1, '71234561', 'Carlos', 'Pérez', '1990-05-12', '987654321', 1, '2024-01-10', 1800.00, 3, 1, 'carlos123'),
(2, '74891233', 'María', 'López', '1995-08-22', '912345678', 2, '2024-02-15', 1600.00, 4, 1, 'mariaPass'),
(3, '70123456', 'Luis', 'Gómez', '1988-11-03', '955123789', 3, '2024-03-01', 1500.00, 5, 2, 'luis789'),
(4, '75678912', 'Ana', 'Torres', '1992-02-18', '931222456', 1, '2024-04-20', 1700.00, 6, 2, 'ana456'),
(5, '74561289', 'Jorge', 'Ramírez', '1987-07-10', '980112233', 2, '2024-05-05', 2000.00, 7, 1, 'jorgeAdmin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrada`
--

CREATE TABLE `entrada` (
  `id_entrada` int(11) NOT NULL,
  `id_invntrio` int(11) NOT NULL,
  `id_dtlle_oc` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `costo` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entrada`
--

INSERT INTO `entrada` (`id_entrada`, `id_invntrio`, `id_dtlle_oc`, `cantidad`, `costo`) VALUES
(1, 1, 1, 10, 850.00),
(2, 2, 2, 5, 420.50),
(3, 3, 3, 20, 1220.00),
(4, 4, 4, 7, 310.75),
(5, 5, 5, 8, 960.40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id_invntrio` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_almcen` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id_invntrio`, `id_producto`, `id_almcen`, `cantidad`) VALUES
(1, 1, 1, 50),
(2, 2, 2, 30),
(3, 3, 1, 80),
(4, 4, 2, 45),
(5, 5, 1, 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_compra`
--

CREATE TABLE `orden_compra` (
  `id_ordcmpra` int(11) NOT NULL,
  `fecha_ordcmpra` date NOT NULL,
  `estado` varchar(30) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_provdor` int(11) NOT NULL,
  `id_empldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `orden_compra`
--

INSERT INTO `orden_compra` (`id_ordcmpra`, `fecha_ordcmpra`, `estado`, `precio`, `id_provdor`, `id_empldo`) VALUES
(1, '2025-01-10', 'Recibido', 850.00, 1, 1),
(2, '2025-01-12', 'Recibido', 420.50, 2, 1),
(3, '2025-01-15', 'Recibido', 1220.00, 3, 2),
(4, '2025-01-20', 'Recibido', 310.75, 4, 2),
(5, '2025-01-22', 'Recibido', 960.40, 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `num_pddo` int(11) NOT NULL,
  `id_clnte_drccion` int(11) NOT NULL,
  `fecha_pddo` date NOT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `fecha_envio` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `imagen` varchar(200) DEFAULT NULL,
  `id_catgria` int(11) NOT NULL,
  `perecible` tinyint(1) NOT NULL,
  `fch_vncmnto` date DEFAULT NULL,
  `grntia_meses` int(11) DEFAULT NULL,
  `fecha_rgstro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre`, `descripcion`, `imagen`, `id_catgria`, `perecible`, `fch_vncmnto`, `grntia_meses`, `fecha_rgstro`) VALUES
(1, 'Auriculares Bluetooth', 'Auriculares inalámbricos con cancelación de ruido', 'auriculares.jpg', 1, 0, NULL, 12, '2025-12-06'),
(2, 'Polo Casual Hombre', 'Polo de algodón suave, disponible en varias tallas', 'polo_hombre.jpg', 2, 0, NULL, NULL, '2025-12-06'),
(3, 'Set de Sartenes Antiadherentes', 'Juego de 3 sartenes de aluminio reforzado', 'sartenes.jpg', 3, 0, NULL, 6, '2025-12-06'),
(4, 'Crema Hidratante Facial', 'Crema ligera para piel seca y mixta', 'crema_facial.jpg', 4, 1, '2026-05-30', NULL, '2025-12-06'),
(5, 'Bloques de Construcción', 'Set de bloques educativos para niños mayores de 3 años', 'bloques.jpg', 5, 0, NULL, NULL, '2025-12-06'),
(6, 'Auriculares Bluetooth', 'Auriculares inalámbricos con cancelación de ruido', '../assets/img/productos/auriculares.jpg', 1, 0, NULL, 12, '2025-12-06'),
(7, 'Polo Casual Hombre', 'Polo de algodón suave disponible en varias tallas', '../assets/img/productos/polo_hombre.jpg', 2, 0, NULL, NULL, '2025-12-06'),
(8, 'Set de Sartenes Antiadherentes', 'Juego de 3 sartenes de aluminio reforzado', '../assets/img/productos/sartenes.jpg', 3, 0, NULL, 6, '2025-12-06'),
(9, 'Crema Hidratante Facial', 'Crema ligera para piel seca y mixta', '../assets/img/productos/crema_facial.jpg', 4, 1, '2026-05-30', NULL, '2025-12-06'),
(10, 'Bloques de Construcción', 'Set de bloques educativos para niños mayores de 3 años', '../assets/img/productos/bloques.jpg', 5, 0, NULL, NULL, '2025-12-06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_provdor` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `id_drccion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_provdor`, `nombre`, `telefono`, `correo`, `id_drccion`) VALUES
(1, 'Comercial Arequipa S.A.', '987654321', 'contacto@arequipasa.com', 8),
(2, 'Distribuidora Cusqueña', '945112233', 'ventas@cusquena.com', 9),
(3, 'Importaciones NorteTrujillo', '933221145', 'info@nortetrujillo.com', 10),
(4, 'ServiLog Piura', NULL, 'administracion@servilogpiura.pe', 11),
(5, 'Mayoristas Chiclayo', '991234567', 'ventas@mayoristachiclayo.com', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida`
--

CREATE TABLE `salida` (
  `id_salida` int(11) NOT NULL,
  `id_invntrio` int(11) NOT NULL,
  `id_dtlle_pddo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `costo` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cargo`
--

CREATE TABLE `tipo_cargo` (
  `id_tipo_crg` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_cargo`
--

INSERT INTO `tipo_cargo` (`id_tipo_crg`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Almacenero'),
(3, 'Supervisor'),
(4, 'Auxiliar de Almacén'),
(5, 'Control de Calidad');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ajuste`
--
ALTER TABLE `ajuste`
  ADD PRIMARY KEY (`id_ajuste`),
  ADD KEY `id_invntrio` (`id_invntrio`),
  ADD KEY `id_empldo` (`id_empldo`);

--
-- Indices de la tabla `almacen`
--
ALTER TABLE `almacen`
  ADD PRIMARY KEY (`id_almcen`),
  ADD KEY `id_drccion` (`id_drccion`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_catgria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `cliente_direccion`
--
ALTER TABLE `cliente_direccion`
  ADD PRIMARY KEY (`id_clnte_drccion`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_drccion` (`id_drccion`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id_dtlle_oc`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_ordcmpra` (`id_ordcmpra`) USING BTREE;

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id_dtlle_pddo`),
  ADD KEY `num_pddo` (`num_pddo`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD PRIMARY KEY (`id_drccion`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id_empldo`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `id_tipo_crg` (`id_tipo_crg`),
  ADD KEY `id_direccion` (`id_direccion`),
  ADD KEY `id_almcen` (`id_almcen`);

--
-- Indices de la tabla `entrada`
--
ALTER TABLE `entrada`
  ADD PRIMARY KEY (`id_entrada`),
  ADD KEY `id_invntrio` (`id_invntrio`),
  ADD KEY `id_dtlle_oc` (`id_dtlle_oc`) USING BTREE;

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id_invntrio`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_almcen` (`id_almcen`);

--
-- Indices de la tabla `orden_compra`
--
ALTER TABLE `orden_compra`
  ADD PRIMARY KEY (`id_ordcmpra`),
  ADD KEY `id_provdor` (`id_provdor`) USING BTREE,
  ADD KEY `id_empldo` (`id_empldo`) USING BTREE;

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`num_pddo`),
  ADD KEY `id_clnte_drccion` (`id_clnte_drccion`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_catgria` (`id_catgria`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_provdor`),
  ADD KEY `id_drccion` (`id_drccion`);

--
-- Indices de la tabla `salida`
--
ALTER TABLE `salida`
  ADD PRIMARY KEY (`id_salida`),
  ADD KEY `id_invntrio` (`id_invntrio`),
  ADD KEY `id_dtlle_pddo` (`id_dtlle_pddo`);

--
-- Indices de la tabla `tipo_cargo`
--
ALTER TABLE `tipo_cargo`
  ADD PRIMARY KEY (`id_tipo_crg`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ajuste`
--
ALTER TABLE `ajuste`
  MODIFY `id_ajuste` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `almacen`
--
ALTER TABLE `almacen`
  MODIFY `id_almcen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_catgria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `cliente_direccion`
--
ALTER TABLE `cliente_direccion`
  MODIFY `id_clnte_drccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id_dtlle_oc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id_dtlle_pddo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `direccion`
--
ALTER TABLE `direccion`
  MODIFY `id_drccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id_empldo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `entrada`
--
ALTER TABLE `entrada`
  MODIFY `id_entrada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id_invntrio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `orden_compra`
--
ALTER TABLE `orden_compra`
  MODIFY `id_ordcmpra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `num_pddo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_provdor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `salida`
--
ALTER TABLE `salida`
  MODIFY `id_salida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_cargo`
--
ALTER TABLE `tipo_cargo`
  MODIFY `id_tipo_crg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ajuste`
--
ALTER TABLE `ajuste`
  ADD CONSTRAINT `ajuste_ibfk_1` FOREIGN KEY (`id_invntrio`) REFERENCES `inventario` (`id_invntrio`),
  ADD CONSTRAINT `ajuste_ibfk_2` FOREIGN KEY (`id_empldo`) REFERENCES `empleado` (`id_empldo`);

--
-- Filtros para la tabla `almacen`
--
ALTER TABLE `almacen`
  ADD CONSTRAINT `almacen_ibfk_1` FOREIGN KEY (`id_drccion`) REFERENCES `direccion` (`id_drccion`);

--
-- Filtros para la tabla `cliente_direccion`
--
ALTER TABLE `cliente_direccion`
  ADD CONSTRAINT `cliente_direccion_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `cliente_direccion_ibfk_2` FOREIGN KEY (`id_drccion`) REFERENCES `direccion` (`id_drccion`);

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`id_ordcmpra`) REFERENCES `orden_compra` (`id_ordcmpra`),
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`num_pddo`) REFERENCES `pedido` (`num_pddo`),
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_tipo_crg`) REFERENCES `tipo_cargo` (`id_tipo_crg`),
  ADD CONSTRAINT `empleado_ibfk_2` FOREIGN KEY (`id_direccion`) REFERENCES `direccion` (`id_drccion`),
  ADD CONSTRAINT `empleado_ibfk_3` FOREIGN KEY (`id_almcen`) REFERENCES `almacen` (`id_almcen`);

--
-- Filtros para la tabla `entrada`
--
ALTER TABLE `entrada`
  ADD CONSTRAINT `entrada_ibfk_1` FOREIGN KEY (`id_invntrio`) REFERENCES `inventario` (`id_invntrio`),
  ADD CONSTRAINT `entrada_ibfk_2` FOREIGN KEY (`id_dtlle_oc`) REFERENCES `detalle_compra` (`id_dtlle_oc`);

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`),
  ADD CONSTRAINT `inventario_ibfk_2` FOREIGN KEY (`id_almcen`) REFERENCES `almacen` (`id_almcen`);

--
-- Filtros para la tabla `orden_compra`
--
ALTER TABLE `orden_compra`
  ADD CONSTRAINT `orden_compra_ibfk_1` FOREIGN KEY (`id_provdor`) REFERENCES `proveedor` (`id_provdor`),
  ADD CONSTRAINT `orden_compra_ibfk_2` FOREIGN KEY (`id_empldo`) REFERENCES `empleado` (`id_empldo`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_clnte_drccion`) REFERENCES `cliente_direccion` (`id_clnte_drccion`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_catgria`) REFERENCES `categoria` (`id_catgria`);

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`id_drccion`) REFERENCES `direccion` (`id_drccion`);

--
-- Filtros para la tabla `salida`
--
ALTER TABLE `salida`
  ADD CONSTRAINT `salida_ibfk_1` FOREIGN KEY (`id_invntrio`) REFERENCES `inventario` (`id_invntrio`),
  ADD CONSTRAINT `salida_ibfk_2` FOREIGN KEY (`id_dtlle_pddo`) REFERENCES `detalle_pedido` (`id_dtlle_pddo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
