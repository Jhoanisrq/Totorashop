-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 11-12-2025 a las 13:02:50
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

--
-- Volcado de datos para la tabla `ajuste`
--

INSERT INTO `ajuste` (`id_ajuste`, `id_invntrio`, `id_empldo`, `cantidad`, `motivo`) VALUES
(1, 1, 2, 6, 'accidente en etrega terrible'),
(2, 4, 2, -5, 'incendio en una parte del almacen'),
(3, 6, 2, -6, 'cliente debolucion '),
(4, 7, 2, -3, 'se entrego en mal estado faltaban pieza'),
(5, 1, 2, 5, 'hubo debolucion en fisico');

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
(2, 'Almacén Secundario', '914512233', 2),
(3, 'Almacén Miraflores', '923111622', 22),
(4, 'Almacén San Isidro', '923433441', 23),
(5, 'Almacén Surco', '923545596', 24);

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
(6, 'ClienteP', 'R', 'pruebac1@gmail.com', '2005-06-12', '', '$2y$10$1D4w03PwffVeRL.4c4y8P.hAFHQSDVNPBZM4WCIzcctnut4HTDaTe'),
(7, 'Jhoa', 'Roque', 'jhoanisrq@gmail.com', '2006-12-18', '', '$2y$10$Jq0sW.Ut/wmNTmovaEv.VereaLosoH.fggh1HK4d9cS.Pc2waPoFa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_direccion`
--

CREATE TABLE `cliente_direccion` (
  `id_clnte_drccion` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_drccion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente_direccion`
--

INSERT INTO `cliente_direccion` (`id_clnte_drccion`, `id_cliente`, `id_drccion`) VALUES
(1, 6, 13),
(2, 6, 14),
(3, 7, 19),
(4, 7, 20),
(5, 7, 21);

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
(1, 1, 36, 56.30, 4),
(2, 1, 46, 94.21, 5),
(3, 2, 10, 35.68, 3),
(4, 3, 45, 76.34, 5),
(5, 3, 43, 33.20, 1),
(6, 3, 54, 66.40, 7),
(7, 4, 60, 66.30, 8),
(8, 4, 50, 46.30, 4),
(9, 5, 34, 37.45, 9),
(10, 6, 122, 30.00, 1);

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

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id_dtlle_pddo`, `num_pddo`, `cantidad`, `prcio_untr`, `id_producto`) VALUES
(1, 1, 1, 37.45, 9),
(2, 1, 2, 56.30, 4),
(3, 1, 1, 87.00, 2),
(4, 2, 1, 56.30, 4),
(5, 2, 1, 35.68, 3),
(6, 2, 2, 33.20, 1),
(7, 3, 2, 66.40, 7),
(8, 3, 1, 66.30, 8),
(9, 4, 2, 87.00, 2),
(10, 5, 5, 66.30, 8);

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
(8, 'Arequipa', 'Yanahuara', 'Av. Ejercito 450', 'Frente al colegio Militar'),
(9, 'Cusco', 'Wanchaq', 'Calle Zaguán del Cie', 'A media cuadra del parque'),
(10, 'Trujillo', 'Vista Alegre', 'Av. Mansiche 980', 'Edificio azul'),
(11, 'Piura', 'Castilla', 'Jr. Comercio 321', 'Cerca del puente Sánchez Cerro'),
(12, 'Chiclayo', 'José Leonardo Ortiz', 'Av. Balta 602', 'Frente al mercado mayorista'),
(13, 'arequipa', 'mariano melgar', '305', 'casa de color gris de 3 pisos'),
(14, 'Cusco', 'Los Olivos', '341', 'casa naranja'),
(15, 'Lima', 'miraflores', 'calle sucre', ''),
(16, 'Lima', 'miraflores', 'calle sucre', 'A media cuadra del parque'),
(17, 'Cusco', 'Los Olivos', '346', ''),
(18, 'arequipa', 'mariano melgar', '1004', 'casa gris de 3 pisos'),
(19, 'arequipa', 'miraflores', '346', 'casa naranja a la esquina del parque'),
(20, 'Lima', 'Los Olivos', '346', ''),
(21, 'Cusco', 'Wanchaq', '3477', 'casa naranja de un piso esquinar'),
(22, 'Lima', 'Miraflores', 'Av. Larco 123', 'Frente al parque'),
(23, 'cusco', 'San Isidro', 'Calle Los Robles 456', 'Edificio azul'),
(24, 'Arequipa', 'Surco', 'Av. Primavera 789', 'Al lado de la iglesia');

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
(1, '71234561', 'Carlos', 'Pérez', '1990-05-12', '987654321', 1, '2024-01-10', 1800.00, 3, 1, '$2y$10$1VI5e2cOTi8BhoY30bXJXueUrfvgQCGVR7vdee2tR5uPTytV/7Scm'),
(2, '74891233', 'María', 'López', '1995-08-22', '912345678', 2, '2024-02-15', 1600.00, 4, 1, '$2y$10$yqhYumAzFKRkWfRXgeJNF.Zr/5rAGC9VhRZUeOOOwmp/BnUNWFoG2'),
(4, '71348561', 'Juan', 'Ernandez', '2003-05-04', '945112233', 3, '2025-07-05', 3400.00, 16, 2, '$2y$10$tyqzHycBgnugXAaTL93AAulfcs2qnudLYppkg/KlS.GGD05Kv0Lr.'),
(5, '60866497', 'Rodrigo', 'Iglesias', '1999-05-05', '934566662', 4, '2025-04-25', 2600.00, 17, 2, '$2y$10$wXoJ/Fmul6nf6DCchufH3u5b4RJQ023aJzgsEvY9wws8jQgdgZ3QK'),
(6, '71241616', 'Jhoana', 'Roque', '2006-12-18', '', 1, '2025-12-11', 6000.00, 18, 2, '$2y$10$rQOaN3TPIgNB22oEow5A3.GrzjPI4KINcbYSVt7hhNe/W68k/ZhPK');

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
(1, 4, 1, 36, 2026.80),
(2, 5, 2, 46, 4333.66),
(3, 3, 3, 10, 356.80),
(4, 5, 4, 45, 3435.30),
(5, 1, 5, 43, 1427.60),
(6, 6, 6, 54, 3585.60),
(7, 7, 9, 34, 1273.30),
(8, 9, 7, 60, 3978.00),
(9, 4, 8, 50, 2315.00);

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
(1, 1, 1, 82),
(2, 2, 2, 42),
(3, 3, 1, 54),
(4, 4, 1, 78),
(5, 5, 1, 91),
(6, 7, 1, 48),
(7, 9, 1, 30),
(8, 10, 2, 60),
(9, 8, 1, 60);

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
(1, '2025-12-11', 'Recibido', 6360.46, 3, 2),
(2, '2025-12-11', 'Recibido', 356.80, 2, 2),
(3, '2025-12-11', 'Recibido', 8448.50, 4, 2),
(4, '2025-12-11', 'Recibido', 6293.00, 5, 2),
(5, '2025-12-11', 'Recibido', 1273.30, 2, 2),
(6, '2025-12-11', 'Pendiente', 3660.00, 2, 2);

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

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`num_pddo`, `id_clnte_drccion`, `fecha_pddo`, `estado`, `costo`, `fecha_envio`) VALUES
(1, 1, '2025-12-11', 'Enviado', 237.05, '2025-12-19'),
(2, 3, '2025-12-11', 'Enviado', 158.38, '2025-12-18'),
(3, 4, '2025-12-11', 'Procesando', 199.10, '2025-12-17'),
(4, 3, '2025-12-11', 'Enviado', 174.00, '2025-12-16'),
(5, 5, '2025-12-11', 'Procesando', 331.50, '2025-12-19');

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
  `fecha_rgstro` date NOT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre`, `descripcion`, `imagen`, `id_catgria`, `perecible`, `fch_vncmnto`, `grntia_meses`, `fecha_rgstro`, `precio`) VALUES
(1, 'Audífonos Bluetooth TWS', 'Audífonos inalámbricos con estuche de carga, cancelación de ruido básica y micrófono integrado.', '../assets/img/productos/1765450316_51e7TR4wzuL._AC_SL1002_auriculares.jpg', 1, 0, '0000-00-00', 6, '2025-12-11', 35.40),
(2, 'Smartwatch Deportivo', 'Reloj inteligente con monitoreo cardiaco, contador de pasos y notificaciones móviles.', '../assets/img/productos/1765450377_Smartwatches-para-deporte.jpg', 1, 0, '0000-00-00', 12, '2025-12-11', 87.00),
(3, 'Polo Unisex Algodón', 'Polo cómodo de algodón suave, disponible en varias tallas y colores.', '../assets/img/productos/1765450456_polosdfw.jfif', 2, 0, '0000-00-00', NULL, '2025-12-11', 45.30),
(4, 'Casaca Rompeviento Unisex', 'Casaca ligera resistente al viento y al agua, ideal para clima frío o lluvioso.', '../assets/img/productos/1765450596_casacarrkbng.jpg', 2, 0, NULL, 1, '2025-12-11', 0.00),
(5, 'Juego de Ollas Antiadherentes 7 piezas', 'Juego completo de ollas con recubrimiento antiadherente y tapas de vidrio templado.', '../assets/img/productos/1765450738_juego_ollasfgwsf.jpg', 3, 0, NULL, NULL, '2025-12-11', 0.00),
(6, 'Hervidor Eléctrico 1.7L', 'Hervidor eléctrico con apagado automático y base giratoria 360°.', NULL, 1, 0, NULL, 4, '2025-12-11', 0.00),
(7, 'Hervidor Eléctrico 1.7L', 'Hervidor eléctrico con apagado automático y base giratoria 360°.', '../assets/img/productos/1765451211_ErvidorElectricouokk.jfif', 3, 0, NULL, 4, '2025-12-11', 0.00),
(8, 'Crema Hidratante Facial Aloe Vera', 'Crema hidratante con extracto natural de aloe vera, ideal para piel seca y sensible.', '../assets/img/productos/1765451451_cremaalovermadfg.jpg', 4, 0, NULL, 5, '2025-12-11', 0.00),
(9, 'Rompecabezas Infantil 100 piezas', 'Rompecabezas colorido con ilustraciones educativas para niños mayores de 6 años.', '../assets/img/productos/1765451581_rompecabezasXD.jpg', 5, 0, NULL, NULL, '2025-12-11', 0.00),
(10, 'Auto de Juguete RC (Control Remoto)', 'Auto a control remoto con batería recargable, luces LED y diseño resistente.', '../assets/img/productos/1765451843_autojuegueteControlremoto.jpg', 5, 0, '0000-00-00', 2, '2025-12-11', 87.40);

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

--
-- Volcado de datos para la tabla `salida`
--

INSERT INTO `salida` (`id_salida`, `id_invntrio`, `id_dtlle_pddo`, `cantidad`, `costo`) VALUES
(1, 4, 4, 1, 56.30),
(2, 3, 5, 1, 35.68),
(3, 1, 6, 2, 66.40),
(4, 7, 1, 1, 37.45),
(5, 4, 2, 2, 112.60),
(6, 2, 3, 1, 87.00),
(7, 2, 9, 2, 174.00);

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
  MODIFY `id_ajuste` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `almacen`
--
ALTER TABLE `almacen`
  MODIFY `id_almcen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_catgria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cliente_direccion`
--
ALTER TABLE `cliente_direccion`
  MODIFY `id_clnte_drccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id_dtlle_oc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id_dtlle_pddo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `direccion`
--
ALTER TABLE `direccion`
  MODIFY `id_drccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id_empldo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `entrada`
--
ALTER TABLE `entrada`
  MODIFY `id_entrada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id_invntrio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `orden_compra`
--
ALTER TABLE `orden_compra`
  MODIFY `id_ordcmpra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `num_pddo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id_salida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
