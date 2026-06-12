-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 12, 2026 at 12:56 AM
-- Server version: 12.3.2-MariaDB
-- PHP Version: 8.5.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Store`
--

-- --------------------------------------------------------

--
-- Table structure for table `Atributos`
--

CREATE TABLE `Atributos` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Atributos`
--

INSERT INTO `Atributos` (`Id`, `Nombre`) VALUES
(1, 'RAM'),
(2, 'Hecho en'),
(3, 'Género'),
(4, 'Tipo de cuello'),
(5, 'Talla'),
(6, 'Largo de mangas'),
(7, 'Color'),
(8, 'Año de lanzamiento'),
(9, 'Tamaño de la pantalla'),
(10, 'Cámara principal'),
(11, 'Modelo'),
(12, 'Procesador'),
(13, 'Generación'),
(14, 'Estilo'),
(15, 'Fit prenda inferior'),
(16, 'Material de vestuario'),
(17, 'Tipo'),
(18, 'Fit prenda superior'),
(19, 'Condicion del producto'),
(20, 'País de origen'),
(21, 'Diseño'),
(22, 'Estilo del sosten');

-- --------------------------------------------------------

--
-- Table structure for table `AtributoValores`
--

CREATE TABLE `AtributoValores` (
  `Id` int(10) UNSIGNED NOT NULL,
  `AtributoId` int(10) UNSIGNED DEFAULT NULL,
  `Valor` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `AtributoValores`
--

INSERT INTO `AtributoValores` (`Id`, `AtributoId`, `Valor`) VALUES
(1, 1, '16 GB'),
(2, 2, 'China'),
(3, 3, 'Mujer'),
(4, 4, 'Button down'),
(5, 5, 'S'),
(6, 6, 'Manga larga'),
(7, 5, 'M'),
(8, 7, 'Crema'),
(9, 8, '2026'),
(10, 9, '6.7'),
(11, 10, '200 MP'),
(12, 11, 'Note 15 Pro'),
(13, 12, 'Mediatek helio'),
(14, 13, '4G LTE'),
(15, 14, 'Deportivo'),
(16, 15, 'Regular'),
(17, 16, 'Poliéster'),
(18, 17, 'Buzo conjunto'),
(19, 11, 'BC.M.DVD.W26'),
(20, 3, 'Hombre'),
(21, 7, 'Negro'),
(22, 5, 'L'),
(23, 5, 'XL'),
(24, 16, 'Algodón'),
(25, 18, 'Regular fit'),
(26, 6, 'Manga corta'),
(27, 17, 'Polos deportivos'),
(28, 19, 'Nuevo'),
(29, 20, 'Peru'),
(30, 11, 'Bikini'),
(31, 21, 'Liso'),
(32, 17, 'Trajes de baño'),
(33, 2, 'Perú'),
(34, 11, 'Una Pieza - Enterizo'),
(35, 22, 'Con copa');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `CarritoItems`
--

CREATE TABLE `CarritoItems` (
  `Id` int(10) UNSIGNED NOT NULL,
  `CarritoId` int(10) UNSIGNED DEFAULT NULL,
  `VarianteId` int(10) UNSIGNED DEFAULT NULL,
  `Cantidad` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Carritos`
--

CREATE TABLE `Carritos` (
  `Id` int(10) UNSIGNED NOT NULL,
  `UsuarioId` int(10) UNSIGNED DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Categorias`
--

CREATE TABLE `Categorias` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Nombre` varchar(100) DEFAULT NULL,
  `Slug` varchar(150) DEFAULT NULL,
  `ParentId` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Categorias`
--

INSERT INTO `Categorias` (`Id`, `Nombre`, `Slug`, `ParentId`) VALUES
(1, 'Tecnología', 'tecnologia', NULL),
(3, 'Celulares', 'celulares-2', 1),
(4, 'Laptops', 'laptops', 1),
(5, 'Ropa', 'ropa', NULL),
(6, 'Polos', 'polos', 5),
(7, 'Casacas', 'casacas', 5),
(8, 'Maquillaje', 'maquillaje', NULL),
(9, 'Abrigos', 'abrigos', 5),
(10, 'Maquillaje de labios', 'maquillaje-de-labios', 8),
(11, 'Maquillaje para cejas', 'maquillaje-para-cejas', 8),
(12, 'Ropa de baño y bikinis', 'ropa-de-bano-y-bikinis', 5),
(13, 'Ropa Deportiva Hombre', 'ropa-deportiva-hombre', 5);

-- --------------------------------------------------------

--
-- Table structure for table `Direcciones`
--

CREATE TABLE `Direcciones` (
  `Id` int(10) UNSIGNED NOT NULL,
  `UsuarioId` int(10) UNSIGNED DEFAULT NULL,
  `Pais` varchar(40) DEFAULT NULL,
  `Region` varchar(50) DEFAULT NULL,
  `Ciudad` varchar(50) DEFAULT NULL,
  `Direccion` varchar(120) DEFAULT NULL,
  `Referencia` varchar(120) DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Direcciones`
--

INSERT INTO `Direcciones` (`Id`, `UsuarioId`, `Pais`, `Region`, `Ciudad`, `Direccion`, `Referencia`, `CreatedAt`) VALUES
(1, 7, 'Peru', 'Lima', 'Miraflones', 'calle 1', NULL, '2026-04-19 23:56:36'),
(2, 7, 'Peru', 'Lima', 'Miraflones', 'calle 1', NULL, '2026-05-16 17:25:36');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Inventario`
--

CREATE TABLE `Inventario` (
  `Id` int(10) UNSIGNED NOT NULL,
  `VarianteId` int(10) UNSIGNED DEFAULT NULL,
  `Stock` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Inventario`
--

INSERT INTO `Inventario` (`Id`, `VarianteId`, `Stock`) VALUES
(1, 1, 11),
(2, 2, 11),
(3, 3, 993),
(4, 4, 30),
(5, 5, 93),
(6, 6, 8),
(7, 7, 80),
(8, 8, 450),
(9, 9, 34),
(10, 10, 15);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Marcas`
--

CREATE TABLE `Marcas` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Nombre` varchar(100) DEFAULT NULL,
  `Slug` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Marcas`
--

INSERT INTO `Marcas` (`Id`, `Nombre`, `Slug`) VALUES
(1, 'samsung', 'samsung'),
(2, 'IPhone', 'iphone'),
(3, 'Levis', 'levis'),
(4, 'Lancome', 'lancome'),
(5, 'Benefit', 'benefit'),
(6, 'Xiaomi', 'xiaomi'),
(7, 'Diadora', 'diadora'),
(8, 'Puma', 'puma'),
(9, 'Solary', 'solary');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000000_create_users_table', 2),
(3, '0001_01_01_000002_create_jobs_table', 2),
(4, '2026_04_16_000000_add_dni_ruc_to_usuarios_table', 3),
(5, '2026_04_29_000001_normalize_roles_to_admin_and_usuario', 3),
(6, '2026_04_30_000005_normalize_pedidos_estado_to_varchar', 4),
(7, '2026_04_30_000000_create_carritos_table', 5),
(8, '2026_04_30_000001_create_carrito_items_table', 5),
(9, '2026_04_30_000002_create_pedidos_table', 5),
(10, '2026_04_30_000003_create_pedido_detalles_table', 5),
(11, '2026_04_30_000004_create_movimientos_stock_table', 5),
(12, '2026_05_16_000000_create_pending_user_verifications_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `MovimientosStock`
--

CREATE TABLE `MovimientosStock` (
  `Id` int(10) UNSIGNED NOT NULL,
  `VarianteId` int(10) UNSIGNED DEFAULT NULL,
  `Tipo` enum('Entrada','Salida') DEFAULT NULL,
  `Cantidad` smallint(6) DEFAULT NULL,
  `Motivo` varchar(100) DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `MovimientosStock`
--

INSERT INTO `MovimientosStock` (`Id`, `VarianteId`, `Tipo`, `Cantidad`, `Motivo`, `CreatedAt`) VALUES
(1, 1, 'Salida', 1, 'Venta', '2026-05-16 17:25:36'),
(2, 4, 'Salida', 3, 'Venta', '2026-05-16 17:35:45'),
(3, 3, 'Salida', 2, 'Venta', '2026-05-16 17:35:45'),
(4, 5, 'Salida', 2, 'Venta', '2026-05-17 01:11:15'),
(5, 3, 'Salida', 1, 'Venta', '2026-05-17 01:11:15'),
(6, 3, 'Salida', 2, 'Venta', '2026-05-17 01:22:07'),
(7, 2, 'Salida', 1, 'Venta', '2026-05-17 01:25:19'),
(8, 5, 'Salida', 2, 'Venta', '2026-05-17 01:29:03'),
(9, 3, 'Salida', 1, 'Venta', '2026-05-17 01:29:03'),
(10, 4, 'Salida', 2, 'Venta', '2026-05-17 01:36:27'),
(11, 5, 'Salida', 2, 'Venta', '2026-05-17 01:36:27'),
(12, 4, 'Entrada', 27, 'Ajuste admin desde alertas', '2026-06-10 05:36:09');

-- --------------------------------------------------------

--
-- Table structure for table `Pagos`
--

CREATE TABLE `Pagos` (
  `Id` int(10) UNSIGNED NOT NULL,
  `PedidoId` int(10) UNSIGNED DEFAULT NULL,
  `Metodo` varchar(50) DEFAULT NULL,
  `Monto` decimal(10,2) DEFAULT NULL,
  `Estado` enum('Pendiente','Aprobado','Rechazado') DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PasswordResets`
--

CREATE TABLE `PasswordResets` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Correo` varchar(120) DEFAULT NULL,
  `Token` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PedidoDetalles`
--

CREATE TABLE `PedidoDetalles` (
  `Id` int(10) UNSIGNED NOT NULL,
  `PedidoId` int(10) UNSIGNED DEFAULT NULL,
  `VarianteId` int(10) UNSIGNED DEFAULT NULL,
  `Cantidad` smallint(6) DEFAULT NULL,
  `Precio` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `PedidoDetalles`
--

INSERT INTO `PedidoDetalles` (`Id`, `PedidoId`, `VarianteId`, `Cantidad`, `Precio`) VALUES
(1, 1, 1, 1, 1099.00),
(2, 2, 4, 3, 220.00),
(3, 2, 3, 2, 149.90),
(6, 4, 3, 2, 149.90),
(7, 5, 2, 1, 4999.00),
(8, 6, 5, 2, 55.92),
(9, 6, 3, 1, 149.90),
(10, 7, 4, 2, 220.00),
(11, 7, 5, 2, 55.92);

-- --------------------------------------------------------

--
-- Table structure for table `Pedidos`
--

CREATE TABLE `Pedidos` (
  `Id` int(10) UNSIGNED NOT NULL,
  `UsuarioId` int(10) UNSIGNED DEFAULT NULL,
  `DireccionId` int(10) UNSIGNED DEFAULT NULL,
  `Total` decimal(10,2) DEFAULT NULL,
  `Estado` varchar(50) DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Pedidos`
--

INSERT INTO `Pedidos` (`Id`, `UsuarioId`, `DireccionId`, `Total`, `Estado`, `CreatedAt`) VALUES
(1, 7, 2, 1099.00, 'enviado', '2026-05-16 22:25:36'),
(2, 7, 2, 959.80, 'pagado', '2026-05-16 22:35:45'),
(4, 7, 2, 299.80, 'pagado', '2026-05-17 01:22:07'),
(5, 7, 2, 4999.00, 'pagado', '2026-05-17 01:25:19'),
(6, 7, 1, 261.74, 'pagado', '2026-05-17 01:29:03'),
(7, 7, 1, 551.84, 'pagado', '2026-05-17 01:36:27');

-- --------------------------------------------------------

--
-- Table structure for table `PendingUserVerifications`
--

CREATE TABLE `PendingUserVerifications` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Email` varchar(120) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `OtpCode` varchar(255) NOT NULL,
  `ExpiresAt` timestamp NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `PendingUserVerifications`
--

INSERT INTO `PendingUserVerifications` (`Id`, `Email`, `Password`, `OtpCode`, `ExpiresAt`, `CreatedAt`) VALUES
(6, '4puest4sxz0r@gmail.com', '$2y$12$4KhzqKuI2W0Wqig49hgjpelGs1mTj6gDkUnvk40qRnX0TW9YA3GZi', '$2y$12$BVYZgjPZcSlkciei9BkYte4occ0.lSi0RqOupa.674JrTlF/2bY4e', '2026-06-12 00:07:18', '2026-06-11 23:57:18');

-- --------------------------------------------------------

--
-- Table structure for table `ProductoImagenes`
--

CREATE TABLE `ProductoImagenes` (
  `Id` int(10) UNSIGNED NOT NULL,
  `ProductoId` int(10) UNSIGNED DEFAULT NULL,
  `Url` varchar(255) DEFAULT NULL,
  `Orden` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ProductoImagenes`
--

INSERT INTO `ProductoImagenes` (`Id`, `ProductoId`, `Url`, `Orden`) VALUES
(1, 1, 'productos/717HjouFSmZoVbCzVzQWGMamZpQWfa3YuRBgoM6l.webp', 1),
(2, 1, 'productos/sbkUnimDtRFZYilwqYMPOIX540LTkUNNAMByejYb.jpg', 2),
(3, 2, 'productos/faUda6KRrjdDzGqrg5aA184jecDbgH0UqUDb9lel.jpg', 1),
(4, 3, 'productos/eB353akZEgkWzDvoflfriE7HJlAmPHqAdDF1hzJC.webp', 1),
(5, 3, 'productos/UctDFZqWlLiZzDHfKQVT7VPia1CSvRzBUSZO4wlH.webp', 2),
(6, 3, 'productos/ZD5skEdAlbNYsY2XV2hcGjgpB6BdJztcUywnNPjp.webp', 3),
(7, 3, 'productos/LqoSxawNIrx6sCxQRni2YblD9XJUNkOnmYca1sbf.webp', 4),
(8, 4, 'productos/XpyWv8S51n5AHgNrLAYW9SNGEQUpN46hjrhfW6Pm.webp', 1),
(9, 5, 'productos/0iU3RZLqWVoGRyBBa6c5y4Ze9iJUuvrtwFU7arqF.webp', 1),
(10, 5, 'productos/lznANTRauMCNVV38WwE9qMMExMCdHUu0cilSzB3q.webp', 2),
(11, 5, 'productos/jCyYK01UhrxhL861ZaGvDx52bjz9IW67y2Uexspl.webp', 3),
(12, 5, 'productos/b4e8y6nPDN1vmiJoBxppVWx8nKMU7bEqojcNuYAm.webp', 4),
(13, 5, 'productos/PZx2LsJySWRuVM0cWTiLan4NCMwDwY5sH4Tn9hCJ.webp', 5),
(14, 5, 'productos/1Z15jjMaKhjv5GvLmKK3KSPbEcLnNSWzpQVwbRMo.webp', 6),
(15, 6, 'productos/9KLNQ9ykIPO7Is20Zn0rDdnFhX2MaLaxNIcKFNKB.webp', 1),
(16, 6, 'productos/nMTX3wFFEcFBCqcDQMl5yK2QteKP8KY0qeOO4N1e.webp', 2),
(17, 6, 'productos/GXPUCsfRvVwb3vu8JHOv9lRzgq2G0UJwRn1EvBiM.webp', 3),
(18, 6, 'productos/Pf74i43caDafDzoN2J8BgZ8naw0nxxhQCLtTFh9O.webp', 4),
(19, 6, 'productos/SiJfp05XF7194KjFyl8mlf6DpSnlbQ3Pyn556Itb.webp', 5),
(20, 6, 'productos/LXsb0xR3hrQ6dLhv0nLGleGIxJduo6mkSr2GBTFI.webp', 6),
(21, 7, 'productos/nOpIdZsHC7Tke5cRDjLJVVCB96siY3IQVAk3i9op.webp', 1),
(22, 7, 'productos/OfwiZOMwRZyIIpI9V4LEQ45wKBiEHV1ca4Iqv41b.webp', 2),
(23, 7, 'productos/4z0nZ8S7Jqg3LTmpTNsBCKXovr4MxHBLdaJeECTL.webp', 3),
(24, 7, 'productos/djQMhoRqqzX62hc4dKuCkdngMsMWyTmh5OSgOyx3.webp', 4),
(25, 7, 'productos/E7LfLbENhVnO5kedQCL7WLqda5ZPjA3hejDSG906.webp', 5),
(26, 7, 'productos/wQUMUC8RmOWUTnseIv97sNDaDV9W85OrCMnOoEKT.webp', 6),
(27, 8, 'productos/JVx4iC2tTD3BitCEPFp1DB1WKg2whLfhwRjpZk6T.webp', 1),
(28, 8, 'productos/7lxdxRDYXYoIsQWLjouqG3cpJR9z15mfa97GMesJ.webp', 2),
(29, 8, 'productos/bO9PM0p14U8apIeqpNiGEtgXgIftxXioyxLvtHkk.webp', 3),
(30, 8, 'productos/tRvXuOTHPG9pX3yq4No7UdnEa8ZQS8Kodxiz0Coy.webp', 4),
(31, 9, 'productos/CUt0iAM3bh1ioaj1awBSgPD9TNOKahVwSTOpo99M.webp', 1),
(32, 9, 'productos/X0jEzsdrBE6RJphiV20fLS3uIBlPd0haZRB95s6l.jpg', 2),
(33, 10, 'productos/kJFNZCx5OogP847fm0eKhninsy41mU7T3PJCBRM3.webp', 1),
(34, 10, 'productos/nSKoV0CrbnyhaRWGuTrGFec3AwvhKcpivMB5TvRV.webp', 2);

-- --------------------------------------------------------

--
-- Table structure for table `Productos`
--

CREATE TABLE `Productos` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Nombre` varchar(120) DEFAULT NULL,
  `Slug` varchar(150) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `CategoriaId` int(10) UNSIGNED DEFAULT NULL,
  `MarcaId` int(10) UNSIGNED DEFAULT NULL,
  `Estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Productos`
--

INSERT INTO `Productos` (`Id`, `Nombre`, `Slug`, `Descripcion`, `CategoriaId`, `MarcaId`, `Estado`, `CreatedAt`) VALUES
(1, 'Iphone 15 PRO max', 'iphone-15-pro-max', 'que  hermoso productos :v', 3, 2, 'Activo', '2026-04-15 21:14:50'),
(2, 'iphone17', 'iphone17', 'que good', 3, 2, 'Activo', '2026-04-16 02:10:13'),
(3, 'Abrigo para mujer', 'abrigo-para-mujer', 'Abrigo Mujer University Club', 9, 3, 'Activo', '2026-05-03 00:09:53'),
(4, 'Set Nooki Favoritos Coreanos', 'set-nooki-favoritos-coreanos', 'Ficha del producto:\r\n\r\n    Incluye: BELIF Tónico Hidratante Moisturizing Bomb 20 ml BELIF Mascarilla Multivitamínica Super Knights 5 ml AMPLE:N Ampolla Colágeno Shot Mini 5 ml AMPLE:N Ampolla Ceramide Shot Mini 10 ml GAON Sérum Tonificante con Semillas 50 ml GAON Espuma Limpiadora con Granos 100 ml AMPLE:N Mascarilla Ceramide Shot 25 ml FMGT Tinte Labial Water Fit (01 ¿ Rosa Pink) 5 g\r\n    Modelo: SETNOOKI012025\r\n    País de origen: Corea\r\n    República Popular Democrática de:\r\n    Condicion del producto: Nuevo\r\n    Marca: FMGT\r\n    Características: 1. GAON Espuma Limpiadora con Granos (100 ml) Limpieza profunda y suave con extractos naturales y granos antioxidantes. Elimina impurezas sin resecar y deja la piel fresca y equilibrada. 2. BELIF Tónico Hidratante Moisturizing Bomb (20 ml) Tónico ligero que hidrata\r\n    calma y prepara la piel para los siguientes pasos. Ideal para todo tipo de piel:\r\n    incluso las sensibles. 3. GAON Sérum Tonificante con Semillas (50 ml) Sérum vegano con antioxidantes que revitaliza y mejora la textura de la piel. Aporta firmeza y luminosidad natural. 4. AMPLE:N Ampolla Colágeno Shot Mini (5 ml) Fórmula concentrada que mejora la elasticidad y firmeza de la piel. Ayuda a suavizar líneas de expresión. 5. AMPLE:N Ampolla Ceramida Shot Mini (10 ml) Ampolla intensiva que refuerza la barrera cutánea y mantiene la hidratación durante todo el día. Perfecta para piel seca o sensible. 6. BELIF Mascarilla Multivitamínica Super Knights (5 ml) Mascarilla revitalizante con vitaminas que aportan energía y brillo. Ideal para recuperar la piel apagada o cansada. 7. AMPLE:N Mascarilla Ceramida Shot (25 ml) Mascarilla facial hidratante con ceramidas que restauran la suavidad y elasticidad de la piel. Efecto calmante inmediato. 8. FMGT Tinte Labial Water Fit (01 ¿ Rosa Pink) 5 g Tinte ligero y de larga duración con acabado natural. Aporta color y frescura a los labios sin resecarlos.:\r\n    Formato belleza: Set\r\n    Instrucciones de uso: Limpia: Usa el GAON Espuma Limpiadora con Granos sobre el rostro húmedo. Masajea suavemente y enjuaga con agua tibia. Tonifica: Aplica el BELIF Tónico Hidratante Moisturizing Bomb con las manos o un algodón para equilibrar e hidratar la piel. Trata: Aplica el GAON Sérum Tonificante con Semillas con suaves palmaditas hasta su completa absorción. Repara: Coloca unas gotas del AMPLE:N Ampolla Colágeno Shot y luego del AMPLE:N Ampolla Ceramida Shot para mejorar la firmeza y reforzar la barrera cutánea. Mascarilla (1¿2 veces por semana): Usa la BELIF Mascarilla Multivitamínica Super Knights para revitalizar\r\n    o La AMPLE:N Mascarilla Ceramida Shot para hidratación profunda. Finaliza: Aplica el FMGT Tinte Labial Water Fit para un toque de color natural y un acabado fresco.:\r\n    Registro sanitario: NSOC73272-25PE\r\n    Tipo de maquillaje para rostro: Tinta\r\n    Tipo de piel: Todo tipo de piel\r\n    Hipoalergénico: Sí\r\n    Cobertura: Media\r\n    Resistente al agua: Sí', 10, 4, 'Activo', '2026-05-03 00:17:55'),
(5, 'Fijadores de Cejas', 'fijadores-de-cejas', 'Ficha del producto:\r\n\r\n    Marca: BENEFIT\r\n    Modelo: BM73\r\n    Tipo: Paletas de rostro\r\n    Tipo de piel: Todo tipo de piel\r\n    Contenido: 7ml\r\n    Formato: Individual\r\n    Efecto: No aplica\r\n    Factor de protección solar: No contiene\r\n    Consistencia: Gel\r\n    Testeado en animales: No\r\n    ¿Qué incluye?: Gel de cejas 24hr Full size\r\n    Registro INVIMA: NSOC22835-23CO\r\n    Condicion del producto: Nuevo', 8, 5, 'Activo', '2026-05-17 00:13:18'),
(6, 'Redmi Note 15 Pro 8+256', 'redmi-note-15-pro-8256', 'Descubre el nuevo REDMI NOTE 15 PRO, un smartphone diseñado para superar tus expectativas. Captura cada momento con su impresionante cámara de 200MP + 8MP y disfruta de selfies perfectas con la cámara frontal de 32MP. Su potente batería de 6500 mAh te asegura horas de entretenimiento y productividad sin interrupciones. Con 256GB de almacenamiento, tendrás espacio de sobra para tus fotos, videos y aplicaciones favoritas.\r\n\r\n    Disfruta de un rendimiento excepcional gracias a sus 8GB de RAM y el procesador Mediatek Helio Octa Core, que te brindan una experiencia fluida y sin retrasos.\r\n    Sumérgete en la velocidad de la red 4G LTE, para una conexión rápida y estable dondequiera que vayas.\r\n    La pantalla te ofrece colores vibrantes y detalles nítidos para una experiencia visual inmersiva.\r\n    Mantente protegido contra el polvo y las salpicaduras con la certificación IP65, que te brinda mayor tranquilidad en tu día a día.\r\n    Incluye todo lo que necesitas: cargador, cable Type-C, cover y manual para que comiences a disfrutarlo desde el primer momento.', 1, 6, 'Activo', '2026-06-11 23:44:00'),
(7, 'Buzo Conjunto Deportivo Hombre Diadora', 'buzo-conjunto-deportivo-hombre-diadora', 'Información adicional\r\nFicha del producto:\r\n\r\n    - Modelo: BC.M.DVD.W26\r\n    - País de origen: China\r\n    - Condicion del producto: Nuevo\r\n    - Marca: DIADORA\r\n    - Composición: 100%poliéster\r\n    - Estilo de vestuario: Deportivo\r\n    - Fit prenda inferior: Regular fit\r\n    - Género: Hombre\r\n    - Material de vestuario: Poliéster\r\n    - Tipo: Buzo conjunto', 13, 7, 'Activo', '2026-06-12 00:32:18'),
(8, 'Polos deportivos hombre', 'polos-deportivos-hombre', 'nformación adicional\r\nFicha del producto:\r\n\r\n    Marca: PUMA\r\n    Modelo: 687388 02\r\n    Tipo: Polos deportivos\r\n    Género: Hombre\r\n    Material principal: Algodón\r\n    Composición: 100% Algodón\r\n    Largo de mangas: Manga corta\r\n    Fit: Regular\r\n    Estilo: Deportivo\r\n    Disciplina: Training\r\n    Cierre: No\r\n    Temporada: Otoño-Invierno\r\n    Hecho en: Bangladesh\r\n    Condicion del producto: Nuevo', 6, 8, 'Activo', '2026-06-12 00:38:22'),
(9, 'Bikini 2 piezas \"Noah\" Marrón', 'bikini-2-piezas-noah-marron', 'Disfruta del sol con el Bikini \"Noah\" de SOLARY, diseñado para realzar tu figura con un estilo moderno y sofisticado. Este conjunto de dos piezas en color marrón te brindará comodidad y seguridad en cada movimiento, permitiéndote disfrutar al máximo de tus días de playa o piscina. Su diseño liso y corte favorecedor se adaptan a tu silueta, mientras que su tejido de lycra brasilera te proporciona una sensación suave y confortable al tacto. ¡Siéntete única y radiante con este bikini de diseño y producción 100% peruana!\r\n\r\n    Confeccionado en lycra de alta calidad para un ajuste perfecto y durabilidad.\r\n    Diseño de dos piezas que realza tu figura.\r\n    Ideal para disfrutar de tus días de sol con estilo y comodidad.\r\n    Tela licrada que se adapta a tus movimientos (+/- 4 cm).\r\n    Fácil de cuidar: Lavado a máquina máx. 30°, no usar secadora.\r\n\r\nMedidas (Contorno de Cadera): XS (84-89 cm), S (90-96 cm), M (97-103 cm), L (104-110 cm). El contorno del busto se regula al cuerpo gracias a su diseño elástico.', 12, 9, 'Activo', '2026-06-12 00:43:31'),
(10, 'Ropa Traje de Baño Gemma Azul Noche', 'ropa-traje-de-bano-gemma-azul-noche', 'Material: Lycra\r\n\r\nTela Licrada\r\n\r\nCopa Removible\r\n\r\n\r\nMEDIDAS (Contorno de Cadera)\r\n\r\n    XS = 84 cm - 89 cm\r\n    S = 90 cm - 96 cm\r\n    M = 97 cm - 103 cm\r\n    L = 104 cm - 110 cm\r\n\r\n* La medida del Contorno del Busto se regula al cuerpo (Elástico)\r\n\r\n\r\nINFO\r\n\r\nDiseño y producción 100% peruana\r\n\r\nCopa removible\r\n\r\n\r\nCUIDADOS\r\nLavado a máquina máx. 30º\r\nNo usar secador\r\n\r\n-----------------------------\r\n\r\n\r\nEncuentra más novedades y promociones en nuestra web WWW.SOLARY.PE\r\n\r\n- Instagram: @solary.pe', 12, 9, 'Activo', '2026-06-12 00:48:20');

-- --------------------------------------------------------

--
-- Table structure for table `ProductoVariantes`
--

CREATE TABLE `ProductoVariantes` (
  `Id` int(10) UNSIGNED NOT NULL,
  `ProductoId` int(10) UNSIGNED DEFAULT NULL,
  `Sku` varchar(100) DEFAULT NULL,
  `Precio` decimal(8,2) DEFAULT NULL,
  `PrecioOferta` decimal(8,2) DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ProductoVariantes`
--

INSERT INTO `ProductoVariantes` (`Id`, `ProductoId`, `Sku`, `Precio`, `PrecioOferta`, `CreatedAt`) VALUES
(1, 1, 'IPHONE15PR-XDMWD2', 1100.00, 1099.00, '2026-04-15 21:14:50'),
(2, 2, 'IPHONE17-CFIUTT', 5000.00, 4999.00, '2026-04-16 02:10:13'),
(3, 3, 'ABRIGOPARA-NEECDT', 160.00, 149.90, '2026-05-03 00:09:53'),
(4, 4, 'SETNOOKIFA-IITJBG', 220.00, NULL, '2026-05-03 00:17:55'),
(5, 5, 'FIJADORESDE-BCZLJH', 69.90, 55.92, '2026-05-17 00:13:18'),
(6, 6, 'REDMINOTE1-FCLQEU', 1229.00, 1167.55, '2026-06-11 23:44:00'),
(7, 7, 'BUZOCONJUNT-XEASNJ', 149.00, 89.40, '2026-06-12 00:32:18'),
(8, 8, 'POLOSDEPORT-KPIO71', 109.00, 76.30, '2026-06-12 00:38:22'),
(9, 9, 'BIKINI2PIE-I7NMAQ', 240.00, 211.20, '2026-06-12 00:43:31'),
(10, 10, 'ROPATRAJED-SJ4VSG', 290.00, 246.50, '2026-06-12 00:48:20');

-- --------------------------------------------------------

--
-- Table structure for table `Roles`
--

CREATE TABLE `Roles` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Roles`
--

INSERT INTO `Roles` (`Id`, `Nombre`) VALUES
(1, 'admin'),
(3, 'Usuario');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('d09JBGSMuceVDBsywmqo2bajR5cJDFFq7UMuuDDN', 10, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:149.0) Gecko/20100101 Firefox/149.0', 'eyJfdG9rZW4iOiI4UXM2RXJaa2FEamZkMlBqN3hCQWdWUVBMVGFxVEgxYkg2TGxDR1hiIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hZG1pblwvZGFzaGJvYXJkIiwicm91dGUiOiJhZG1pbi5kYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MTB9', 1776284915);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `UsuarioRoles`
--

CREATE TABLE `UsuarioRoles` (
  `Id` int(10) UNSIGNED NOT NULL,
  `UsuarioId` int(10) UNSIGNED DEFAULT NULL,
  `RolId` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `UsuarioRoles`
--

INSERT INTO `UsuarioRoles` (`Id`, `UsuarioId`, `RolId`) VALUES
(6, 10, 1),
(10, 13, 1),
(11, 14, 3),
(13, 7, 1),
(17, 22, 3);

-- --------------------------------------------------------

--
-- Table structure for table `Usuarios`
--

CREATE TABLE `Usuarios` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Alias` varchar(60) DEFAULT NULL,
  `Nombre` varchar(60) DEFAULT NULL,
  `Apellidos` varchar(100) DEFAULT NULL,
  `Correo` varchar(120) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Telefono` char(9) DEFAULT NULL,
  `Dni` char(8) DEFAULT NULL,
  `Ruc` char(11) DEFAULT NULL,
  `RazonSocial` varchar(150) DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Usuarios`
--

INSERT INTO `Usuarios` (`Id`, `Alias`, `Nombre`, `Apellidos`, `Correo`, `Password`, `Telefono`, `Dni`, `Ruc`, `RazonSocial`, `CreatedAt`) VALUES
(7, 's4tker', 'David', 'Altamirano', 's4tker@gmail.com', '$2y$12$foKMrXZGOZy/dFvgN5c9TOiRrppAmV9wPWH27Tqv4DboCirVokeaa', '978683140', '12345678', NULL, NULL, '2026-04-15 18:01:47'),
(10, 'admin', 'Admin', 'Principal', 'admin@store.com', '$2y$12$nsGOmtIkssXxmFnOEHpeQ.vnT9fOr2teoMZe2AXiOiKLISxYGxanu', NULL, NULL, NULL, NULL, '2026-04-15 18:14:36'),
(13, 'x', NULL, NULL, 'x@gmail.com', '$2y$12$QwmMZ4fMVeinU1Jj/V.mDOAU33iZsag0ZUMd3GkNrh5BcqP5ZpVzi', NULL, NULL, NULL, NULL, '2026-04-29 08:12:21'),
(14, 'store', NULL, NULL, 'store@gmail.com', '$2y$12$j2pxr4t/FO6t8vPBLPkonurBIENOmHWSOYTPa4GUGGE8TrAW63yWG', NULL, NULL, NULL, NULL, '2026-04-29 08:34:51'),
(22, 'jazx368', NULL, NULL, 'jazx368@gmail.com', '$2y$12$TtCizxx41NZsRz6Qbil0zOt5fPZvIPxKtnm.nZj4KVYuwoNeUn/D.', NULL, NULL, NULL, NULL, '2026-05-17 01:35:26');

-- --------------------------------------------------------

--
-- Table structure for table `VarianteAtributos`
--

CREATE TABLE `VarianteAtributos` (
  `Id` int(10) UNSIGNED NOT NULL,
  `VarianteId` int(10) UNSIGNED DEFAULT NULL,
  `ValorId` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `VarianteAtributos`
--

INSERT INTO `VarianteAtributos` (`Id`, `VarianteId`, `ValorId`) VALUES
(26, 3, 3),
(27, 3, 2),
(28, 3, 6),
(29, 3, 5),
(30, 3, 7),
(31, 3, 4),
(32, 1, 1),
(34, 5, 8),
(35, 6, 9),
(36, 6, 10),
(37, 6, 11),
(38, 6, 12),
(39, 6, 2),
(40, 6, 13),
(41, 6, 14),
(42, 7, 15),
(43, 7, 16),
(44, 7, 17),
(45, 7, 18),
(46, 7, 19),
(47, 7, 20),
(48, 7, 21),
(49, 7, 5),
(50, 7, 7),
(51, 7, 22),
(52, 7, 23),
(53, 8, 24),
(54, 8, 25),
(55, 8, 26),
(56, 8, 20),
(57, 8, 27),
(58, 8, 5),
(59, 8, 7),
(60, 8, 22),
(61, 8, 23),
(62, 9, 28),
(63, 9, 29),
(64, 9, 30),
(65, 9, 31),
(66, 9, 7),
(67, 9, 22),
(68, 9, 5),
(69, 10, 28),
(70, 10, 32),
(71, 10, 33),
(72, 10, 34),
(73, 10, 35),
(74, 10, 3),
(75, 10, 5),
(76, 10, 22),
(77, 10, 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Atributos`
--
ALTER TABLE `Atributos`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `AtributoValores`
--
ALTER TABLE `AtributoValores`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `AtributoId` (`AtributoId`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `CarritoItems`
--
ALTER TABLE `CarritoItems`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `CarritoId` (`CarritoId`),
  ADD KEY `VarianteId` (`VarianteId`);

--
-- Indexes for table `Carritos`
--
ALTER TABLE `Carritos`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `UsuarioId` (`UsuarioId`);

--
-- Indexes for table `Categorias`
--
ALTER TABLE `Categorias`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Slug` (`Slug`),
  ADD KEY `ParentId` (`ParentId`);

--
-- Indexes for table `Direcciones`
--
ALTER TABLE `Direcciones`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `UsuarioId` (`UsuarioId`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `Inventario`
--
ALTER TABLE `Inventario`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `VarianteId` (`VarianteId`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Marcas`
--
ALTER TABLE `Marcas`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `MovimientosStock`
--
ALTER TABLE `MovimientosStock`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `VarianteId` (`VarianteId`);

--
-- Indexes for table `Pagos`
--
ALTER TABLE `Pagos`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `PedidoId` (`PedidoId`);

--
-- Indexes for table `PasswordResets`
--
ALTER TABLE `PasswordResets`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Token` (`Token`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `PedidoDetalles`
--
ALTER TABLE `PedidoDetalles`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `PedidoId` (`PedidoId`),
  ADD KEY `VarianteId` (`VarianteId`);

--
-- Indexes for table `Pedidos`
--
ALTER TABLE `Pedidos`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `UsuarioId` (`UsuarioId`),
  ADD KEY `DireccionId` (`DireccionId`);

--
-- Indexes for table `PendingUserVerifications`
--
ALTER TABLE `PendingUserVerifications`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `pendinguserverifications_email_unique` (`Email`);

--
-- Indexes for table `ProductoImagenes`
--
ALTER TABLE `ProductoImagenes`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `ProductoId` (`ProductoId`);

--
-- Indexes for table `Productos`
--
ALTER TABLE `Productos`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Slug` (`Slug`),
  ADD KEY `CategoriaId` (`CategoriaId`),
  ADD KEY `MarcaId` (`MarcaId`);

--
-- Indexes for table `ProductoVariantes`
--
ALTER TABLE `ProductoVariantes`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Sku` (`Sku`),
  ADD KEY `ProductoId` (`ProductoId`);

--
-- Indexes for table `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `UsuarioRoles`
--
ALTER TABLE `UsuarioRoles`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `UsuarioId` (`UsuarioId`),
  ADD KEY `RolId` (`RolId`);

--
-- Indexes for table `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Correo` (`Correo`),
  ADD UNIQUE KEY `Dni` (`Dni`),
  ADD UNIQUE KEY `Ruc` (`Ruc`);

--
-- Indexes for table `VarianteAtributos`
--
ALTER TABLE `VarianteAtributos`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `VarianteId` (`VarianteId`),
  ADD KEY `ValorId` (`ValorId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Atributos`
--
ALTER TABLE `Atributos`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `AtributoValores`
--
ALTER TABLE `AtributoValores`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `CarritoItems`
--
ALTER TABLE `CarritoItems`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Carritos`
--
ALTER TABLE `Carritos`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Categorias`
--
ALTER TABLE `Categorias`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `Direcciones`
--
ALTER TABLE `Direcciones`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Inventario`
--
ALTER TABLE `Inventario`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Marcas`
--
ALTER TABLE `Marcas`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `MovimientosStock`
--
ALTER TABLE `MovimientosStock`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `Pagos`
--
ALTER TABLE `Pagos`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PasswordResets`
--
ALTER TABLE `PasswordResets`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `PedidoDetalles`
--
ALTER TABLE `PedidoDetalles`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `Pedidos`
--
ALTER TABLE `Pedidos`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `PendingUserVerifications`
--
ALTER TABLE `PendingUserVerifications`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ProductoImagenes`
--
ALTER TABLE `ProductoImagenes`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `Productos`
--
ALTER TABLE `Productos`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ProductoVariantes`
--
ALTER TABLE `ProductoVariantes`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Roles`
--
ALTER TABLE `Roles`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UsuarioRoles`
--
ALTER TABLE `UsuarioRoles`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `VarianteAtributos`
--
ALTER TABLE `VarianteAtributos`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `AtributoValores`
--
ALTER TABLE `AtributoValores`
  ADD CONSTRAINT `1` FOREIGN KEY (`AtributoId`) REFERENCES `Atributos` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `CarritoItems`
--
ALTER TABLE `CarritoItems`
  ADD CONSTRAINT `1` FOREIGN KEY (`CarritoId`) REFERENCES `Carritos` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`VarianteId`) REFERENCES `ProductoVariantes` (`Id`);

--
-- Constraints for table `Carritos`
--
ALTER TABLE `Carritos`
  ADD CONSTRAINT `1` FOREIGN KEY (`UsuarioId`) REFERENCES `Usuarios` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `Categorias`
--
ALTER TABLE `Categorias`
  ADD CONSTRAINT `1` FOREIGN KEY (`ParentId`) REFERENCES `Categorias` (`Id`) ON DELETE SET NULL;

--
-- Constraints for table `Direcciones`
--
ALTER TABLE `Direcciones`
  ADD CONSTRAINT `1` FOREIGN KEY (`UsuarioId`) REFERENCES `Usuarios` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `Inventario`
--
ALTER TABLE `Inventario`
  ADD CONSTRAINT `1` FOREIGN KEY (`VarianteId`) REFERENCES `ProductoVariantes` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `MovimientosStock`
--
ALTER TABLE `MovimientosStock`
  ADD CONSTRAINT `1` FOREIGN KEY (`VarianteId`) REFERENCES `ProductoVariantes` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `Pagos`
--
ALTER TABLE `Pagos`
  ADD CONSTRAINT `1` FOREIGN KEY (`PedidoId`) REFERENCES `Pedidos` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `PedidoDetalles`
--
ALTER TABLE `PedidoDetalles`
  ADD CONSTRAINT `1` FOREIGN KEY (`PedidoId`) REFERENCES `Pedidos` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`VarianteId`) REFERENCES `ProductoVariantes` (`Id`);

--
-- Constraints for table `Pedidos`
--
ALTER TABLE `Pedidos`
  ADD CONSTRAINT `1` FOREIGN KEY (`UsuarioId`) REFERENCES `Usuarios` (`Id`),
  ADD CONSTRAINT `2` FOREIGN KEY (`DireccionId`) REFERENCES `Direcciones` (`Id`);

--
-- Constraints for table `ProductoImagenes`
--
ALTER TABLE `ProductoImagenes`
  ADD CONSTRAINT `1` FOREIGN KEY (`ProductoId`) REFERENCES `Productos` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `Productos`
--
ALTER TABLE `Productos`
  ADD CONSTRAINT `1` FOREIGN KEY (`CategoriaId`) REFERENCES `Categorias` (`Id`),
  ADD CONSTRAINT `2` FOREIGN KEY (`MarcaId`) REFERENCES `Marcas` (`Id`);

--
-- Constraints for table `ProductoVariantes`
--
ALTER TABLE `ProductoVariantes`
  ADD CONSTRAINT `1` FOREIGN KEY (`ProductoId`) REFERENCES `Productos` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `UsuarioRoles`
--
ALTER TABLE `UsuarioRoles`
  ADD CONSTRAINT `1` FOREIGN KEY (`UsuarioId`) REFERENCES `Usuarios` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`RolId`) REFERENCES `Roles` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `VarianteAtributos`
--
ALTER TABLE `VarianteAtributos`
  ADD CONSTRAINT `1` FOREIGN KEY (`VarianteId`) REFERENCES `ProductoVariantes` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`ValorId`) REFERENCES `AtributoValores` (`Id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
