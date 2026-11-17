-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-11-2026 a las 17:12:21
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `voluntmatch`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_nombre` varchar(255) NOT NULL,
  `modulo` varchar(255) NOT NULL,
  `detalle` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_nombre`, `modulo`, `detalle`, `created_at`, `updated_at`) VALUES
(1, 'Coordinación Proyección Social UC', 'Soporte / Consultas', 'Respondió consulta #1 de LUIS (quintana@gmail.com)', '2026-11-16 21:11:04', '2026-11-16 21:11:04'),
(2, 'Coordinación Proyección Social UC', 'Verificación ONG', 'Cambió estado institucional de \'luisong234\' a Aprobado', '2026-11-17 10:44:24', '2026-11-17 10:44:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `postulacion_id` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `horas_registradas` int(11) NOT NULL,
  `actividad` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id`, `postulacion_id`, `fecha`, `horas_registradas`, `actividad`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-11-15', 7, 'rwer', '2026-11-16 03:56:32', '2026-11-16 03:56:32');

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
-- Estructura de tabla para la tabla `consultas_contacto`
--

CREATE TABLE `consultas_contacto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rol_remitente` varchar(255) NOT NULL DEFAULT 'Visitante',
  `nombre` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `asunto` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `respuesta_admin` text DEFAULT NULL,
  `estado` varchar(255) NOT NULL DEFAULT 'Pendiente',
  `respondido_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `consultas_contacto`
--

INSERT INTO `consultas_contacto` (`id`, `user_id`, `rol_remitente`, `nombre`, `correo`, `asunto`, `mensaje`, `respuesta_admin`, `estado`, `respondido_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Voluntario', 'LUIS', 'quintana@gmail.com', 'Acreditación de Horas y Certificados QR', 'quiero saber como estas admin?', 'gracias por comunicarte', 'Respondida', '2026-11-16 21:11:04', '2026-11-16 21:10:29', '2026-11-16 21:11:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donaciones`
--

CREATE TABLE `donaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `oportunidad_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `donante_nombre` varchar(255) NOT NULL,
  `donante_email` varchar(255) DEFAULT NULL,
  `es_anonimo` tinyint(1) NOT NULL DEFAULT 0,
  `metodo_pago` varchar(50) NOT NULL,
  `monto` decimal(10,2) NOT NULL DEFAULT 0.00,
  `detalle_especie` varchar(255) DEFAULT NULL,
  `codigo_comprobante` varchar(40) NOT NULL,
  `mensaje_apoyo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `donante_dni` varchar(20) DEFAULT NULL,
  `numero_operacion` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `donaciones`
--

INSERT INTO `donaciones` (`id`, `oportunidad_id`, `user_id`, `donante_nombre`, `donante_email`, `es_anonimo`, `metodo_pago`, `monto`, `detalle_especie`, `codigo_comprobante`, `mensaje_apoyo`, `created_at`, `updated_at`, `donante_dni`, `numero_operacion`) VALUES
(1, 1, 1, 'Donante Solidario Anónimo', 'luisong23@gmail.com', 1, 'Yape', 10.00, NULL, 'DON-CUSCO-2026-BRS4NG', NULL, '2026-11-16 08:50:37', '2026-11-16 08:50:37', NULL, NULL),
(2, 1, 2, 'LUIS', 'quintana@gmail.com', 0, 'Plin', 5.00, NULL, 'VCH-CUSCO-2026-BY5JXI', 'sasas', '2026-11-16 09:29:32', '2026-11-16 09:29:32', '73203633', '12121212');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_11_03_041212_create_organizacions_table', 1),
(5, '2026_11_03_041213_create_oportunidads_table', 1),
(6, '2026_11_10_045643_create_permission_tables', 1),
(7, '2026_11_10_151324_create_postulaciones_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tipo` varchar(255) NOT NULL DEFAULT 'info',
  `titulo` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `postulacion_id` bigint(20) UNSIGNED DEFAULT NULL,
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id`, `user_id`, `tipo`, `titulo`, `mensaje`, `postulacion_id`, `leida`, `created_at`, `updated_at`) VALUES
(1, 2, 'aprobada', '¡Fuiste aceptado en la campaña!', 'luisong23 aceptó tu postulación para \"Campaña de Prevención de Incendios Forestales y Protección de Laderas en Cusco\". Ya puedes coordinar tus jornadas por WhatsApp.', 1, 1, '2026-11-16 03:56:10', '2026-11-16 03:57:11'),
(2, 2, 'avance', 'Nuevas horas registradas (+7 hrs)', 'luisong23 registró +7 hrs por \"rwer\" en Campaña de Prevención de Incendios Forestales y Protección de Laderas en Cusco. Acumulas 7/5 hrs.', 1, 1, '2026-11-16 03:56:32', '2026-11-16 03:57:11'),
(3, 2, 'certificado', '🎉 ¡Felicitaciones! Certificado Oficial Emitido', 'La organización luisong23 ha validado tus 7 horas en \"Campaña de Prevención de Incendios Forestales y Protección de Laderas en Cusco\" y emitió tu Certificado Oficial (VM-CUSCO-2026-0001).', 1, 1, '2026-11-16 03:56:40', '2026-11-16 03:57:11'),
(4, 1, 'donacion', '¡Nueva Donación Solidaria Recibida!', 'LUIS registró un aporte de S/ 5.00 vía Plin (Op: 12121212) para la campaña \"Campaña de Prevención de Incendios Forestales y Protección de Laderas en Cusco\". Voucher: VCH-CUSCO-2026-BY5JXI.', NULL, 0, '2026-11-16 09:29:32', '2026-11-16 09:29:32'),
(5, 2, 'respuesta_admin', 'Respuesta de Coordinación: Acreditación de Horas y Certificados QR', 'gracias por comunicarte', NULL, 0, '2026-11-16 21:11:04', '2026-11-16 21:11:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oportunidades`
--

CREATE TABLE `oportunidades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ong_id` bigint(20) UNSIGNED NOT NULL,
  `ong_nombre` varchar(255) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `distrito` varchar(255) NOT NULL,
  `direccion_exacta` varchar(255) DEFAULT NULL,
  `latitud` varchar(255) NOT NULL DEFAULT '-13.53195',
  `longitud` varchar(255) NOT NULL DEFAULT '-71.96746',
  `horario` varchar(255) NOT NULL DEFAULT '¡Es Flexible! Nos acomodamos a tu horario.',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `horas` int(11) NOT NULL DEFAULT 20,
  `cupos` int(11) NOT NULL DEFAULT 10,
  `cupos_disponibles` int(11) NOT NULL DEFAULT 10,
  `estado` varchar(255) NOT NULL DEFAULT 'Abierta',
  `imagen` text DEFAULT NULL,
  `descripcion` text NOT NULL,
  `funciones` text DEFAULT NULL,
  `requisitos` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `acepta_donaciones` tinyint(1) NOT NULL DEFAULT 0,
  `meta_donacion` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_recaudado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `yape_numero` varchar(30) DEFAULT NULL,
  `tiene_meta_donacion` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `oportunidades`
--

INSERT INTO `oportunidades` (`id`, `ong_id`, `ong_nombre`, `titulo`, `categoria`, `distrito`, `direccion_exacta`, `latitud`, `longitud`, `horario`, `fecha_inicio`, `fecha_fin`, `fecha_cierre`, `horas`, `cupos`, `cupos_disponibles`, `estado`, `imagen`, `descripcion`, `funciones`, `requisitos`, `created_at`, `updated_at`, `acepta_donaciones`, `meta_donacion`, `monto_recaudado`, `yape_numero`, `tiene_meta_donacion`) VALUES
(1, 1, 'luisong23', 'Campaña de Prevención de Incendios Forestales y Protección de Laderas en Cusco', 'Educación', 'Wanchaq', 'Wanchaq, Cusco', '-13.51977', '-71.96740', '¡Es Flexible! Nos acomodamos a tu horario.', '2026-11-15', '2026-12-30', '2026-11-30 23:59:00', 5, 3, 2, 'Abierta', '/uploads/convocatorias/camp_1794719481_6af93ef9267c4.jpeg', 'Proyecto de impacto social enfocado en Campaña de Prevención de Incendios Forestales y Protección de Laderas en Cusco dentro del distrito de Wanchaq, Cusco. Los voluntarios universitarios aplicarán sus competencias académicas en jornadas planificadas de 5 horas con certificación oficial verificable mediante código QR.', 'Participar activamente en las jornadas de campo y planificación en Wanchaq\r\nRegistrar evidencias y reportes de avance en cada sesión asignada\r\nTrabajar de forma colaborativa con el equipo técnico de la organización\r\nApoyar en la sensibilización y entrega de resultados a la comunidad', 'Ser estudiante universitario con disponibilidad para cumplir 5 horas\r\nCompromiso, puntualidad y responsabilidad en las fechas programadas\r\nActitud proactiva para el trabajo en equipo e impacto comunitario\r\nInterés en proyectos vinculados a Educación en la Región Cusco', '2026-11-15 10:11:21', '2026-11-16 08:48:55', 1, 10.00, 15.00, '957500230', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oportunidads`
--

CREATE TABLE `oportunidads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organizacions`
--

CREATE TABLE `organizacions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postulaciones`
--

CREATE TABLE `postulaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `oportunidad_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ong_id` bigint(20) UNSIGNED DEFAULT NULL,
  `proyecto` varchar(255) NOT NULL,
  `organizacion` varchar(255) NOT NULL,
  `horas` int(11) NOT NULL DEFAULT 20,
  `horas_validadas` int(11) NOT NULL DEFAULT 0,
  `codigo_certificado` varchar(255) DEFAULT NULL,
  `universidad` varchar(255) DEFAULT NULL,
  `ciclo` varchar(255) DEFAULT NULL,
  `telefono_contacto` varchar(255) DEFAULT NULL,
  `disponibilidad` varchar(255) DEFAULT NULL,
  `motivacion` text DEFAULT NULL,
  `calificacion` int(11) DEFAULT NULL,
  `resena_ong` text DEFAULT NULL,
  `estado` varchar(255) NOT NULL DEFAULT 'En revisión',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `postulaciones`
--

INSERT INTO `postulaciones` (`id`, `user_id`, `oportunidad_id`, `ong_id`, `proyecto`, `organizacion`, `horas`, `horas_validadas`, `codigo_certificado`, `universidad`, `ciclo`, `telefono_contacto`, `disponibilidad`, `motivacion`, `calificacion`, `resena_ong`, `estado`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 'Campaña de Prevención de Incendios Forestales y Protección de Laderas en Cusco', 'luisong23', 5, 7, 'VM-CUSCO-2026-0001', 'Universidad Continental - Cusco', '4° - 6° Ciclo', '957500230', 'Tardes (Lunes a Viernes)', 'wrwrwqerwqr', 5, 'Excelente compromiso, puntualidad y trabajo en equipo durante toda la campaña.', 'Completada', '2026-11-16 03:55:31', '2026-11-16 03:56:40'),
(2, 3, 1, 1, 'Campaña de Prevención de Incendios Forestales y Protección de Laderas en Cusco', 'luisong23', 5, 0, NULL, 'UNSAAC - Cusco', '4° - 6° Ciclo', '957500230', 'Tardes (Lunes a Viernes)', '155151515jbyugb', NULL, NULL, 'Rechazada', '2026-11-16 07:17:47', '2026-11-17 12:01:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rendiciones_gasto`
--

CREATE TABLE `rendiciones_gasto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `oportunidad_id` bigint(20) UNSIGNED NOT NULL,
  `ong_id` bigint(20) UNSIGNED NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `monto_gastado` decimal(10,2) NOT NULL,
  `comprobante_ref` varchar(255) DEFAULT NULL,
  `fecha_gasto` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rendiciones_gasto`
--

INSERT INTO `rendiciones_gasto` (`id`, `oportunidad_id`, `ong_id`, `concepto`, `monto_gastado`, `comprobante_ref`, `fecha_gasto`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'jhhg', 20.00, 'Boleta / Factura Institucional', '2026-11-16', '2026-11-16 09:41:42', '2026-11-16 09:41:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('s0Pv5slL469TLyT6m8GD1bCmYB4KJjzK01AQuN0y', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJzWmo1UlFxYVFsQ1N0MVluNlBCbUxxUXhYc2lNT1hwSTJ6alg5OEIwIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jb250YWN0byIsInJvdXRlIjoiY29udGFjdG8ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1794926146),
('w9EHZtlO0vN8gYuaMzO5utwZQTAuePF9XF1Y0xfD', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ6QTM2OGlZNVJYaDdobE5GTjBFMENQWmlTVG5sUGxBWWJzRE9TaE1yIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2hvbWUiLCJyb3V0ZSI6ImhvbWUifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsInBhc3N3b3JkX2hhc2hfd2ViIjoiZjRiMTEwOWMyODI2NTNjNWRiNTY1MTY4NGMwOWE1ZWRhYzJhOWY0NjQxZTZlZWRmY2UyZmI5OTZlYzVkZWEwNSJ9', 1794901447);

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
  `tipo_usuario` varchar(255) NOT NULL DEFAULT 'estudiante',
  `dni` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `carrera` varchar(255) DEFAULT NULL,
  `disponibilidad` varchar(255) DEFAULT NULL,
  `ruc` varchar(255) DEFAULT NULL,
  `distrito` varchar(255) DEFAULT NULL,
  `mision` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `estado_verificacion` varchar(255) DEFAULT 'Aprobado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `tipo_usuario`, `dni`, `telefono`, `carrera`, `disponibilidad`, `ruc`, `distrito`, `mision`, `remember_token`, `created_at`, `updated_at`, `estado_verificacion`) VALUES
(1, 'luisong23', 'luisong23@gmail.com', NULL, '$2y$12$GYi70oFJfM/pNqocv3FmeOr1ER96bvUv5iKlxmUkC438BeRsehTzW', 'organizacion', NULL, NULL, NULL, NULL, '20225225522', 'Wanchaq', NULL, NULL, '2026-11-15 10:08:49', '2026-11-15 10:08:49', 'Aprobado'),
(2, 'LUIS', 'quintana@gmail.com', NULL, '$2y$12$cjNDsZ8W0YKb4vkDQkxCIevR/c214dVyREOYk00BKQsW1XBLkS5.C', 'estudiante', '73203633', NULL, 'Ingeniería Ambiental', NULL, NULL, 'Cusco Centro', NULL, NULL, '2026-11-16 03:54:11', '2026-11-16 03:54:11', 'Aprobado'),
(3, 'Coordinación Proyección Social UC', 'admin@voluntmatch.edu.pe', NULL, '$2y$12$pU2irP8KyeY7g2K8M7jIAeOEeTK8E2YvXhGRmDLwOppNMDZ42rYcK', 'estudiante', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-11-16 07:10:28', '2026-11-17 12:32:45', 'Aprobado'),
(4, 'luisong234', 'luisong234@gmail.com', NULL, '$2y$12$Xxw5nW52IajUWsSumKSzceHFUhvOp/n1CQwQXhh8lCkw84yg/CUBS', 'organizacion', NULL, '957500231', NULL, NULL, '20121222121', 'San Jerónimo', 'dsadsadasdad', NULL, '2026-11-16 19:33:23', '2026-11-17 10:44:24', 'Aprobado');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`);

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
-- Indices de la tabla `consultas_contacto`
--
ALTER TABLE `consultas_contacto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `donaciones`
--
ALTER TABLE `donaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `donaciones_codigo_comprobante_unique` (`codigo_comprobante`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

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
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `oportunidades`
--
ALTER TABLE `oportunidades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `oportunidads`
--
ALTER TABLE `oportunidads`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `organizacions`
--
ALTER TABLE `organizacions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rendiciones_gasto`
--
ALTER TABLE `rendiciones_gasto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `consultas_contacto`
--
ALTER TABLE `consultas_contacto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `donaciones`
--
ALTER TABLE `donaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `oportunidades`
--
ALTER TABLE `oportunidades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `oportunidads`
--
ALTER TABLE `oportunidads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `organizacions`
--
ALTER TABLE `organizacions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `rendiciones_gasto`
--
ALTER TABLE `rendiciones_gasto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
