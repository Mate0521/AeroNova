-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-11-2025 a las 20:22:34
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
-- Estructura de tabla para la tabla `g2_administrador`
--

CREATE TABLE `g2_administrador` (
  `idAdministrador` int(11) NOT NULL,
  `Nombre` varchar(45) DEFAULT NULL,
  `Apellido` varchar(45) DEFAULT NULL,
  `Correo` varchar(45) DEFAULT NULL,
  `Telefono` int(11) DEFAULT NULL,
  `Clave` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `g2_avion`
--

CREATE TABLE `g2_avion` (
  `Matricula` int(11) NOT NULL,
  `Modelo` varchar(45) DEFAULT NULL,
  `Capacidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `g2_ciudad`
--

CREATE TABLE `g2_ciudad` (
  `idCiudad` int(11) NOT NULL,
  `Nombre` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `g2_estado_piloto`
--

CREATE TABLE `g2_estado_piloto` (
  `id_estado` int(11) NOT NULL,
  `valor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `g2_estado_ticket`
--

CREATE TABLE `g2_estado_ticket` (
  `idEstado_Ticket` int(11) NOT NULL,
  `Valor` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `g2_estado_vuelo`
--

CREATE TABLE `g2_estado_vuelo` (
  `idEstado_Vuelo` int(11) NOT NULL,
  `Valor` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `g2_pasajero`
--

CREATE TABLE `g2_pasajero` (
  `idPasajero` int(11) NOT NULL,
  `Nombre` varchar(45) DEFAULT NULL,
  `Apellido` varchar(45) DEFAULT NULL,
  `Correo` varchar(45) DEFAULT NULL,
  `Telefono` int(11) DEFAULT NULL,
  `Clave` varchar(45) DEFAULT NULL,
  `Codigo_Verificacion` int(6) NOT NULL DEFAULT 0,
  `estado_cuenta` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `g2_piloto`
--

CREATE TABLE `g2_piloto` (
  `idPiloto` int(11) NOT NULL,
  `Nombre` varchar(45) DEFAULT NULL,
  `Apellido` varchar(45) DEFAULT NULL,
  `Correo` varchar(45) DEFAULT NULL,
  `Telefono` int(11) DEFAULT NULL,
  `Clave` varchar(45) DEFAULT NULL,
  `Foto` varchar(100) DEFAULT NULL,
  `estado_cuenta` int(1) NOT NULL DEFAULT 0,
  `id_estado_piloto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `g2_ruta`
--

CREATE TABLE `g2_ruta` (
  `idRuta` int(11) NOT NULL,
  `Duracion_Estimada` time DEFAULT NULL,
  `Distancia_KM` varchar(45) DEFAULT NULL,
  `Origen` int(11) NOT NULL,
  `Destino` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `g2_ticket`
--

CREATE TABLE `g2_ticket` (
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
-- Estructura de tabla para la tabla `g2_vuelo`
--

CREATE TABLE `g2_vuelo` (
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
-- Indices de la tabla `g2_administrador`
--
ALTER TABLE `g2_administrador`
  ADD PRIMARY KEY (`idAdministrador`);

--
-- Indices de la tabla `g2_avion`
--
ALTER TABLE `g2_avion`
  ADD PRIMARY KEY (`Matricula`);

--
-- Indices de la tabla `g2_ciudad`
--
ALTER TABLE `g2_ciudad`
  ADD PRIMARY KEY (`idCiudad`);

--
-- Indices de la tabla `g2_estado_piloto`
--
ALTER TABLE `g2_estado_piloto`
  ADD PRIMARY KEY (`id_estado`),
  ADD UNIQUE KEY `valor` (`valor`);

--
-- Indices de la tabla `g2_estado_ticket`
--
ALTER TABLE `g2_estado_ticket`
  ADD PRIMARY KEY (`idEstado_Ticket`);

--
-- Indices de la tabla `g2_estado_vuelo`
--
ALTER TABLE `g2_estado_vuelo`
  ADD PRIMARY KEY (`idEstado_Vuelo`);

--
-- Indices de la tabla `g2_pasajero`
--
ALTER TABLE `g2_pasajero`
  ADD PRIMARY KEY (`idPasajero`),
  ADD UNIQUE KEY `Correo` (`Correo`);

--
-- Indices de la tabla `g2_piloto`
--
ALTER TABLE `g2_piloto`
  ADD PRIMARY KEY (`idPiloto`),
  ADD UNIQUE KEY `Correo` (`Correo`),
  ADD KEY `id_estado_piloto` (`id_estado_piloto`);

--
-- Indices de la tabla `g2_ruta`
--
ALTER TABLE `g2_ruta`
  ADD PRIMARY KEY (`idRuta`),
  ADD KEY `fk_Ruta_Ciudad1_idx` (`Origen`),
  ADD KEY `fk_Ruta_Ciudad2_idx` (`Destino`);

--
-- Indices de la tabla `g2_ticket`
--
ALTER TABLE `g2_ticket`
  ADD PRIMARY KEY (`idTicket`),
  ADD KEY `fk_Ticket_Estado_Ticket1_idx` (`Estado_Ticket_idEstado_Ticket`),
  ADD KEY `fk_Ticket_Pasajero1_idx` (`Pasajero_idPasajero`),
  ADD KEY `fk_Ticket_Vuelo1_idx` (`Vuelo_idVuelo`);

--
-- Indices de la tabla `g2_vuelo`
--
ALTER TABLE `g2_vuelo`
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
-- AUTO_INCREMENT de la tabla `g2_ciudad`
--
ALTER TABLE `g2_ciudad`
  MODIFY `idCiudad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `g2_estado_piloto`
--
ALTER TABLE `g2_estado_piloto`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `g2_pasajero`
--
ALTER TABLE `g2_pasajero`
  MODIFY `idPasajero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `g2_ruta`
--
ALTER TABLE `g2_ruta`
  MODIFY `idRuta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `g2_ticket`
--
ALTER TABLE `g2_ticket`
  MODIFY `idTicket` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `g2_vuelo`
--
ALTER TABLE `g2_vuelo`
  MODIFY `idVuelo` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `g2_piloto`
--
ALTER TABLE `g2_piloto`
  ADD CONSTRAINT `g2_piloto_ibfk_1` FOREIGN KEY (`id_estado_piloto`) REFERENCES `g2_estado_piloto` (`id_estado`);

--
-- Filtros para la tabla `g2_ruta`
--
ALTER TABLE `g2_ruta`
  ADD CONSTRAINT `fk_Ruta_Ciudad1` FOREIGN KEY (`Origen`) REFERENCES `g2_ciudad` (`idCiudad`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Ruta_Ciudad2` FOREIGN KEY (`Destino`) REFERENCES `g2_ciudad` (`idCiudad`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `g2_ticket`
--
ALTER TABLE `g2_ticket`
  ADD CONSTRAINT `fk_Ticket_Estado_Ticket1` FOREIGN KEY (`Estado_Ticket_idEstado_Ticket`) REFERENCES `g2_estado_ticket` (`idEstado_Ticket`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Ticket_Pasajero1` FOREIGN KEY (`Pasajero_idPasajero`) REFERENCES `g2_pasajero` (`idPasajero`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Ticket_Vuelo1` FOREIGN KEY (`Vuelo_idVuelo`) REFERENCES `g2_vuelo` (`idVuelo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `g2_vuelo`
--
ALTER TABLE `g2_vuelo`
  ADD CONSTRAINT `fk_Vuelo_Avion1` FOREIGN KEY (`Avion_Matricula`) REFERENCES `g2_avion` (`Matricula`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Vuelo_Estado_Vuelo1` FOREIGN KEY (`Estado_Vuelo_idEstado_Vuelo`) REFERENCES `g2_estado_vuelo` (`idEstado_Vuelo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Vuelo_Ruta1` FOREIGN KEY (`Ruta_idRuta`) REFERENCES `g2_ruta` (`idRuta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Vuelo_piloto` FOREIGN KEY (`Piloto_principal`) REFERENCES `g2_piloto` (`idPiloto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Vuelo_piloto1` FOREIGN KEY (`Copiloto`) REFERENCES `g2_piloto` (`idPiloto`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
