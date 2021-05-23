-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-05-2021 a las 16:39:24
-- Versión del servidor: 10.1.37-MariaDB
-- Versión de PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_sky`
--
CREATE DATABASE IF NOT EXISTS `db_sky` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `db_sky`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sysm_catalogo`
--

CREATE TABLE `sysm_catalogo` (
  `num_catalogo` int(11) NOT NULL,
  `cod_catalogo` char(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT '00',
  `des_catalogo` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `des_acronimo` char(10) COLLATE utf8_spanish_ci NOT NULL,
  `cod_estado` char(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT '01',
  `ind_del` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sysm_catalogo`
--

INSERT INTO `sysm_catalogo` (`num_catalogo`, `cod_catalogo`, `des_catalogo`, `des_acronimo`, `cod_estado`, `ind_del`) VALUES
(1, '0A', 'Catalogo de estados generico', 'GEN', '01', '0'),
(2, '0B', 'Catalogo de ', 'GENE', '01', '0'),
(3, '0C', 'Catalogo categorias negocio', 'CATEG', '01', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sysm_datacatalogo`
--

CREATE TABLE `sysm_datacatalogo` (
  `num_datacatalogo` int(11) NOT NULL,
  `num_catalogo` int(11) NOT NULL,
  `cod_catalogo` char(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT '00',
  `cod_datacata` char(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT '00',
  `des_datacata` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `des_corta` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `cod_estado` char(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT '01',
  `ind_del` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sysm_datacatalogo`
--

INSERT INTO `sysm_datacatalogo` (`num_datacatalogo`, `num_catalogo`, `cod_catalogo`, `cod_datacata`, `des_datacata`, `des_corta`, `cod_estado`, `ind_del`) VALUES
(1, 1, '0A', '01', 'Activo', 'ACT', '01', '0'),
(2, 1, '0A', '02', 'Inactivo', 'INA', '01', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sysm_empresa`
--

CREATE TABLE `sysm_empresa` (
  `num_empresa` int(11) NOT NULL,
  `cod_empresa` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `ruc_empresa` char(11) COLLATE utf8_spanish_ci NOT NULL,
  `des_empresa` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `dir_empresa` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `cod_estado` char(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT '01',
  `ind_del` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sysm_empresa`
--

INSERT INTO `sysm_empresa` (`num_empresa`, `cod_empresa`, `ruc_empresa`, `des_empresa`, `dir_empresa`, `cod_estado`, `ind_del`) VALUES
(1, 'empresa_prueba1', '11111111111', 'BEmpresa Prueba 01', 'Av Pruebas 00', '01', '0'),
(2, 'empresa_prueba2', '22222222222', 'CEmpresa Prueba 02', 'Av Pruebas 01', '02', '0'),
(3, 'empresa_prueba3', '33333333333', 'AEmpresa Prueba 03', 'Av Pruebas 02', '01', '0'),
(4, 'empresa_prueba4', '44444444444', 'DEmpresa Prueba 04', 'Av Pruebas 03', '01', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sysm_navmenu`
--

CREATE TABLE `sysm_navmenu` (
  `num_menu` int(11) NOT NULL,
  `cod_menu` char(30) COLLATE utf8_spanish_ci NOT NULL,
  `des_menu` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `des_icono` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `des_larga` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `ind_nivel` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `ind_modulo` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `cod_estado` char(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT '01',
  `ind_del` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sysm_navmenu`
--

INSERT INTO `sysm_navmenu` (`num_menu`, `cod_menu`, `des_menu`, `des_icono`, `des_larga`, `ind_nivel`, `ind_modulo`, `cod_estado`, `ind_del`) VALUES
(1, 'admi', 'Administracion', 'fa fa-tachometer', 'Modulo Administracion', '1', '0', '01', '0'),
(2, 'cali', 'Gestion Calidad', 'fa fa-table fa-fw', 'Modulo Gestion de la Calidad Negocio', '1', '0', '01', '0'),
(3, 'come', 'Gestion Comercial', 'fa fa-table fa-fw', 'Modulo Comercial', '1', '0', '01', '0'),
(4, 'cont', 'Contabilidad', 'fa fa-table fa-fw', 'Modulo Contabilidad', '1', '0', '01', '0'),
(5, 'fact', 'Facturacion Electronica', 'fa fa-bullseye fa-fw', 'Modulo Facturacion', '1', '0', '01', '0'),
(6, 'gere', 'Gerencia', 'fa fa-th fa-fw', 'Modulo Gerencia', '1', '0', '01', '0'),
(7, 'nego', 'Gestion Negocio', 'fa fa-th fa-fw', 'Modulo Negocios', '1', '0', '01', '0'),
(8, 'plan', 'Planeamiento Estrategico', 'fa fa-th fa-fw', 'Modulo Planeamiento', '1', '0', '01', '0'),
(9, 'proy', 'Gestion Proyectos', 'a fa-building-o fa-fw', 'Modulo Proyectos', '1', '0', '01', '0'),
(10, 'rrhh', 'Recursos Humanos', 'fa fa-users fa-fw', 'Modulo Recursos Humanos', '1', '0', '01', '0'),
(11, 'sist', 'Sistemas', 'fa fa-table fa-fw', 'Modulo de Sistemas', '1', '0', '01', '0'),
(12, 'tics', 'Tecnologias Informacion', 'fa fa-table fa-fw', 'Modulo Tecnologias Informacion', '1', '0', '01', '0'),
(13, 'tcar', 'Transporte Carga', 'fa fa-truck fa-fw', 'Negocio Transporte Carga', '1', '0', '01', '0'),
(99, 'syst', 'System Control', 'fa fa-cog', 'Administrador del Sistema', '1', '0', '01', '0'),
(100, 'syst_user', 'Administrar Usuarios', 'fa fa-users', 'Administración de Usuarios', '2', '1', '01', '0'),
(101, 'admi_dash', 'Dashboard', 'fa fa-tachometer', 'Dashboard', '2', '0', '01', '0'),
(102, 'cont_eeff', 'Estados Financieros ', 'fa fa-bar chart', 'Estados Financieros', '2', '0', '01', '0'),
(103, 'cont_rven', 'Registro Ventas', 'fa fa-plus-square', 'Modulo Registro Ventas', '2', '0', '01', '0'),
(104, 'tcar_cser', 'Cotizacion Servicio', 'fa fa-plus-square', 'Modulo Cotizacion Servicios', '2', '0', '01', '0'),
(1001, 'cont_eeff_bgen', 'Balance General', 'fa fa-check', 'Sin descripcion', '3', '1', '01', '0'),
(1002, 'cont_eeff_eres', 'Estado Resultados', 'fa fa-check', '', '3', '1', '01', '0'),
(1003, 'cont_eeff_fefe', 'Flujo Efectivo', 'fa fa-check', '', '3', '1', '01', '0'),
(1004, 'tcar_cser_prof', 'proforma', 'fa fa-check', '', '3', '0', '01', '0'),
(1005, 'tcar_cser_prof_vent', 'venta', 'fa fa-check', '', '4', '0', '01', '0'),
(1006, 'tcar_cser_prof_vent_ordn', 'orden', 'fa fa-check', '', '5', '0', '01', '0'),
(1007, 'tcar_cser_prof_vent_ordn_prim', 'primaria', 'fa fa-check', '', '6', '1', '01', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sysm_rolusua`
--

CREATE TABLE `sysm_rolusua` (
  `num_rol` int(11) NOT NULL,
  `cod_rol` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `des_rol` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `cod_categoria` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `cod_estado` char(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT '01',
  `ind_del` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sysm_rolusua`
--

INSERT INTO `sysm_rolusua` (`num_rol`, `cod_rol`, `des_rol`, `cod_categoria`, `cod_estado`, `ind_del`) VALUES
(1, 'admin', 'Administrador', 'Primero', '01', '0'),
(2, 'conta', 'Contador', 'Primero', '01', '0'),
(3, 'Asis', 'Asistente', 'Secundario', '01', '0'),
(4, 'Aux', 'Auxiliar', 'Secundario', '01', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sysm_usuario`
--

CREATE TABLE `sysm_usuario` (
  `num_usuario` int(11) NOT NULL,
  `cod_usuario` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `pas_usuario` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `dir_correo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `des_nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `num_documento` char(8) COLLATE utf8_spanish_ci NOT NULL,
  `num_empresa` int(11) NOT NULL,
  `num_rol` int(11) NOT NULL,
  `cod_estado` char(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT '01',
  `ind_del` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sysm_usuario`
--

INSERT INTO `sysm_usuario` (`num_usuario`, `cod_usuario`, `pas_usuario`, `dir_correo`, `des_nombre`, `num_documento`, `num_empresa`, `num_rol`, `cod_estado`, `ind_del`) VALUES
(1, 'usuario_maestro', 'internet', 'matikasrf@gmail.com', 'USUARIO', '11111110', 1, 1, '01', '0'),
(2, 'usuario_prueba', 'internet', 'usuario.prueba@gmail.com', 'USUARIO', '22222220', 3, 2, '01', '1'),
(3, 'usuario_prueba1', 'internet', 'armdf@gmail.com', 'Kelvin Arthas Menetil Son', '11111111', 2, 3, '01', '0'),
(4, 'usuario_prueba2', 'internet', 'aeeff@fasdf.com', 'salUSUinas', '22222222', 1, 3, '01', '0'),
(5, 'usuario_prueba3', 'internet', 'sricred@gmail.com', 'Anabel licatoma', '33333333', 3, 1, '01', '0'),
(6, 'usuario_prueba4', 'internet', 'amontalvan@gmail.com', 'antony Montalvan', '44444444', 4, 2, '01', '0'),
(7, 'usuario_prueba5', 'internet', 'calcvind@gmail.com', 'Westen union2', '55555555', 1, 3, '01', '1'),
(8, 'usuario_prueba6', 'internet', 'ausu@gmail.com', 'ALtarino Cultiro 22', '66666666', 2, 2, '01', '0'),
(9, 'usuario_prueba7', 'internet', 'cherman@gmail.com', 'CAsio Herman', '77777777', 3, 4, '01', '0'),
(10, 'usuario_prueba8', 'idak2idd320o', 'aguyama@gmail.com', 'Alison Guyama Red', '88888888', 3, 3, '01', '0'),
(11, 'marco_sifuente', '1234poir0', 'msifuente@gmail.com', 'Marco Antonio Sifuente', '11114444', 1, 1, '01', '0'),
(12, 'carlos_flores', 'internet', 'cflorez123_123@gmail.com', 'Carlos Florez Win', '11113333', 3, 1, '01', '0'),
(13, 'samuel_jara', 'internet1234124', 'sjarav@gmail.com', 'Samuel Jara Vergas', '11112222', 1, 1, '01', '0'),
(14, 'Sol_blas', 'dadddak', 'awdwdad@lkd.com', 'CLin raton', '21343232', 3, 1, '01', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `syst_asignamod`
--

CREATE TABLE `syst_asignamod` (
  `cod_usuario` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `cod_ruta` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `fec_asignacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ind_del` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `syst_asignamod`
--

INSERT INTO `syst_asignamod` (`cod_usuario`, `cod_ruta`, `fec_asignacion`, `ind_del`) VALUES
('usuario_maestro', 'admi_dash', '0000-00-00 00:00:00', '0'),
('usuario_maestro', 'cont_eeff_bgen', '0000-00-00 00:00:00', '0'),
('usuario_maestro', 'cont_eeff_eres', '0000-00-00 00:00:00', '0'),
('usuario_maestro', 'cont_eeff_fefe', '0000-00-00 00:00:00', '0'),
('usuario_maestro', 'cont_rven', '0000-00-00 00:00:00', '0'),
('usuario_maestro', 'syst_user', '2020-08-12 00:41:18', '0'),
('usuario_maestro', 'tcar_cser', '0000-00-00 00:00:00', '0'),
('usuario_maestro', 'tcar_cser_prof_vent_ordn_prim', '2020-07-14 21:16:36', '0');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `sysm_catalogo`
--
ALTER TABLE `sysm_catalogo`
  ADD PRIMARY KEY (`num_catalogo`),
  ADD UNIQUE KEY `cod_catalogo_un` (`cod_catalogo`) USING BTREE,
  ADD KEY `cod_catalogo_in` (`cod_catalogo`) USING BTREE;

--
-- Indices de la tabla `sysm_datacatalogo`
--
ALTER TABLE `sysm_datacatalogo`
  ADD PRIMARY KEY (`num_datacatalogo`),
  ADD UNIQUE KEY `cod_datacata_un` (`cod_catalogo`,`cod_datacata`),
  ADD KEY `num_catalogo_in` (`num_catalogo`) USING BTREE;

--
-- Indices de la tabla `sysm_empresa`
--
ALTER TABLE `sysm_empresa`
  ADD PRIMARY KEY (`num_empresa`),
  ADD UNIQUE KEY `ind_uni_empresa` (`cod_empresa`,`ruc_empresa`);

--
-- Indices de la tabla `sysm_navmenu`
--
ALTER TABLE `sysm_navmenu`
  ADD PRIMARY KEY (`num_menu`);

--
-- Indices de la tabla `sysm_rolusua`
--
ALTER TABLE `sysm_rolusua`
  ADD PRIMARY KEY (`num_rol`),
  ADD UNIQUE KEY `cod_rol` (`cod_rol`);

--
-- Indices de la tabla `sysm_usuario`
--
ALTER TABLE `sysm_usuario`
  ADD PRIMARY KEY (`num_usuario`),
  ADD UNIQUE KEY `cod_usuario` (`cod_usuario`),
  ADD UNIQUE KEY `num_documento` (`num_documento`),
  ADD KEY `num_empresa` (`num_empresa`),
  ADD KEY `num_rol` (`num_rol`);

--
-- Indices de la tabla `syst_asignamod`
--
ALTER TABLE `syst_asignamod`
  ADD UNIQUE KEY `cod_usuario_ruta` (`cod_usuario`,`cod_ruta`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `sysm_catalogo`
--
ALTER TABLE `sysm_catalogo`
  MODIFY `num_catalogo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sysm_datacatalogo`
--
ALTER TABLE `sysm_datacatalogo`
  MODIFY `num_datacatalogo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sysm_empresa`
--
ALTER TABLE `sysm_empresa`
  MODIFY `num_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sysm_navmenu`
--
ALTER TABLE `sysm_navmenu`
  MODIFY `num_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1008;

--
-- AUTO_INCREMENT de la tabla `sysm_rolusua`
--
ALTER TABLE `sysm_rolusua`
  MODIFY `num_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sysm_usuario`
--
ALTER TABLE `sysm_usuario`
  MODIFY `num_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `sysm_datacatalogo`
--
ALTER TABLE `sysm_datacatalogo`
  ADD CONSTRAINT `sysm_datacatalogo_ibfk_1` FOREIGN KEY (`num_catalogo`) REFERENCES `sysm_catalogo` (`num_catalogo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sysm_datacatalogo_ibfk_2` FOREIGN KEY (`cod_catalogo`) REFERENCES `sysm_catalogo` (`cod_catalogo`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `sysm_usuario`
--
ALTER TABLE `sysm_usuario`
  ADD CONSTRAINT `sysm_usuario_ibfk_1` FOREIGN KEY (`num_empresa`) REFERENCES `sysm_empresa` (`num_empresa`),
  ADD CONSTRAINT `sysm_usuario_ibfk_2` FOREIGN KEY (`num_rol`) REFERENCES `sysm_rolusua` (`num_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
