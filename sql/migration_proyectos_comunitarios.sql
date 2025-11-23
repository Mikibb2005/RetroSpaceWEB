-- 1. Actualizar tabla proyectos
ALTER TABLE proyectos ADD COLUMN autor_id INT AFTER id;

ALTER TABLE proyectos
ADD COLUMN fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP;

-- Asignar proyectos existentes al usuario ID 1 (Admin)
UPDATE proyectos SET autor_id = 1 WHERE autor_id IS NULL;

UPDATE proyectos SET fecha_actualizacion = fecha_creacion;

-- 2. Tabla para las actualizaciones (Devlogs) del proyecto
CREATE TABLE IF NOT EXISTS proyecto_actualizaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    archivos TEXT DEFAULT NULL, -- JSON con paths de imagenes/videos
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos (id) ON DELETE CASCADE
);

-- 3. Tabla para comentarios en las actualizaciones
CREATE TABLE IF NOT EXISTS proyecto_comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    actualizacion_id INT NOT NULL,
    autor_id INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (actualizacion_id) REFERENCES proyecto_actualizaciones (id) ON DELETE CASCADE,
    FOREIGN KEY (autor_id) REFERENCES usuarios (id) ON DELETE CASCADE
);