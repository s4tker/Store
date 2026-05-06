-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-05-2026 a las 19:23:19
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `storedb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atributos`
--

CREATE TABLE `atributos` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `atributos`
--

INSERT INTO `atributos` (`Id`, `Nombre`) VALUES
(1, 'RAM');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atributovalores`
--

CREATE TABLE `atributovalores` (
  `Id` int(10) UNSIGNED NOT NULL,
  `AtributoId` int(10) UNSIGNED DEFAULT NULL,
  `Valor` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `atributovalores`
--

INSERT INTO `atributovalores` (`Id`, `AtributoId`, `Valor`) VALUES
(1, 1, '16 GB');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritoitems`
--

CREATE TABLE `carritoitems` (
  `Id` int(10) UNSIGNED NOT NULL,
  `CarritoId` int(10) UNSIGNED DEFAULT NULL,
  `VarianteId` int(10) UNSIGNED DEFAULT NULL,
  `Cantidad` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritos`
--

CREATE TABLE `carritos` (
  `Id` int(10) UNSIGNED NOT NULL,
  `UsuarioId` int(10) UNSIGNED DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Nombre` varchar(100) DEFAULT NULL,
  `Slug` varchar(150) DEFAULT NULL,
  `ParentId` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`Id`, `Nombre`, `Slug`, `ParentId`) VALUES
