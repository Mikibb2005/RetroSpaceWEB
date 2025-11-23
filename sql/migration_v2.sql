-- 2. Tabla para las actualizaciones (Devlogs) del proyecto
CREATE TABLE IF NOT EXISTS proyecto_actualizaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    archivos TEXT DEFAULT NULL,
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