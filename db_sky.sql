-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-06-2021 a las 18:43:46
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
-- Estructura de tabla para la tabla `cont_cpe`
--

CREATE TABLE `cont_cpe` (
  `num_ruc` char(11) COLLATE utf8_spanish_ci NOT NULL,
  `cod_cpe` char(2) COLLATE utf8_spanish_ci NOT NULL,
  `num_serie` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `num_cpe` int(11) NOT NULL,
  `fec_emision` date NOT NULL,
  `cod_tipdocrec` char(2) COLLATE utf8_spanish_ci NOT NULL,
  `num_docrecep` char(16) COLLATE utf8_spanish_ci NOT NULL,
  `des_nomrecep` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `cod_moneda` char(3) COLLATE utf8_spanish_ci NOT NULL,
  `mto_tipocambio` decimal(3,2) NOT NULL,
  `mto_totalvta` decimal(15,2) NOT NULL DEFAULT '0.00',
  `mto_totaligv` decimal(15,2) NOT NULL DEFAULT '0.00',
  `mto_imptotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `des_observa` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `num_xml` int(11) DEFAULT NULL,
  `ind_estado` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `cod_usumod` char(20) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'noUser',
  `fec_modif` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cont_cpe`
--

INSERT INTO `cont_cpe` (`num_ruc`, `cod_cpe`, `num_serie`, `num_cpe`, `fec_emision`, `cod_tipdocrec`, `num_docrecep`, `des_nomrecep`, `cod_moneda`, `mto_tipocambio`, `mto_totalvta`, `mto_totaligv`, `mto_imptotal`, `des_observa`, `num_xml`, `ind_estado`, `cod_usumod`, `fec_modif`) VALUES
('20601344557', '00', 'N001', 1, '2021-06-11', '06', '11111111111', 'Empresa prueba 1', 'PEN', '0.00', '1100.00', '0.00', '1100.00', 'sin observaciones', 0, '0', 'noUser', '2021-06-13 13:09:26'),
('20601344557', '00', 'N001', 2, '2021-06-13', '06', '22222222222', 'Empresa prueba 2', 'PEN', '0.00', '1200.00', '0.00', '1200.00', 'sin observaciones', NULL, '0', 'noUser', '2021-06-13 10:30:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cont_cpedata`
--

CREATE TABLE `cont_cpedata` (
  `num_ruc` char(11) COLLATE utf8_spanish_ci NOT NULL,
  `cod_cpe` char(2) COLLATE utf8_spanish_ci NOT NULL,
  `num_serie` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `num_cpe` int(11) NOT NULL,
  `num_item` int(11) NOT NULL,
  `cod_rubro` char(2) COLLATE utf8_spanish_ci NOT NULL,
  `mto_rubro` decimal(15,2) DEFAULT NULL,
  `des_rubro` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `cod_usureg` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fec_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cont_cpedata`
--

INSERT INTO `cont_cpedata` (`num_ruc`, `cod_cpe`, `num_serie`, `num_cpe`, `num_item`, `cod_rubro`, `mto_rubro`, `des_rubro`, `cod_usureg`, `fec_registro`) VALUES
('20601344557', '00', 'N001', 1, 0, '01', NULL, 'Av universitaria 3418 los ceros 104 ', '', '2021-06-13 10:30:36'),
('20601344557', '00', 'N001', 1, 0, '31', '1200.00', '', '', '2021-06-17 23:57:40'),
('20601344557', '00', 'N001', 1, 1, '80', NULL, '1', '', '2021-06-18 00:02:07'),
('20601344557', '00', 'N001', 1, 1, '81', '12.00', '', '', '2021-06-18 00:04:22'),
('20601344557', '00', 'N001', 1, 1, '82', NULL, 'UND', '', '2021-06-18 00:05:59'),
('20601344557', '00', 'N001', 1, 1, '83', NULL, 'P0123', '', '2021-06-18 00:08:26'),
('20601344557', '00', 'N001', 1, 1, '84', NULL, 'Calamina Galv 022 x 360', '', '2021-06-18 00:11:03'),
('20601344557', '00', 'N001', 1, 1, '85', '100.00', '', '', '2021-06-18 00:08:26'),
('20601344557', '00', 'N001', 1, 1, '99', '1200.00', '', '', '2021-06-18 00:09:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cont_docrubro`
--

CREATE TABLE `cont_docrubro` (
  `cod_docrubro` char(2) COLLATE utf8_spanish_ci NOT NULL,
  `des_docrubro` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `des_rubros` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `cod_usureg` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fec_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cont_docrubro`
--

INSERT INTO `cont_docrubro` (`cod_docrubro`, `des_docrubro`, `des_rubros`, `cod_usureg`, `fec_registro`) VALUES
('00', 'Nota Pedido', '01,31,80,81,82,83,84,85,99', 'batch', '2021-06-17 23:51:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sysm_catalogo`
--

CREATE TABLE `sysm_catalogo` (
  `cod_catalogo` char(2) COLLATE utf8_spanish_ci NOT NULL,
  `des_catalogo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `des_acronimo` char(10) COLLATE utf8_spanish_ci NOT NULL,
  `cod_usureg` char(20) COLLATE utf8_spanish_ci NOT NULL,
  `fec_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sysm_catalogo`
--

INSERT INTO `sysm_catalogo` (`cod_catalogo`, `des_catalogo`, `des_acronimo`, `cod_usureg`, `fec_registro`) VALUES
('A0', 'Estado de Registro', 'EST', 'batch', '2021-06-17 13:12:52'),
('A1', 'Catalogo Rubros CP', 'RUBR', 'batch', '2021-06-17 10:42:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sysm_datacat`
--

CREATE TABLE `sysm_datacat` (
  `cod_catalogo` char(2) COLLATE utf8_spanish_ci NOT NULL,
  `cod_param` char(2) COLLATE utf8_spanish_ci NOT NULL,
  `des_param` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `des_larga` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `cod_usureg` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fec_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sysm_datacat`
--

INSERT INTO `sysm_datacat` (`cod_catalogo`, `cod_param`, `des_param`, `des_larga`, `cod_usureg`, `fec_registro`) VALUES
('A1', '00', 'Datos Adicionales', 'Datos Adicionales del cliente', 'batch', '2021-06-17 12:38:39'),
('A1', '01', 'Direccion Cliente', 'Dirección del clientes o Domicilio fiscal', 'batch', '2021-06-17 10:45:58'),
('A1', '02', 'Nro Final', 'Nro Final (Rango CP)', 'batch', '2021-06-17 10:45:58'),
('A1', '11', 'Ind Exporta', 'Indicador Factura de Exportación', 'batch', '2021-06-17 11:14:29'),
('A1', '12', 'Ind Ancticipado', 'Indicador Pago Anticipado Factura', 'batch', '2021-06-17 11:14:29'),
('A1', '13', 'Ind Itinerante', 'Indicador Emisor Itinerante', 'batch', '2021-06-17 11:15:05'),
('A1', '14', 'Ind Combustible', 'Indicador Venta conbustible y/o mantenimiento vehicular ', 'batch', '2021-06-17 11:15:05'),
('A1', '21', 'Sub Total Ventas', 'Sub Total Ventas', 'batch', '2021-06-17 12:24:08'),
('A1', '22', 'Anticipos', 'Anticipos', 'batch', '2021-06-17 12:24:49'),
('A1', '23', 'Descuentos', 'Descuentos', 'batch', '2021-06-17 12:24:49'),
('A1', '24', 'Valor de Venta', 'Valor de Venta', 'batch', '2021-06-17 12:24:49'),
('A1', '25', 'ISC', 'ISC', 'batch', '2021-06-17 12:24:49'),
('A1', '26', 'IGV', 'IGV', 'batch', '2021-06-17 12:24:49'),
('A1', '27', 'ICBPER', 'ICBPER', 'batch', '2021-06-17 12:24:49'),
('A1', '28', 'Otros Cargos', 'Otros Cargos', 'batch', '2021-06-17 12:24:49'),
('A1', '29', 'Otros Tributos', 'Otros Tributos', 'batch', '2021-06-17 12:24:50'),
('A1', '30', 'Monto de Redondeo', 'Monto de Redondeo', 'batch', '2021-06-17 12:24:50'),
('A1', '31', 'Importe Total', 'Importe Total', 'batch', '2021-06-17 12:24:50'),
('A1', '70', 'Ind Anticipo', 'Indicador Operación Anticipo', 'batch', '2021-06-17 12:24:50'),
('A1', '71', 'FA Serie', 'Factura Anticipada Serie', 'batch', '2021-06-17 12:24:50'),
('A1', '72', 'FA Numero', 'Factura Anticipada Numero', 'batch', '2021-06-17 12:24:50'),
('A1', '73', 'FA Fecha Emisión', 'Factura Ant. Fecha Emisión', 'batch', '2021-06-17 12:24:50'),
('A1', '74', 'FA Importe Total', 'Factura Ant. Importe Total', 'batch', '2021-06-17 12:24:50'),
('A1', '75', 'FA Descripción', 'Factura Ant descripción', 'batch', '2021-06-17 12:24:50'),
('A1', '76', 'Valor Anticipo', 'Valor Anticipo', 'batch', '2021-06-17 12:24:50'),
('A1', '79', 'Ind Gratuita', 'Indicador Operación Gratuita', 'batch', '2021-06-17 12:24:50'),
('A1', '80', 'Tipo Ítem', 'Tipo Ítem (Bien/Servicio/Cargo/Tributo)', 'batch', '2021-06-17 12:24:50'),
('A1', '81', 'Cantidad', 'Cantidad', 'batch', '2021-06-17 12:24:50'),
('A1', '82', 'Unidad Medida', 'Unidad Medida', 'batch', '2021-06-17 12:24:50'),
('A1', '83', 'Código', 'Código', 'batch', '2021-06-17 12:24:50'),
('A1', '84', 'descripción', 'descripción', 'batch', '2021-06-17 12:24:50'),
('A1', '85', 'Valor Unitario', 'Valor Unitario', 'batch', '2021-06-17 12:24:50'),
('A1', '86', 'Descuento', 'Descuento', 'batch', '2021-06-17 12:24:50'),
('A1', '87', 'Ind Ad Valorem', 'Concepto ad Valorem', 'batch', '2021-06-17 12:24:50'),
('A1', '88', 'Tasa Ad Valorem', 'Tasa % Ad Valorem', 'batch', '2021-06-17 12:24:50'),
('A1', '89', 'Monto Ad Valorem', 'Monto Ad Valorem', 'batch', '2021-06-17 12:24:50'),
('A1', '90', 'Concepto ISC', 'Concepto ISC', 'batch', '2021-06-17 12:24:50'),
('A1', '91', 'Tasa ISC', 'Tasa % ISC', 'batch', '2021-06-17 12:24:50'),
('A1', '92', 'Monto ISC', 'Monto ISC', 'batch', '2021-06-17 12:24:50'),
('A1', '93', 'Afectación', 'Afectación (Grav/Exo/Inafec)', 'batch', '2021-06-17 12:24:50'),
('A1', '94', 'IGV', 'IGV (18.0%)', 'batch', '2021-06-17 12:24:50'),
('A1', '95', 'Monto IGV', 'Monto IGV', 'batch', '2021-06-17 12:29:28'),
('A1', '96', 'ICBPER Año', 'ICBPER Año', 'batch', '2021-06-17 12:29:28'),
('A1', '97', 'Tasa ICBPER', 'Tasa ICBPER', 'batch', '2021-06-17 12:29:28'),
('A1', '98', 'Monto ICBPER', 'Monto ICBPER', 'batch', '2021-06-17 12:29:28'),
('A1', '99', 'Importe de Venta', 'Importe de Item', 'batch', '2021-06-17 12:29:28');

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
(1, 'sin_empresa', '00000000000', '_Sin Empresa', 'Av Sin Direccion', '01', '0'),
(2, 'empresa_prueba1', '11111111111', 'Empresa Prueba 01', 'Av Pruebas 01', '02', '0'),
(3, 'empresa_prueba2', '22222222222', 'Empresa Prueba 02', 'Av Pruebas 02', '01', '0'),
(4, 'empresa_prueba3', '33333333333', 'Empresa Prueba 03', 'Av Pruebas 03', '01', '0'),
(5, 'abascom_matias', '20601344557', 'ABASTECEDORA Y COMERCIALIZADORA MATIAS E.I.R.L.', 'AV UNIVERSITARIA #3418 URB CAYHUAYNA - HUANUCO', '01', '0');

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
(105, 'cont_comp', 'Comprobantes de Pago', 'fa fa-plus-square', 'Modulos de comprobantes de pago', '2', '0', '01', '0'),
(1001, 'cont_eeff_bgen', 'Balance General', 'fa fa-check', 'Sin descripcion', '3', '1', '01', '0'),
(1002, 'cont_eeff_eres', 'Estado Resultados', 'fa fa-check', '', '3', '1', '01', '0'),
(1003, 'cont_eeff_fefe', 'Flujo Efectivo', 'fa fa-check', '', '3', '1', '01', '0'),
(1004, 'tcar_cser_prof', 'proforma', 'fa fa-check', '', '3', '0', '01', '0'),
(1005, 'cont_comp_nped', 'Nota de Pedido', 'fa fa-check', 'Modulo de Nota de Pedido', '3', '1', '01', '0'),
(1501, 'tcar_cser_prof_vent', 'venta', 'fa fa-check', '', '4', '0', '01', '0'),
(1701, 'tcar_cser_prof_vent_ordn', 'orden', 'fa fa-check', '', '5', '0', '01', '0'),
(1901, 'tcar_cser_prof_vent_ordn_prim', 'primaria', 'fa fa-check', '', '6', '1', '01', '0');

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
  `cod_supervisor` char(20) COLLATE utf8_spanish_ci NOT NULL,
  `cod_estado` char(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT '01',
  `ind_del` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sysm_usuario`
--

INSERT INTO `sysm_usuario` (`num_usuario`, `cod_usuario`, `pas_usuario`, `dir_correo`, `des_nombre`, `num_documento`, `num_empresa`, `num_rol`, `cod_supervisor`, `cod_estado`, `ind_del`) VALUES
(1, 'usuario_maestro', 'internet', 'matikasrf@gmail.com', 'USUARIO', '11111110', 1, 1, '', '01', '0'),
(2, 'usuario_prueba', 'internet', 'usuario.prueba@gmail.com', 'USUARIO', '22222220', 3, 2, '', '01', '1'),
(3, 'usuario_prueba1', 'internet', 'armdf@gmail.com', 'Kelvin Arthas Menetil Son', '11111111', 2, 3, '', '01', '0'),
(4, 'usuario_prueba2', 'internet', 'aeeff@fasdf.com', 'salUSUinas', '22222222', 1, 3, '', '01', '0'),
(5, 'usuario_prueba3', 'internet', 'sricred@gmail.com', 'Anabel licatoma 32', '33333333', 3, 1, '', '01', '0'),
(6, 'usuario_prueba4', 'internet', 'amontalvan@gmail.com', 'antony Montalvan 4', '44444444', 4, 2, '', '01', '0'),
(7, 'usuario_prueba5', 'internet', 'calcvind@gmail.com', 'Westen union2', '55555555', 1, 3, '', '01', '1'),
(8, 'usuario_prueba6', 'internet', 'ausu@gmail.com', 'ALtarino Cultiro 123', '66666666', 2, 2, '', '02', '0'),
(9, 'usuario_prueba7', 'internet', 'cherman@gmail.com', 'CAsio Herman', '77777777', 3, 4, '', '01', '0'),
(10, 'usuario_prueba8', '12345678', 'aguyama@gmail.com', 'Alison Guyama Red 8', '88888888', 3, 3, '', '01', '0'),
(11, 'marco_sifuente', '1234poir0', 'msifuente@gmail.com', 'Marco Antonio Sifuente', '11114444', 1, 1, '', '01', '0'),
(12, 'carlos_flores', 'internet', 'cflorez123_123@gmail.com', 'Carlos Florez Win', '11113333', 3, 1, '', '01', '0'),
(13, 'tomy_matias', 'internet', 'tmatias@gmail.com', 'Tomy Matias Rivera', '77889900', 5, 1, '', '01', '0'),
(14, 'Sol_blas', 'dadddak', 'awdwdad@lkd.com', 'CLin raton', '21343232', 3, 1, '', '01', '0'),
(15, 'patriciostar', '12e12e', 'trapri@fad.com', 'patricio Etrella', '12334324', 4, 1, '', '01', '0');

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
('usuario_maestro', 'cont_comp_nped', '2021-06-03 10:25:40', '0'),
('usuario_maestro', 'cont_eeff_bgen', '0000-00-00 00:00:00', '0'),
('usuario_maestro', 'cont_eeff_eres', '0000-00-00 00:00:00', '0'),
('usuario_maestro', 'cont_eeff_fefe', '0000-00-00 00:00:00', '0'),
('usuario_maestro', 'cont_rven', '0000-00-00 00:00:00', '0'),
('usuario_maestro', 'syst_user', '2020-08-12 00:41:18', '0'),
('usuario_maestro', 'tcar_cser', '0000-00-00 00:00:00', '0'),
('usuario_maestro', 'tcar_cser_prof_vent_ordn_prim', '2020-07-14 21:16:36', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vmat_inventario`
--

CREATE TABLE `vmat_inventario` (
  `num_inventario` int(11) NOT NULL,
  `cod_producto` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `cnt_stock` int(11) NOT NULL DEFAULT '0',
  `cnt_preciocompra` decimal(12,2) NOT NULL DEFAULT '0.00',
  `cnt_precioventa` decimal(12,2) NOT NULL DEFAULT '0.00',
  `cod_proveedor` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `cod_estado` char(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT '01',
  `ind_del` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `vmat_inventario`
--

INSERT INTO `vmat_inventario` (`num_inventario`, `cod_producto`, `cnt_stock`, `cnt_preciocompra`, `cnt_precioventa`, `cod_proveedor`, `cod_estado`, `ind_del`) VALUES
(1, '0001', 1200, '25.50', '27.80', '01', '01', '0'),
(2, '0002', 1800, '14.50', '16.80', '01', '01', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vmat_producto`
--

CREATE TABLE `vmat_producto` (
  `num_produco` int(11) NOT NULL,
  `cod_producto` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `des_producto` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `des_larga` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `cod_categoria` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `cod_tipo` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `cod_familia` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `cod_marca` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `num_medidas` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `cnt_peso` decimal(6,2) NOT NULL DEFAULT '0.00',
  `ind_del` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `vmat_producto`
--

INSERT INTO `vmat_producto` (`num_produco`, `cod_producto`, `des_producto`, `des_larga`, `cod_categoria`, `cod_tipo`, `cod_familia`, `cod_marca`, `num_medidas`, `cnt_peso`, `ind_del`) VALUES
(1, '0001', 'Fierro 1/2 AA', 'Fierro Corrugado 1/2 Aceros Arequipa x 9mts', '01', '01', '01', '01', '1/2 x 9 mts', '5.80', '0'),
(2, '0002', 'Fierro 3/8 AA', 'Fierro Corrugado 3/8 Aceros Arequipa x 9mts', '01', '01', '01', '01', '3/8 x 9 mts', '4.10', '0');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cont_cpe`
--
ALTER TABLE `cont_cpe`
  ADD UNIQUE KEY `pk_cpe` (`num_ruc`,`cod_cpe`,`num_serie`,`num_cpe`);

--
-- Indices de la tabla `cont_cpedata`
--
ALTER TABLE `cont_cpedata`
  ADD UNIQUE KEY `pk_rubrocpe` (`num_ruc`,`cod_cpe`,`num_serie`,`num_cpe`,`num_item`,`cod_rubro`);

--
-- Indices de la tabla `cont_docrubro`
--
ALTER TABLE `cont_docrubro`
  ADD PRIMARY KEY (`cod_docrubro`);

--
-- Indices de la tabla `sysm_catalogo`
--
ALTER TABLE `sysm_catalogo`
  ADD PRIMARY KEY (`cod_catalogo`);

--
-- Indices de la tabla `sysm_datacat`
--
ALTER TABLE `sysm_datacat`
  ADD UNIQUE KEY `pk_datacat` (`cod_catalogo`,`cod_param`);

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
-- Indices de la tabla `vmat_inventario`
--
ALTER TABLE `vmat_inventario`
  ADD PRIMARY KEY (`num_inventario`);

--
-- Indices de la tabla `vmat_producto`
--
ALTER TABLE `vmat_producto`
  ADD PRIMARY KEY (`num_produco`),
  ADD UNIQUE KEY `cod_producto` (`cod_producto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `sysm_empresa`
--
ALTER TABLE `sysm_empresa`
  MODIFY `num_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `sysm_navmenu`
--
ALTER TABLE `sysm_navmenu`
  MODIFY `num_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1902;

--
-- AUTO_INCREMENT de la tabla `sysm_rolusua`
--
ALTER TABLE `sysm_rolusua`
  MODIFY `num_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sysm_usuario`
--
ALTER TABLE `sysm_usuario`
  MODIFY `num_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `vmat_inventario`
--
ALTER TABLE `vmat_inventario`
  MODIFY `num_inventario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `vmat_producto`
--
ALTER TABLE `vmat_producto`
  MODIFY `num_produco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

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
