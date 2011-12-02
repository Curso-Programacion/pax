-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 02-12-2011 a las 09:33:46
-- Versión del servidor: 5.1.41
-- Versión de PHP: 5.3.2-1ubuntu4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `paxDemo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
  `id` int(11) NOT NULL DEFAULT '0',
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', ''),
(2, 'Usuario', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil_permiso`
--

CREATE TABLE IF NOT EXISTS `perfil_permiso` (
  `id_perfil` int(11) DEFAULT NULL,
  `id_permiso` int(11) DEFAULT NULL,
  KEY `id_perfil` (`id_perfil`),
  KEY `id_permiso` (`id_permiso`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `perfil_permiso`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE IF NOT EXISTS `permiso` (
  `id` int(11) NOT NULL DEFAULT '0',
  `modulo` varchar(100) DEFAULT NULL,
  `accion` varchar(100) DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id`, `modulo`, `accion`, `descripcion`) VALUES
(1, 'usuario', 'buscar', ''),
(2, 'usuario', 'cambiarClave', ''),
(3, 'usuario', 'cambiarPermiso', ''),
(4, 'usuario', 'crear', ''),
(5, 'usuario', 'eliminar', ''),
(6, 'usuario', 'login', ''),
(7, 'usuario', 'modificar', ''),
(8, 'usuario', 'recuperarClave', ''),
(9, 'usuario', 'salir', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `id_perfil` int(11) DEFAULT NULL,
  `fallosAcceso` int(11) DEFAULT '0',
  `correo` varchar(100) DEFAULT NULL,
  `fechaBajaRegistro` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fechaAltaClave` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaUltimoAcceso` datetime NOT NULL,
  `id_zona` int(11) DEFAULT '-1',
  `siglas` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=89 ;

--
-- Volcar la base de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `usuario`, `clave`, `nombre`, `apellidos`, `id_perfil`, `fallosAcceso`, `correo`, `fechaBajaRegistro`, `fechaAltaClave`, `fechaUltimoAcceso`, `id_zona`, `siglas`) VALUES
(1, 'pax', '991df245b7aa31f83f359cdb0a32e242', 'PAX', 'Demo ', 2, 0, 'info@ilkebenson.com', '0000-00-00 00:00:00', '2010-12-29 10:24:39', '2011-01-09 17:55:49', 9, 'PD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_permiso`
--

CREATE TABLE IF NOT EXISTS `usuario_permiso` (
  `id_usuario` int(11) DEFAULT NULL,
  `id_permiso` int(11) DEFAULT NULL,
  KEY `id_usuario` (`id_usuario`),
  KEY `id_permiso` (`id_permiso`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `usuario_permiso`
--

