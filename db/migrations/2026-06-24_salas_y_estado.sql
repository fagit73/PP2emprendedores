-- Migración para bases YA creadas (el schema.sql no se re-ejecuta sobre un volumen existente).
-- Aplica: tabla salas + seed, columna reservas.id_sala, y estado PENDIENTE en el ENUM.
-- Correr UNA sola vez:
--   docker-compose exec -T db mariadb -u root -pbiblio-ifts turnos-biblioteca < db/migrations/2026-06-24_salas_y_estado.sql

USE `turnos-biblioteca`;

CREATE TABLE IF NOT EXISTS salas (
  id_sala INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  descripcion TEXT,
  capacidad INT,
  activa BOOLEAN DEFAULT TRUE
);

INSERT IGNORE INTO salas (id_sala, nombre, descripcion, capacidad) VALUES
  (1,'Sala Multipropósito','Sala principal de la biblioteca',30),
  (2,'Sala de Lectura','Espacio silencioso para estudio',20),
  (3,'Aula Audiovisual','Sala con proyector y equipo multimedia',25);

-- La tabla reservas está vacía, por eso se puede agregar la columna NOT NULL.
ALTER TABLE reservas
  ADD COLUMN IF NOT EXISTS id_sala INT NOT NULL DEFAULT 1 AFTER id_horario;

ALTER TABLE reservas
  ADD CONSTRAINT fk_reserva_sala FOREIGN KEY (id_sala) REFERENCES salas(id_sala);

-- Extiende el ENUM de estado para soportar el flujo PENDIENTE -> ACTIVA.
ALTER TABLE reservas
  MODIFY COLUMN estado ENUM('PENDIENTE','ACTIVA','CANCELADA','FINALIZADA') DEFAULT 'PENDIENTE';
