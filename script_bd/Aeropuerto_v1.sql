CREATE DATABASE IF NOT EXISTS aeropuerto;
USE aeropuerto;

-- -----------------------------------------------------
-- Table aeropuerto.Administrador
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS aeropuerto.Administrador (
  idAdministrador INT NOT NULL,
  Nombre VARCHAR(45) NULL DEFAULT NULL,
  Apellido VARCHAR(45) NULL DEFAULT NULL,
  Correo VARCHAR(45) NULL DEFAULT NULL,
  Telefono INT NULL DEFAULT NULL,
  Clave VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (idAdministrador)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table aeropuerto.Piloto
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS aeropuerto.Piloto (
  idPiloto INT NOT NULL,
  Nombre VARCHAR(45) NULL DEFAULT NULL,
  Apellido VARCHAR(45) NULL DEFAULT NULL,
  Correo VARCHAR(45) NULL DEFAULT NULL,
  Telefono INT NULL DEFAULT NULL,
  Clave VARCHAR(45) NULL DEFAULT NULL,
  Foto VARCHAR(100) NULL DEFAULT NULL,
  Estado_Piloto INT NOT NULL,
  PRIMARY KEY (idPiloto)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table aeropuerto.Pasajero
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS aeropuerto.Pasajero (
  idPasajero INT NOT NULL,
  Nombre VARCHAR(45) NULL DEFAULT NULL,
  Apellido VARCHAR(45) NULL DEFAULT NULL,
  Correo VARCHAR(45) NULL DEFAULT NULL,
  Telefono INT NULL DEFAULT NULL,
  Clave VARCHAR(45) NULL DEFAULT NULL,
  Codigo_Verificacion INT NOT NULL DEFAULT 0,
  PRIMARY KEY (idPasajero)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table aeropuerto.Avion
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS aeropuerto.Avion (
  Matricula INT NOT NULL,
  Modelo VARCHAR(45) NULL DEFAULT NULL,
  Capacidad INT NULL DEFAULT NULL,
  PRIMARY KEY (Matricula)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table aeropuerto.Ciudad
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS aeropuerto.Ciudad (
  idCiudad INT NOT NULL AUTO_INCREMENT,
  Nombre VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (idCiudad)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table aeropuerto.Ruta
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS aeropuerto.Ruta (
  idRuta INT NOT NULL AUTO_INCREMENT,
  Duracion_Estimada TIME NULL DEFAULT NULL,
  Distancia_KM VARCHAR(45) NULL DEFAULT NULL,
  Origen INT NOT NULL,
  Destino INT NOT NULL,
  PRIMARY KEY (idRuta),
  INDEX fk_Ruta_Ciudad1_idx (Origen ASC),
  INDEX fk_Ruta_Ciudad2_idx (Destino ASC),
  CONSTRAINT fk_Ruta_Ciudad1
    FOREIGN KEY (Origen)
    REFERENCES aeropuerto.Ciudad (idCiudad)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_Ruta_Ciudad2
    FOREIGN KEY (Destino)
    REFERENCES aeropuerto.Ciudad (idCiudad)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table aeropuerto.Estado_Vuelo
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS aeropuerto.Estado_Vuelo (
  idEstado_Vuelo INT NOT NULL,
  Valor VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (idEstado_Vuelo)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table aeropuerto.Vuelo
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS aeropuerto.Vuelo (
  idVuelo INT NOT NULL AUTO_INCREMENT,
  Fecha DATE NOT NULL,
  Hora_Despegue TIME NOT NULL,
  Piloto_principal INT NOT NULL,
  Copiloto INT NOT NULL,
  Avion_Matricula INT NOT NULL,
  Ruta_idRuta INT NOT NULL,
  Hora_Llegada TIME NULL DEFAULT NULL,
  Estado_Vuelo_idEstado_Vuelo INT NOT NULL,
  PRIMARY KEY (idVuelo),
  INDEX fk_Vuelo_piloto_idx (Piloto_principal ASC),
  INDEX fk_Vuelo_piloto1_idx (Copiloto ASC),
  INDEX fk_Vuelo_Avion1_idx (Avion_Matricula ASC),
  INDEX fk_Vuelo_Ruta1_idx (Ruta_idRuta ASC),
  INDEX fk_Vuelo_Estado_Vuelo1_idx (Estado_Vuelo_idEstado_Vuelo ASC),
  CONSTRAINT fk_Vuelo_piloto
    FOREIGN KEY (Piloto_principal)
    REFERENCES aeropuerto.Piloto (idPiloto)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_Vuelo_piloto1
    FOREIGN KEY (Copiloto)
    REFERENCES aeropuerto.Piloto (idPiloto)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_Vuelo_Avion1
    FOREIGN KEY (Avion_Matricula)
    REFERENCES aeropuerto.Avion (Matricula)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_Vuelo_Ruta1
    FOREIGN KEY (Ruta_idRuta)
    REFERENCES aeropuerto.Ruta (idRuta)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_Vuelo_Estado_Vuelo1
    FOREIGN KEY (Estado_Vuelo_idEstado_Vuelo)
    REFERENCES aeropuerto.Estado_Vuelo (idEstado_Vuelo)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table aeropuerto.Estado_Ticket
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS aeropuerto.Estado_Ticket (
  idEstado_Ticket INT NOT NULL,
  Valor VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (idEstado_Ticket)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table aeropuerto.Ticket
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS aeropuerto.Ticket (
  idTicket INT NOT NULL AUTO_INCREMENT,
  Estado_Ticket_idEstado_Ticket INT NOT NULL,
  Precio DOUBLE NULL DEFAULT NULL,
  Puesto INT NOT NULL,
  Pasajero_idPasajero INT NOT NULL,
  Vuelo_idVuelo INT NOT NULL,
  Check_in INT NULL DEFAULT 0,
  PRIMARY KEY (idTicket),
  INDEX fk_Ticket_Estado_Ticket1_idx (Estado_Ticket_idEstado_Ticket ASC),
  INDEX fk_Ticket_Pasajero1_idx (Pasajero_idPasajero ASC),
  INDEX fk_Ticket_Vuelo1_idx (Vuelo_idVuelo ASC),
  CONSTRAINT fk_Ticket_Estado_Ticket1
    FOREIGN KEY (Estado_Ticket_idEstado_Ticket)
    REFERENCES aeropuerto.Estado_Ticket (idEstado_Ticket)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_Ticket_Pasajero1
    FOREIGN KEY (Pasajero_idPasajero)
    REFERENCES aeropuerto.Pasajero (idPasajero)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_Ticket_Vuelo1
    FOREIGN KEY (Vuelo_idVuelo)
    REFERENCES aeropuerto.Vuelo (idVuelo)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;