(1, 'Tecnología', 'tecnologia', NULL),
(3, 'Celulares', 'celulares-2', 1),
(4, 'Laptops', 'laptops', 1),
(5, 'Ropa', 'ropa', NULL),
(6, 'Polos', 'polos', 5),
(7, 'Casacas', 'casacas', 5),
(8, 'Maquillaje', 'maquillaje', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
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
-- Volcado de datos para la tabla `direcciones`
--

INSERT INTO `direcciones` (`Id`, `UsuarioId`, `Pais`, `Region`, `Ciudad`, `Direccion`, `Referencia`, `CreatedAt`) VALUES
(1, 7, 'Peru', 'Lima', 'Miraflones', 'calle 1', NULL, '2026-04-19 23:56:36'),
(2, 15, 'Perú', 'lambayeque', 'chciclayo', 'av.chiclayo123', '', '2026-04-30 17:39:02'),
(3, 15, 'Perú', 'lambayeque', 'chciclayo', 'av.chiclayo123', '', '2026-04-30 17:54:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
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
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `Id` int(10) UNSIGNED NOT NULL,
  `VarianteId` int(10) UNSIGNED DEFAULT NULL,
  `Stock` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`Id`, `VarianteId`, `Stock`) VALUES
(1, 1, 12),
(2, 2, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
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
-- Estructura de tabla para la tabla `job_batches`
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
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Nombre` varchar(100) DEFAULT NULL,
  `Slug` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`Id`, `Nombre`, `Slug`) VALUES
(1, 'samsung', 'samsung'),
(2, 'IPhone', 'iphone');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000000_create_users_table', 2),
(3, '0001_01_01_000002_create_jobs_table', 2),
(4, '2026_04_16_000000_add_dni_ruc_to_usuarios_table', 3),
(5, '2026_04_29_000001_normalize_roles_to_admin_and_usuario', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientosstock`
--

CREATE TABLE `movimientosstock` (
  `Id` int(10) UNSIGNED NOT NULL,
  `VarianteId` int(10) UNSIGNED DEFAULT NULL,
  `Tipo` enum('Entrada','Salida') DEFAULT NULL,
  `Cantidad` smallint(6) DEFAULT NULL,
  `Motivo` varchar(100) DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `movimientosstock`
--

INSERT INTO `movimientosstock` (`Id`, `VarianteId`, `Tipo`, `Cantidad`, `Motivo`, `CreatedAt`) VALUES
(1, 2, 'Salida', 1, 'Venta', '2026-04-30 17:39:02'),
(2, 1, 'Salida', 1, 'Venta', '2026-04-30 17:39:02'),
(3, 2, 'Entrada', 1, 'Cancelación de pedido', '2026-04-30 17:51:11'),
(4, 1, 'Entrada', 1, 'Cancelación de pedido', '2026-04-30 17:51:11'),
(5, 1, 'Salida', 1, 'Venta', '2026-04-30 17:54:34'),
(6, 2, 'Salida', 1, 'Venta', '2026-04-30 17:54:34'),
(7, 1, 'Entrada', 1, 'Cancelación de pedido', '2026-04-30 18:11:19'),
(8, 2, 'Entrada', 1, 'Cancelación de pedido', '2026-04-30 18:11:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `Id` int(10) UNSIGNED NOT NULL,
  `PedidoId` int(10) UNSIGNED DEFAULT NULL,
  `Metodo` varchar(50) DEFAULT NULL,
  `Monto` decimal(10,2) DEFAULT NULL,
  `Estado` enum('Pendiente','Aprobado','Rechazado') DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `passwordresets`
--

CREATE TABLE `passwordresets` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Correo` varchar(120) DEFAULT NULL,
  `Token` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidodetalles`
--

CREATE TABLE `pedidodetalles` (
  `Id` int(10) UNSIGNED NOT NULL,
  `PedidoId` int(10) UNSIGNED DEFAULT NULL,
  `VarianteId` int(10) UNSIGNED DEFAULT NULL,
  `Cantidad` smallint(6) DEFAULT NULL,
  `Precio` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pedidodetalles`
--

INSERT INTO `pedidodetalles` (`Id`, `PedidoId`, `VarianteId`, `Cantidad`, `Precio`) VALUES
(1, 1, 2, 1, 4999.00),
(2, 1, 1, 1, 1099.00),
(3, 2, 1, 1, 1099.00),
(4, 2, 2, 1, 4999.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `Id` int(10) UNSIGNED NOT NULL,
  `UsuarioId` int(10) UNSIGNED DEFAULT NULL,
  `DireccionId` int(10) UNSIGNED DEFAULT NULL,
  `Total` decimal(10,2) DEFAULT NULL,
  `Estado` enum('Pendiente','Pagado','Enviado','Entregado','Cancelado') DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`Id`, `UsuarioId`, `DireccionId`, `Total`, `Estado`, `CreatedAt`) VALUES
(1, 15, 2, 6098.00, 'Cancelado', '2026-04-30 22:39:02'),
(2, 15, 3, 6098.00, 'Cancelado', '2026-04-30 22:54:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productoimagenes`
--

CREATE TABLE `productoimagenes` (
  `Id` int(10) UNSIGNED NOT NULL,
  `ProductoId` int(10) UNSIGNED DEFAULT NULL,
  `Url` varchar(255) DEFAULT NULL,
  `Orden` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productoimagenes`
--

INSERT INTO `productoimagenes` (`Id`, `ProductoId`, `Url`, `Orden`) VALUES
(1, 1, 'productos/717HjouFSmZoVbCzVzQWGMamZpQWfa3YuRBgoM6l.webp', 1),
(2, 1, 'productos/sbkUnimDtRFZYilwqYMPOIX540LTkUNNAMByejYb.jpg', 2),
(3, 2, 'productos/faUda6KRrjdDzGqrg5aA184jecDbgH0UqUDb9lel.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
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
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`Id`, `Nombre`, `Slug`, `Descripcion`, `CategoriaId`, `MarcaId`, `Estado`, `CreatedAt`) VALUES
(1, 'Iphone 15 PRO max', 'iphone-15-pro-max', 'que  hermoso productos :v', 3, 2, 'Inactivo', '2026-04-15 21:14:50'),
(2, 'iphone17', 'iphone17', 'que good', 3, 2, 'Activo', '2026-04-16 02:10:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productovariantes`
--

CREATE TABLE `productovariantes` (
  `Id` int(10) UNSIGNED NOT NULL,
  `ProductoId` int(10) UNSIGNED DEFAULT NULL,
  `Sku` varchar(100) DEFAULT NULL,
  `Precio` decimal(8,2) DEFAULT NULL,
  `PrecioOferta` decimal(8,2) DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productovariantes`
--

INSERT INTO `productovariantes` (`Id`, `ProductoId`, `Sku`, `Precio`, `PrecioOferta`, `CreatedAt`) VALUES
(1, 1, 'IPHONE15PR-XDMWD2', 1100.00, 1099.00, '2026-04-15 21:14:50'),
(2, 2, 'IPHONE17-CFIUTT', 5000.00, 4999.00, '2026-04-16 02:10:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`Id`, `Nombre`) VALUES
(1, 'admin'),
(3, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
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
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Abw6hGZeK4np4r7YxY6yHzl9UUIU0OGHtj5sc9xZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJBOXl4NnRZRlYwUWV4TVVMMFl5NGRSdnpEanQ0WXlxWHhJWGNBR1ZRIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbj9yZWRpcmVjdD0lMkZjb21wcmFzJTJGZm9ybXVsYXJpbyIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1777587560),
('IH26YPMJAxmKtSacXOSZCUfPBvM8Aa1pvsoMXnsY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJDYnFpVFBMRjFpbm8wQkQ1WThIRG5lUXd0YVF1c1RMeTgydWRnMWsxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbj9yZWRpcmVjdD0lMkZjb21wcmFzJTJGZm9ybXVsYXJpbyIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1777610653),
('jYqNLfG0V27gJnRg1Bq5zoj2UJ4UgyLP41sf9n7m', 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJXV1ZtOWRTcDhSaENKcVVSS1FUVmVMdno1ZzMyRWtXalhrUldxN3NWIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jb21wcmFzXC9mb3JtdWxhcmlvIiwicm91dGUiOiJjb21wcmFzLmZvcm11bGFyaW8ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MTV9', 1777580099),
('Z14QztZF6upKBMrqMhawJy5rWHg50ztu8vfDwScf', 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJzUjJpUkV5RjlZaFdMWVdiUDhsT0QzUm9DbTVmNmd5SG1GUk9CRzE0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jb21wcmFzXC9mb3JtdWxhcmlvIiwicm91dGUiOiJjb21wcmFzLmZvcm11bGFyaW8ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MTV9', 1777872045);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
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
-- Estructura de tabla para la tabla `usuarioroles`
--

CREATE TABLE `usuarioroles` (
  `Id` int(10) UNSIGNED NOT NULL,
  `UsuarioId` int(10) UNSIGNED DEFAULT NULL,
  `RolId` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarioroles`
--

INSERT INTO `usuarioroles` (`Id`, `UsuarioId`, `RolId`) VALUES
(6, 10, 1),
(9, 7, 3),
(10, 13, 1),
(11, 14, 3),
(12, 15, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
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
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Id`, `Alias`, `Nombre`, `Apellidos`, `Correo`, `Password`, `Telefono`, `Dni`, `Ruc`, `RazonSocial`, `CreatedAt`) VALUES
(7, 's4tker', 'David', 'Altamirano', 's4tker@gmail.com', '$2y$12$s91wHewZ7qoNmjBIIAAGa.R1OVLhs7JUkPSWbZwZbhV6YIqOR/wWa', '978683140', '1234567', NULL, NULL, '2026-04-15 18:01:47'),
(10, 'admin', 'Admin', 'Principal', 'admin@store.com', '$2y$12$nsGOmtIkssXxmFnOEHpeQ.vnT9fOr2teoMZe2AXiOiKLISxYGxanu', NULL, NULL, NULL, NULL, '2026-04-15 18:14:36'),
(13, 'x', NULL, NULL, 'x@gmail.com', '$2y$12$QwmMZ4fMVeinU1Jj/V.mDOAU33iZsag0ZUMd3GkNrh5BcqP5ZpVzi', NULL, NULL, NULL, NULL, '2026-04-29 08:12:21'),
(14, 'store', NULL, NULL, 'store@gmail.com', '$2y$12$j2pxr4t/FO6t8vPBLPkonurBIENOmHWSOYTPa4GUGGE8TrAW63yWG', NULL, NULL, NULL, NULL, '2026-04-29 08:34:51'),
(15, 'cheveztrujillo1', NULL, NULL, 'cheveztrujillo1@gmail.com', '$2y$12$pl6ZBfIzep3bFNadqSfg.eB.DF7CtU8kCLQuUghtgWPv4pg8HeaUW', NULL, NULL, NULL, NULL, '2026-04-30 16:11:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `varianteatributos`
--

CREATE TABLE `varianteatributos` (
  `Id` int(10) UNSIGNED NOT NULL,
  `VarianteId` int(10) UNSIGNED DEFAULT NULL,
  `ValorId` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `varianteatributos`
--

INSERT INTO `varianteatributos` (`Id`, `VarianteId`, `ValorId`) VALUES
(7, 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `atributos`
--
ALTER TABLE `atributos`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `atributovalores`
--
ALTER TABLE `atributovalores`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `AtributoId` (`AtributoId`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indices de la tabla `carritoitems`
--
ALTER TABLE `carritoitems`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `CarritoId` (`CarritoId`),
  ADD KEY `VarianteId` (`VarianteId`);

--
-- Indices de la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `UsuarioId` (`UsuarioId`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Slug` (`Slug`),
  ADD KEY `ParentId` (`ParentId`);

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `UsuarioId` (`UsuarioId`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `VarianteId` (`VarianteId`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimientosstock`
--
ALTER TABLE `movimientosstock`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `VarianteId` (`VarianteId`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `PedidoId` (`PedidoId`);

--
-- Indices de la tabla `passwordresets`
--
ALTER TABLE `passwordresets`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Token` (`Token`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `pedidodetalles`
--
ALTER TABLE `pedidodetalles`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `PedidoId` (`PedidoId`),
  ADD KEY `VarianteId` (`VarianteId`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `UsuarioId` (`UsuarioId`),
  ADD KEY `DireccionId` (`DireccionId`);

--
-- Indices de la tabla `productoimagenes`
--
ALTER TABLE `productoimagenes`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `ProductoId` (`ProductoId`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Slug` (`Slug`),
  ADD KEY `CategoriaId` (`CategoriaId`),
  ADD KEY `MarcaId` (`MarcaId`);

--
-- Indices de la tabla `productovariantes`
--
ALTER TABLE `productovariantes`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Sku` (`Sku`),
  ADD KEY `ProductoId` (`ProductoId`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `usuarioroles`
--
ALTER TABLE `usuarioroles`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `UsuarioId` (`UsuarioId`),
  ADD KEY `RolId` (`RolId`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Correo` (`Correo`),
  ADD UNIQUE KEY `Dni` (`Dni`),
  ADD UNIQUE KEY `Ruc` (`Ruc`);

--
-- Indices de la tabla `varianteatributos`
--
ALTER TABLE `varianteatributos`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `VarianteId` (`VarianteId`),
  ADD KEY `ValorId` (`ValorId`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `atributos`
--
ALTER TABLE `atributos`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `atributovalores`
--
ALTER TABLE `atributovalores`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `carritoitems`
--
ALTER TABLE `carritoitems`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `carritos`
--
ALTER TABLE `carritos`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `movimientosstock`
--
ALTER TABLE `movimientosstock`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `passwordresets`
--
ALTER TABLE `passwordresets`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidodetalles`
--
ALTER TABLE `pedidodetalles`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productoimagenes`
--
ALTER TABLE `productoimagenes`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productovariantes`
--
ALTER TABLE `productovariantes`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarioroles`
--
ALTER TABLE `usuarioroles`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `varianteatributos`
--
ALTER TABLE `varianteatributos`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `atributovalores`
--
ALTER TABLE `atributovalores`
  ADD CONSTRAINT `1` FOREIGN KEY (`AtributoId`) REFERENCES `atributos` (`Id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
