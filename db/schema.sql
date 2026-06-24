USE `turnos-biblioteca`;

CREATE TABLE IF NOT EXISTS roles (
  id_rol INT AUTO_INCREMENT PRIMARY KEY,
  nombre_rol VARCHAR(50) NOT NULL UNIQUE,
  descripcion TEXT
);
INSERT IGNORE INTO roles (nombre_rol, descripcion) VALUES
  ('ADMIN','Administrador con acceso completo'),
  ('DIRECTIVO','Puede consultar información del sistema'),
  ('DOCENTE','Puede crear y administrar sus reservas');

CREATE TABLE IF NOT EXISTS usuarios (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  nombre_completo VARCHAR(150) NOT NULL,
  dni VARCHAR(20) UNIQUE NOT NULL,
  celular VARCHAR(30),
  email_institucional VARCHAR(150) UNIQUE NOT NULL,
  email_recuperacion VARCHAR(150),
  password_hash VARCHAR(255) NOT NULL,
  id_rol INT NOT NULL,
  activo BOOLEAN DEFAULT TRUE,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_usuario_roles FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
);

CREATE TABLE IF NOT EXISTS tipos_uso (
  id_tipo_uso INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  descripcion TEXT
);
INSERT IGNORE INTO tipos_uso (nombre, descripcion) VALUES
  ('Lectura','Uso de biblioteca para estudio'),
  ('Audiovisual','Uso multimedia y proyección'),
  ('Extension Cultural','Talleres y eventos culturales');

CREATE TABLE IF NOT EXISTS horarios (
  id_horario INT AUTO_INCREMENT PRIMARY KEY,
  hora_inicio TIME NOT NULL,
  hora_fin TIME NOT NULL
);
INSERT IGNORE INTO horarios (hora_inicio, hora_fin) VALUES
  ('07:30:00','08:30:00'),('08:30:00','09:30:00'),('09:40:00','10:40:00'),
  ('10:50:00','11:50:00'),('11:50:00','12:30:00'),('13:00:00','14:00:00'),
  ('14:00:00','15:00:00'),('15:10:00','16:10:00'),('16:20:00','17:20:00'),
  ('17:20:00','18:20:00'),('18:20:00','19:20:00'),('19:30:00','20:30:00'),
  ('20:30:00','21:30:00'),('21:30:00','22:30:00');

CREATE TABLE IF NOT EXISTS reservas (
  id_reserva INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  id_tipo_uso INT NOT NULL,
  id_horario INT NOT NULL,
  fecha_reserva DATE NOT NULL,
  dia_semana VARCHAR(20),
  motivo TEXT,
  modo_proposito ENUM('FORMULARIO','ARCHIVO','SIN_DATOS') DEFAULT 'SIN_DATOS',
  estado ENUM('ACTIVA','CANCELADA','FINALIZADA') DEFAULT 'ACTIVA',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reserva_usuario FOREIGN KEY (id_usuario)  REFERENCES usuarios(id_usuario),
  CONSTRAINT fk_reserva_tipo    FOREIGN KEY (id_tipo_uso) REFERENCES tipos_uso(id_tipo_uso),
  CONSTRAINT fk_reserva_horario FOREIGN KEY (id_horario)  REFERENCES horarios(id_horario)
);

CREATE TABLE IF NOT EXISTS proyectos (
  id_proyecto INT AUTO_INCREMENT PRIMARY KEY,
  id_reserva INT NOT NULL,
  titulo VARCHAR(200),
  docente VARCHAR(150),
  fecha_inicio DATE,
  fecha_fin DATE,
  descripcion TEXT,
  evaluacion TEXT,
  archivo VARCHAR(255),
  CONSTRAINT fk_proyecto_reserva FOREIGN KEY (id_reserva)
    REFERENCES reservas(id_reserva) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS palabras_clave (
  id_keyword INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL UNIQUE
);
INSERT IGNORE INTO palabras_clave (nombre) VALUES
  ('Ciencias Sociales'),('Educación Artistica'),('Educación Física'),('Ingles'),
  ('Matemática'),('Bases de Datos'),('Seguridad Informática'),
  ('Desarrollo de Aplicaciones Web Dinámicas'),('Laboratorio de Redes Informáticas'),
  ('Proyecto de Diseño e Implementación de Sitios Web Dinámicos'),
  ('Prácticas Profesionalizantes'),('Proyección Audiovisual');

CREATE TABLE IF NOT EXISTS proyecto_keywords (
  id_proyecto INT NOT NULL,
  id_keyword INT NOT NULL,
  PRIMARY KEY (id_proyecto, id_keyword),
  FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto) ON DELETE CASCADE,
  FOREIGN KEY (id_keyword)  REFERENCES palabras_clave(id_keyword)
);

CREATE TABLE IF NOT EXISTS auditoria_reservas (
  id_auditoria INT AUTO_INCREMENT PRIMARY KEY,
  id_reserva INT NOT NULL,
  id_usuario INT NOT NULL,
  accion ENUM('CREADA','EDITADA','CANCELADA'),
  descripcion TEXT,
  fecha_anterior DATE NULL,
  fecha_nueva DATE NULL,
  horario_anterior VARCHAR(50),
  horario_nuevo VARCHAR(50),
  fecha_accion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_reserva) REFERENCES reservas(id_reserva),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE INDEX idx_fecha_reserva      ON reservas(fecha_reserva);
CREATE INDEX idx_usuario_reserva    ON reservas(id_usuario);
CREATE INDEX idx_tipo_reserva       ON reservas(id_tipo_uso);
CREATE INDEX idx_reserva_fecha_hora ON reservas(fecha_reserva, id_horario);
