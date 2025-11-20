-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-11-2025 a las 19:55:05
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
-- Base de datos: `aeropuerto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `idAdministrador` int(11) NOT NULL,
  `Nombre` varchar(45) DEFAULT NULL,
  `Apellido` varchar(45) DEFAULT NULL,
  `Correo` varchar(45) DEFAULT NULL,
  `Telefono` int(11) DEFAULT NULL,
  `Clave` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avion`
--

CREATE TABLE `avion` (
  `Matricula` int(11) NOT NULL,
  `Modelo` varchar(45) DEFAULT NULL,
  `Capacidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudad`
--

CREATE TABLE `ciudad` (
  `idCiudad` int(11) NOT NULL,
  `Nombre` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_ticket`
--

CREATE TABLE `estado_ticket` (
  `idEstado_Ticket` int(11) NOT NULL,
  `Valor` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_vuelo`
--

CREATE TABLE `estado_vuelo` (
  `idEstado_Vuelo` int(11) NOT NULL,
  `Valor` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pasajero`
--

CREATE TABLE `pasajero` (
  `idPasajero` int(11) NOT NULL,
  `Nombre` varchar(45) DEFAULT NULL,
  `Apellido` varchar(45) DEFAULT NULL,
  `Correo` varchar(45) DEFAULT NULL,
  `Telefono` int(11) DEFAULT NULL,
  `Clave` varchar(45) DEFAULT NULL,
  `Codigo_Verificacion` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `piloto`
--

CREATE TABLE `piloto` (
  `idPiloto` int(11) NOT NULL,
  `Nombre` varchar(45) DEFAULT NULL,
  `Apellido` varchar(45) DEFAULT NULL,
  `Correo` varchar(45) DEFAULT NULL,
  `Telefono` int(11) DEFAULT NULL,
  `Clave` varchar(45) DEFAULT NULL,
  `Foto` varchar(100) DEFAULT NULL,
  `Estado_Piloto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ruta`
--

CREATE TABLE `ruta` (
  `idRuta` int(11) NOT NULL,
  `Duracion_Estimada` time DEFAULT NULL,
  `Distancia_KM` varchar(45) DEFAULT NULL,
  `Origen` int(11) NOT NULL,
  `Destino` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket`
--

CREATE TABLE `ticket` (
  `idTicket` int(11) NOT NULL,
  `Estado_Ticket_idEstado_Ticket` int(11) NOT NULL,
  `Precio` double DEFAULT NULL,
  `Puesto` int(11) NOT NULL,
  `Pasajero_idPasajero` int(11) NOT NULL,
  `Vuelo_idVuelo` int(11) NOT NULL,
  `Check_in` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vuelo`
--

CREATE TABLE `vuelo` (
  `idVuelo` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `Hora_Despegue` time NOT NULL,
  `Piloto_principal` int(11) NOT NULL,
  `Copiloto` int(11) NOT NULL,
  `Avion_Matricula` int(11) NOT NULL,
  `Ruta_idRuta` int(11) NOT NULL,
  `Hora_Llegada` time DEFAULT NULL,
  `Estado_Vuelo_idEstado_Vuelo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`idAdministrador`);

--
-- Indices de la tabla `avion`
--
ALTER TABLE `avion`
  ADD PRIMARY KEY (`Matricula`);

--
-- Indices de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  ADD PRIMARY KEY (`idCiudad`);

--
-- Indices de la tabla `estado_ticket`
--
ALTER TABLE `estado_ticket`
  ADD PRIMARY KEY (`idEstado_Ticket`);

--
-- Indices de la tabla `estado_vuelo`
--
ALTER TABLE `estado_vuelo`
  ADD PRIMARY KEY (`idEstado_Vuelo`);

--
-- Indices de la tabla `pasajero`
--
ALTER TABLE `pasajero`
  ADD PRIMARY KEY (`idPasajero`);

--
-- Indices de la tabla `piloto`
--
ALTER TABLE `piloto`
  ADD PRIMARY KEY (`idPiloto`);

--
-- Indices de la tabla `ruta`
--
ALTER TABLE `ruta`
  ADD PRIMARY KEY (`idRuta`),
  ADD KEY `fk_Ruta_Ciudad1_idx` (`Origen`),
  ADD KEY `fk_Ruta_Ciudad2_idx` (`Destino`);

--
-- Indices de la tabla `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`idTicket`),
  ADD KEY `fk_Ticket_Estado_Ticket1_idx` (`Estado_Ticket_idEstado_Ticket`),
  ADD KEY `fk_Ticket_Pasajero1_idx` (`Pasajero_idPasajero`),
  ADD KEY `fk_Ticket_Vuelo1_idx` (`Vuelo_idVuelo`);

--
-- Indices de la tabla `vuelo`
--
ALTER TABLE `vuelo`
  ADD PRIMARY KEY (`idVuelo`),
  ADD KEY `fk_Vuelo_piloto_idx` (`Piloto_principal`),
  ADD KEY `fk_Vuelo_piloto1_idx` (`Copiloto`),
  ADD KEY `fk_Vuelo_Avion1_idx` (`Avion_Matricula`),
  ADD KEY `fk_Vuelo_Ruta1_idx` (`Ruta_idRuta`),
  ADD KEY `fk_Vuelo_Estado_Vuelo1_idx` (`Estado_Vuelo_idEstado_Vuelo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  MODIFY `idCiudad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ruta`
--
ALTER TABLE `ruta`
  MODIFY `idRuta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ticket`
--
ALTER TABLE `ticket`
  MODIFY `idTicket` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vuelo`
--
ALTER TABLE `vuelo`
  MODIFY `idVuelo` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ruta`
--
ALTER TABLE `ruta`
  ADD CONSTRAINT `fk_Ruta_Ciudad1` FOREIGN KEY (`Origen`) REFERENCES `ciudad` (`idCiudad`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Ruta_Ciudad2` FOREIGN KEY (`Destino`) REFERENCES `ciudad` (`idCiudad`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `fk_Ticket_Estado_Ticket1` FOREIGN KEY (`Estado_Ticket_idEstado_Ticket`) REFERENCES `estado_ticket` (`idEstado_Ticket`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Ticket_Pasajero1` FOREIGN KEY (`Pasajero_idPasajero`) REFERENCES `pasajero` (`idPasajero`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Ticket_Vuelo1` FOREIGN KEY (`Vuelo_idVuelo`) REFERENCES `vuelo` (`idVuelo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `vuelo`
--
ALTER TABLE `vuelo`
  ADD CONSTRAINT `fk_Vuelo_Avion1` FOREIGN KEY (`Avion_Matricula`) REFERENCES `avion` (`Matricula`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Vuelo_Estado_Vuelo1` FOREIGN KEY (`Estado_Vuelo_idEstado_Vuelo`) REFERENCES `estado_vuelo` (`idEstado_Vuelo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Vuelo_Ruta1` FOREIGN KEY (`Ruta_idRuta`) REFERENCES `ruta` (`idRuta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Vuelo_piloto` FOREIGN KEY (`Piloto_principal`) REFERENCES `piloto` (`idPiloto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Vuelo_piloto1` FOREIGN KEY (`Copiloto`) REFERENCES `piloto` (`idPiloto`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
