-- Estructura de las tablas Fase I
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ACTAS_PUNTOS`
--

CREATE TABLE `ACTAS_PUNTOS` (
  `FECHA` date NOT NULL,
  `CODPUN` int(8) NOT NULL DEFAULT '0',
  `PUNTO` varchar(12) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `TITULO` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ACTAS_TEXTOS`
--

CREATE TABLE `ACTAS_TEXTOS` (
  `FECHA` date NOT NULL,
  `CODPUN` int(8) NOT NULL DEFAULT '0',
  `CODAPA` int(8) NOT NULL DEFAULT '0',
  `APARTADO` varchar(12) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `SUBTITULO` text CHARACTER SET utf8 COLLATE utf8_spanish_ci,
  `TEXTO` text CHARACTER SET utf8 COLLATE utf8_spanish_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ADMINISTRACIONES`
--

CREATE TABLE `ADMINISTRACIONES` (
  `CODADM` int(5) NOT NULL DEFAULT '0',
  `NOMBRE` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `VIA` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT '',
  `DIRECCION` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT '',
  `CP` varchar(5) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT '',
  `LOCALIDAD` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT '',
  `TELEFONO` varchar(9) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT '',
  `MOVIL` varchar(9) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT '',
  `FAX` varchar(9) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT '',
  `EMAIL` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT '',
  `ADMINISTRADOR` int(5) DEFAULT '0',
  `ACTIVA` enum('','N','S') CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT '',
  `NOTAS` text CHARACTER SET utf8 COLLATE utf8_spanish_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `APARTAMENTOS`
--

CREATE TABLE `APARTAMENTOS` (
  `CODAPAR` int(3) UNSIGNED NOT NULL,
  `PORTAL` int(2) UNSIGNED NOT NULL,
  `PISO` varchar(1) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `LETRA` varchar(2) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `TIPO` varchar(2) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT '',
  `METROS` decimal(5,2) UNSIGNED DEFAULT '0.00',
  `TERRAZA` decimal(5,2) UNSIGNED ZEROFILL DEFAULT '000.00',
  `COEFICIENTEFASE` decimal(5,4) UNSIGNED ZEROFILL DEFAULT '0.0000',
  `COEFICIENTEBLOQ` decimal(5,2) UNSIGNED DEFAULT '0.00',
  `FINCA` int(3) UNSIGNED NOT NULL,
  `REGISTRO` int(5) UNSIGNED DEFAULT '0',
  `REFCATASTRAL` varchar(20) DEFAULT '',
  `METROSCATASTRO` decimal(5,2) UNSIGNED DEFAULT '0.00',
  `COEFICIENTE` decimal(5,4) UNSIGNED ZEROFILL DEFAULT '0.0000'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Datos de los apartamentos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ASISTENTES`
--

CREATE TABLE `ASISTENTES` (
  `FECHA` date NOT NULL,
  `CODAPAR` int(3) NOT NULL,
  `CODPERS` int(5) NOT NULL,
  `REPRESENTADO` enum('N','S') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  `VOTO` enum('N','S') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 'S'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DEUDAS`
--

CREATE TABLE `DEUDAS` (
  `FECHA` date NOT NULL,
  `CODAPAR` int(11) NOT NULL,
  `ORDINARIA` decimal(8,2) NOT NULL DEFAULT '0.00',
  `EXTRAORDINARIA` decimal(8,2) NOT NULL DEFAULT '0.00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `GARAJES`
--

CREATE TABLE `GARAJES` (
  `CODGAR` int(11) NOT NULL,
  `CODAPAR` int(3) NOT NULL,
  `BAJA` date DEFAULT NULL,
  `NOTAS` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `JUNTAS`
--

CREATE TABLE `JUNTAS` (
  `FECHA` date NOT NULL,
  `TIPO` enum('','O','E','I','G') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `CONVOCATORIA` enum('','1','2') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `HORA` time DEFAULT '00:00:00',
  `PRESIDENTE` int(5) DEFAULT '0',
  `VICEPRESIDENTE1` int(5) DEFAULT '0',
  `VICEPRESIDENTE2` int(5) DEFAULT '0',
  `VOCAL1` int(5) DEFAULT '0',
  `VOCAL2` int(5) DEFAULT '0',
  `VOCAL3` int(5) DEFAULT '0',
  `VOCAL4` int(5) DEFAULT '0',
  `SECRETARIO` int(5) DEFAULT '0',
  `ADMINISTRACION` int(5) DEFAULT '0',
  `NOTAS` text CHARACTER SET utf8 COLLATE utf8_spanish_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PERSONAS`
--

CREATE TABLE `PERSONAS` (
  `CODPERS` int(5) UNSIGNED NOT NULL COMMENT 'Datos de las personas',
  `APELLIDOS` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `NOMBRE` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT '',
  `SEXO` enum('H','M','') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `CODUSU` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT '',
  `CORREO` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT '',
  `ENVIOS` enum('N','S') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  `TELEFONO` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT '',
  `NOTAS` text CHARACTER SET utf8 COLLATE utf8_spanish_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Datos de los propietarios y representantes.';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PROPIETARIOS`
--

CREATE TABLE `PROPIETARIOS` (
  `CODAPAR` int(3) UNSIGNED NOT NULL,
  `CODPERS` int(5) UNSIGNED NOT NULL,
  `BAJA` date DEFAULT '0000-00-00',
  `ORDEN` int(2) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Guarda los propietarios de los apartamentos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VOTACIONES`
--

CREATE TABLE `VOTACIONES` (
  `FECHA` date NOT NULL,
  `NUMVOT` int(3) NOT NULL,
  `TEXTO` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `OPCION1` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `OPCION2` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `OPCION3` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `OPCION4` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VOTOS`
--

CREATE TABLE `VOTOS` (
  `FECHA` date NOT NULL,
  `NUMVOT` int(3) NOT NULL,
  `CODAPAR` int(3) NOT NULL,
  `ASISTE` enum('N','S') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  `VOTA` enum('N','S') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  `PRESENTE` enum('N','S') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  `RESULTADO1` enum('N','S') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  `RESULTADO2` enum('N','S') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  `RESULTADO3` enum('N','S') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  `RESULTADO4` enum('N','S') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ACTAS_PUNTOS`
--
ALTER TABLE `ACTAS_PUNTOS`
  ADD PRIMARY KEY (`FECHA`,`CODPUN`),
  ADD KEY `PUNTO` (`PUNTO`);
ALTER TABLE `ACTAS_PUNTOS` ADD FULLTEXT KEY `TITULO` (`TITULO`);

--
-- Indices de la tabla `ACTAS_TEXTOS`
--
ALTER TABLE `ACTAS_TEXTOS`
  ADD PRIMARY KEY (`FECHA`,`CODPUN`,`CODAPA`),
  ADD KEY `APARTADO` (`APARTADO`);
ALTER TABLE `ACTAS_TEXTOS` ADD FULLTEXT KEY `TEXTO` (`TEXTO`,`SUBTITULO`);

--
-- Indices de la tabla `ADMINISTRACIONES`
--
ALTER TABLE `ADMINISTRACIONES`
  ADD PRIMARY KEY (`CODADM`);

--
-- Indices de la tabla `APARTAMENTOS`
--
ALTER TABLE `APARTAMENTOS`
  ADD PRIMARY KEY (`CODAPAR`),
  ADD UNIQUE KEY `FINCA_UNIQUE` (`FINCA`),
  ADD KEY `Apartamento` (`PORTAL`,`PISO`,`LETRA`);

--
-- Indices de la tabla `ASISTENTES`
--
ALTER TABLE `ASISTENTES`
  ADD PRIMARY KEY (`FECHA`,`CODAPAR`);

--
-- Indices de la tabla `DEUDAS`
--
ALTER TABLE `DEUDAS`
  ADD PRIMARY KEY (`FECHA`,`CODAPAR`);

--
-- Indices de la tabla `GARAJES`
--
ALTER TABLE `GARAJES`
  ADD PRIMARY KEY (`CODGAR`,`CODAPAR`);

--
-- Indices de la tabla `JUNTAS`
--
ALTER TABLE `JUNTAS`
  ADD PRIMARY KEY (`FECHA`);

--
-- Indices de la tabla `PERSONAS`
--
ALTER TABLE `PERSONAS`
  ADD PRIMARY KEY (`CODPERS`),
  ADD KEY `Persona` (`APELLIDOS`,`NOMBRE`);

--
-- Indices de la tabla `PROPIETARIOS`
--
ALTER TABLE `PROPIETARIOS`
  ADD PRIMARY KEY (`CODAPAR`,`CODPERS`);

--
-- Indices de la tabla `VOTACIONES`
--
ALTER TABLE `VOTACIONES`
  ADD PRIMARY KEY (`FECHA`,`NUMVOT`);

--
-- Indices de la tabla `VOTOS`
--
ALTER TABLE `VOTOS`
  ADD PRIMARY KEY (`FECHA`,`NUMVOT`,`CODAPAR`);
COMMIT;


