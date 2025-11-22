-- Actualizar tablas para soportar múltiples archivos

-- Posts del diario: agregar columna para archivos JSON
ALTER TABLE posts_diario
ADD COLUMN archivos TEXT DEFAULT NULL COMMENT 'JSON array de rutas de archivos';

-- Proyectos: agregar columna para archivos JSON
ALTER TABLE proyectos
ADD COLUMN archivos TEXT DEFAULT NULL COMMENT 'JSON array de rutas de archivos';

-- Hilos del foro: agregar columna para archivos JSON
ALTER TABLE foro_hilos
ADD COLUMN archivos TEXT DEFAULT NULL COMMENT 'JSON array de rutas de archivos';