-- Schema for PostgreSQL

CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol VARCHAR(20) DEFAULT 'user' CHECK (rol IN ('admin', 'user')),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    esta_bloqueado BOOLEAN DEFAULT FALSE
);

CREATE TABLE posts_diario (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    imagen VARCHAR(255),
    codigo_embed TEXT,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    autor_id INT,
    FOREIGN KEY (autor_id) REFERENCES usuarios (id)
);

CREATE TABLE proyectos (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    categoria VARCHAR(50) NOT NULL CHECK (
        categoria IN (
            'Programacion',
            'Hardware',
            'Mods',
            'GameMaker'
        )
    ),
    link1 VARCHAR(255),
    link2 VARCHAR(255),
    video_url VARCHAR(255),
    imagen VARCHAR(255),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    autor_id INT,
    FOREIGN KEY (autor_id) REFERENCES usuarios (id)
);

CREATE TABLE proyecto_actualizaciones (
    id SERIAL PRIMARY KEY,
    proyecto_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    archivos TEXT DEFAULT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos (id) ON DELETE CASCADE
);

CREATE TABLE proyecto_comentarios (
    id SERIAL PRIMARY KEY,
    actualizacion_id INT NOT NULL,
    autor_id INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (actualizacion_id) REFERENCES proyecto_actualizaciones (id) ON DELETE CASCADE,
    FOREIGN KEY (autor_id) REFERENCES usuarios (id) ON DELETE CASCADE
);

CREATE TABLE foro_hilos (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    categoria VARCHAR(50),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    autor_id INT,
    sticky BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (autor_id) REFERENCES usuarios (id)
);

CREATE TABLE foro_comentarios (
    id SERIAL PRIMARY KEY,
    contenido TEXT NOT NULL,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    autor_id INT,
    hilo_id INT,
    parent_id INT DEFAULT NULL,
    FOREIGN KEY (autor_id) REFERENCES usuarios (id),
    FOREIGN KEY (hilo_id) REFERENCES foro_hilos (id),
    FOREIGN KEY (parent_id) REFERENCES foro_comentarios (id)
);

CREATE TABLE estadisticas (
    id SERIAL PRIMARY KEY,
    tipo VARCHAR(50),
    valor INT,
    fecha DATE
);

-- Initial Data
INSERT INTO
    usuarios (
        username,
        email,
        password_hash,
        rol
    )
VALUES (
        'admin',
        'admin@mikisito.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'admin'
    );

INSERT INTO
    posts_diario (titulo, contenido, autor_id)
VALUES (
        'Primer post en Mikisito',
        '¡Bienvenidos a mi diario! Este es el primer post.',
        1
    );

INSERT INTO
    proyectos (
        titulo,
        descripcion,
        categoria,
        link1,
        autor_id
    )
VALUES (
        'Proyecto Demo',
        'Un proyecto de prueba',
        'Programacion',
        'https://github.com',
        1
    );