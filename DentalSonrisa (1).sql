-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 26-06-2025 a las 13:12:10
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
-- Base de datos: `DentalSonrisa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Atencion`
--

CREATE TABLE `Atencion` (
  `ID_Atencion` int(11) NOT NULL,
  `RUT_Paciente` varchar(12) NOT NULL,
  `ID_Tratamiento` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `ID_FormaPago` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `Atencion`
--

INSERT INTO `Atencion` (`ID_Atencion`, `RUT_Paciente`, `ID_Tratamiento`, `Fecha`, `ID_FormaPago`) VALUES
(3, '34.567.890-1', 3, '2023-03-10', 3),
(4, '45.678.901-2', 4, '2023-04-05', 4),
(6, '21934678-1', 5, '2025-06-27', 6),
(8, '45.678.901-2', 4, '2025-06-28', 6),
(9, '21934678-1', 6, '2025-06-28', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Especialista`
--

CREATE TABLE `Especialista` (
  `ID_Especialista` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `Especialista`
--

INSERT INTO `Especialista` (`ID_Especialista`, `Nombre`) VALUES
(1, 'Dra. María González'),
(3, 'Dr. Juan Lopez'),
(4, 'Dra. Ana Martínez'),
(5, 'Dr. Pedro Sanchez'),
(7, 'flavio'),
(8, 'flavio'),
(9, 'flavio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `FormaPago`
--

CREATE TABLE `FormaPago` (
  `ID_FormaPago` int(11) NOT NULL,
  `Descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `FormaPago`
--

INSERT INTO `FormaPago` (`ID_FormaPago`, `Descripcion`) VALUES
(2, 'Tarjeta de Credito'),
(3, 'Tarjeta de Debito'),
(4, 'Transferencia Bancaria'),
(5, 'Cheque'),
(6, 'Efectivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Paciente`
--

CREATE TABLE `Paciente` (
  `RUT` varchar(12) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `Paciente`
--

INSERT INTO `Paciente` (`RUT`, `Nombre`, `Email`) VALUES
('12.345.678-9', 'Laura Fernández', 'laura@email.com'),
('123123123', 'flavio', 'fasd@gmail.com'),
('213861932', 'Nicolas', 'nicolas@gmail.com'),
('21386910-8', 'ramiro', 'prueba@gmail.com'),
('213869108', 'Ramiro', 'ramiro@gmail.com'),
('21934678-1', 'Felipe', 'felipe@gmail.com'),
('23.456.789-0', 'Miguel Rodríguez', 'miguel@email.com'),
('34.567.890-1', 'Sofía García', 'sofia@email.com'),
('45.678.901-2', 'David Alex', 'david@gmail.com'),
('56.789.012-3', 'Elena Castro', 'elena@email.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Tratamiento`
--

CREATE TABLE `Tratamiento` (
  `ID_Tratamiento` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Valor` decimal(10,2) NOT NULL,
  `ID_Especialista` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `Tratamiento`
--

INSERT INTO `Tratamiento` (`ID_Tratamiento`, `Nombre`, `Valor`, `ID_Especialista`) VALUES
(1, 'Limpieza Dental', 25000.00, 1),
(3, 'Ortodoncia', 800000.00, 3),
(4, 'Extraccion', 35000.00, 4),
(5, 'Implante Dental', 450000.00, 5),
(6, 'Limpieza dental', 10000.00, 3);

--
-- Disparadores `Tratamiento`
--
DELIMITER $$
CREATE TRIGGER `after_tratamiento_delete` AFTER DELETE ON `Tratamiento` FOR EACH ROW BEGIN
    INSERT INTO Tratamiento_Auditoria (ID_Tratamiento, Nombre, Accion)
    VALUES (OLD.ID_Tratamiento, OLD.Nombre, 'DELETE');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Tratamiento_Auditoria`
--

CREATE TABLE `Tratamiento_Auditoria` (
  `ID` int(11) NOT NULL,
  `ID_Tratamiento` int(11) DEFAULT NULL,
  `Nombre` varchar(255) DEFAULT NULL,
  `Fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `Accion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `Tratamiento_Auditoria`
--

INSERT INTO `Tratamiento_Auditoria` (`ID`, `ID_Tratamiento`, `Nombre`, `Fecha`, `Accion`) VALUES
(1, 7, 'flavio', '2025-06-26 11:10:12', 'DELETE');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Atencion`
--
ALTER TABLE `Atencion`
  ADD PRIMARY KEY (`ID_Atencion`),
  ADD KEY `Atencion_ibfk_1` (`RUT_Paciente`),
  ADD KEY `Atencion_ibfk_2` (`ID_Tratamiento`),
  ADD KEY `Atencion_ibfk_3` (`ID_FormaPago`);

--
-- Indices de la tabla `Especialista`
--
ALTER TABLE `Especialista`
  ADD PRIMARY KEY (`ID_Especialista`);

--
-- Indices de la tabla `FormaPago`
--
ALTER TABLE `FormaPago`
  ADD PRIMARY KEY (`ID_FormaPago`);

--
-- Indices de la tabla `Paciente`
--
ALTER TABLE `Paciente`
  ADD PRIMARY KEY (`RUT`);

--
-- Indices de la tabla `Tratamiento`
--
ALTER TABLE `Tratamiento`
  ADD PRIMARY KEY (`ID_Tratamiento`),
  ADD KEY `Tratamiento_ibfk_1` (`ID_Especialista`);

--
-- Indices de la tabla `Tratamiento_Auditoria`
--
ALTER TABLE `Tratamiento_Auditoria`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Atencion`
--
ALTER TABLE `Atencion`
  MODIFY `ID_Atencion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `Especialista`
--
ALTER TABLE `Especialista`
  MODIFY `ID_Especialista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `FormaPago`
--
ALTER TABLE `FormaPago`
  MODIFY `ID_FormaPago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `Tratamiento`
--
ALTER TABLE `Tratamiento`
  MODIFY `ID_Tratamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `Tratamiento_Auditoria`
--
ALTER TABLE `Tratamiento_Auditoria`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Atencion`
--
ALTER TABLE `Atencion`
  ADD CONSTRAINT `Atencion_ibfk_1` FOREIGN KEY (`RUT_Paciente`) REFERENCES `Paciente` (`RUT`) ON DELETE CASCADE,
  ADD CONSTRAINT `Atencion_ibfk_2` FOREIGN KEY (`ID_Tratamiento`) REFERENCES `Tratamiento` (`ID_Tratamiento`) ON DELETE CASCADE,
  ADD CONSTRAINT `Atencion_ibfk_3` FOREIGN KEY (`ID_FormaPago`) REFERENCES `FormaPago` (`ID_FormaPago`) ON DELETE CASCADE;

--
-- Filtros para la tabla `Tratamiento`
--
ALTER TABLE `Tratamiento`
  ADD CONSTRAINT `Tratamiento_ibfk_1` FOREIGN KEY (`ID_Especialista`) REFERENCES `Especialista` (`ID_Especialista`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
