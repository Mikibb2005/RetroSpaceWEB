-- Complete PostgreSQL Schema + Data for RetroSpaceWEB
-- This file replaces all previous MySQL migration files

-- Drop existing tables in correct order (respect foreign keys)
DROP TABLE IF EXISTS proyecto_comentarios CASCADE;

DROP TABLE IF EXISTS proyecto_actualizaciones CASCADE;

DROP TABLE IF EXISTS mensajes_contacto CASCADE;

DROP TABLE IF EXISTS seguidores CASCADE;

DROP TABLE IF EXISTS foro_comentarios CASCADE;

DROP TABLE IF EXISTS foro_hilos CASCADE;

DROP TABLE IF EXISTS proyectos CASCADE;

DROP TABLE IF EXISTS posts_diario CASCADE;

DROP TABLE IF EXISTS usuarios CASCADE;

DROP TABLE IF EXISTS estadisticas CASCADE;

-- Create all tables
CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    nombre_real VARCHAR(100),
    password_hash VARCHAR(255) NOT NULL,
    rol VARCHAR(20) DEFAULT 'user' CHECK (rol IN ('admin', 'user')),
    biografia TEXT,
    avatar TEXT,
    etiquetas_so TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    esta_bloqueado BOOLEAN DEFAULT FALSE
);

CREATE TABLE posts_diario (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    imagen VARCHAR(255),
    codigo_embed TEXT,
    archivos TEXT,
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
    archivos TEXT,
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
    archivos TEXT,
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
    archivos TEXT,
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
    parent_id INT,
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

CREATE TABLE mensajes_contacto (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    asunto VARCHAR(200) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    leido BOOLEAN DEFAULT FALSE,
    respondido BOOLEAN DEFAULT FALSE,
    ip_address VARCHAR(45),
    user_agent TEXT
);

CREATE TABLE seguidores (
    id SERIAL PRIMARY KEY,
    seguidor_id INT NOT NULL,
    seguido_id INT NOT NULL,
    fecha_seguimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seguidor_id) REFERENCES usuarios (id),
    FOREIGN KEY (seguido_id) REFERENCES usuarios (id),
    UNIQUE (seguidor_id, seguido_id)
);

-- Insert initial data
INSERT INTO
    usuarios (
        id,
        username,
        email,
        nombre_real,
        password_hash,
        rol,
        fecha_registro,
        esta_bloqueado,
        biografia,
        avatar,
        etiquetas_so
    )
VALUES (
        1,
        'admin',
        'admin@retrospace.local',
        'Administrador Supremo',
        '$2y$10$hScxccQaMSUwlJ5Ax/0qvueUdk21.oH.YbeutECieXd.e7uASUVpC',
        'admin',
        '2025-11-22 14:04:13',
        false,
        'Soy el admin del sitio.',
        'data:image/svg+xml;base64,CiAgICAgICAgPHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScxNTAnIGhlaWdodD0nMTUwJz4KICAgICAgICAgICAgPGNpcmNsZSBjeD0nNzUnIGN5PSc3NScgcj0nNzUnIGZpbGw9JyNGNTlFMEInLz4KICAgICAgICAgICAgPHRleHQgeD0nNzUnIHk9Jzk1JyBmb250LXNpemU9JzYwJyB0ZXh0LWFuY2hvcj0nbWlkZGxlJz7irZA8L3RleHQ+CiAgICAgICAgPC9zdmc+CiAgICA=',
        NULL
    ),
    (
        2,
        'testuser',
        'test@example.com',
        NULL,
        '$2y$10$YZeuGDjOfnXbzBxoUEROmeJ/tIJGNHgQNLQvyglUudGeroaQ3uKty',
        'user',
        '2025-11-22 15:06:39',
        false,
        NULL,
        NULL,
        NULL
    ),
    (
        3,
        'Mikibb2005',
        'mikibb2005@gmail.com',
        'Miguel Blánquez Bravo',
        '$2y$10$zvzqH/EG7JmgBnnVReSeBeXrbU05VygypOioG/XOTPOwna1EazLZ2',
        'user',
        '2025-11-22 15:09:13',
        false,
        'Soy el creador de esta pagina web, estudiante de Ingenieria Informatica y duaño del canal de YouTube Bloop.',
        'data:image/svg+xml;base64,CiAgICAgICAgPHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScxNTAnIGhlaWdodD0nMTUwJz4KICAgICAgICAgICAgPGNpcmNsZSBjeD0nNzUnIGN5PSc3NScgcj0nNzUnIGZpbGw9JyNFQzQ4OTknLz4KICAgICAgICAgICAgPHRleHQgeD0nNzUnIHk9Jzk1JyBmb250LXNpemU9JzYwJyB0ZXh0LWFuY2hvcj0nbWlkZGxlJz7wn46uPC90ZXh0PgogICAgICAgIDwvc3ZnPgogICAg',
        '["Windows XP","Ubuntu","Batocera.linux","iOS 17"]'
    ),
    (
        4,
        'miki2',
        'mikibb20051@gmail.com',
        '',
        '$2y$10$/k/A0./RRhEDiSjblctibedT/kyOJRmqz6tovincA1OGnawAlYqtS',
        'user',
        '2025-11-22 20:41:25',
        false,
        'Soy la cuenta secundaria de pruebas del creador de este foro / web',
        'data:image/svg+xml;base64,CiAgICAgICAgPHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScxNTAnIGhlaWdodD0nMTUwJz4KICAgICAgICAgICAgPGNpcmNsZSBjeD0nNzUnIGN5PSc3NScgcj0nNzUnIGZpbGw9JyNFQzQ4OTknLz4KICAgICAgICAgICAgPHRleHQgeD0nNzUnIHk9Jzk1JyBmb250LXNpemU9JzYwJyB0ZXh0LWFuY2hvcj0nbWlkZGxlJz7wn46uPC90ZXh0PgogICAgICAgIDwvc3ZnPgogICAh',
        NULL
    );

INSERT INTO
    posts_diario (
        id,
        titulo,
        contenido,
        imagen,
        codigo_embed,
        fecha_publicacion,
        autor_id,
        archivos
    )
VALUES (
        1,
        'Primer post en RetroSpace',
        '¡Bienvenidos a mi diario! Este es el primer post.

Aqui como su propio nombre indica voy a estar subiendo un diario de los temas tecnologicos, tales como mis estudios (Ingenieria Informatica), mi canal de Youtube (@Bloop434) y mods o proyectos que vaya haciendo, que por cierto, tambien podreis ver en el apartado de Proyectos que ahi ire subiendo todos los que voy haciendo y como evolucionan.

En este foro se baneara y bloqueara el acceso a los usuarios que falten el respeto a quien sea, y queremos que no haya ningun tipo de odio, posiciones politicas, ni nada de eso.

Y sin mucho mas que decir espero que os mole la idea, y que hagais muchos amigos, voy a seguir desarrollando cosas para la web.',
        '',
        '',
        '2025-11-22 14:04:13',
        1,
        '["/uploads/diario/6922e12b12190_1763893547.jpg"]'
    );

INSERT INTO
    proyectos (
        id,
        titulo,
        descripcion,
        categoria,
        link1,
        link2,
        video_url,
        imagen,
        fecha_creacion,
        autor_id,
        archivos,
        fecha_actualizacion
    )
VALUES (
        1,
        'RetroSpace web',
        'Este proyecto está hecho para hacer pruebas de funcionamiento de la web. También quiero que se use para yo subir los logs de actualizaciones que iré haciendo. Y en los comentarios quiero que me dejéis las funcionalidades que queréis que mejore/añada.',
        'Programacion',
        '',
        '',
        '',
        '',
        '2025-11-22 14:04:13',
        1,
        '["/uploads/proyectos/6922f45f6c28b_1763898463.png"]',
        '2025-11-23 12:48:08'
    );

INSERT INTO
    foro_hilos (
        id,
        titulo,
        descripcion,
        categoria,
        fecha_creacion,
        autor_id,
        sticky,
        archivos
    )
VALUES (
        1,
        'Primer Hilo creado',
        'Hola, soy Miki es desarrollador de esta pagina web, esto solo es el primer hilo que creo y lo voy a utilizar para saludar a todo el que entre nuevo en este foro.
Un saludo.',
        'general',
        '2025-11-22 20:12:19',
        3,
        false,
        NULL
    );

INSERT INTO
    foro_comentarios (
        id,
        contenido,
        fecha_publicacion,
        autor_id,
        hilo_id,
        parent_id
    )
VALUES (
        1,
        'hola, primer coment',
        '2025-11-22 20:35:08',
        3,
        1,
        NULL
    ),
    (
        2,
        'Y se puede responder de tranqui',
        '2025-11-22 20:35:20',
        3,
        1,
        1
    ),
    (
        3,
        'probando interaccion entre dos cuentas',
        '2025-11-22 20:41:45',
        4,
        1,
        NULL
    );

INSERT INTO
    seguidores (
        id,
        seguidor_id,
        seguido_id,
        fecha_seguimiento
    )
VALUES (
        1,
        4,
        3,
        '2025-11-22 20:42:01'
    ),
    (
        2,
        1,
        3,
        '2025-11-23 11:22:40'
    );

-- Update sequences to correct values
SELECT setval ('usuarios_id_seq', 4);

SELECT setval ('posts_diario_id_seq', 1);

SELECT setval ('proyectos_id_seq', 1);

SELECT setval ('foro_hilos_id_seq', 1);

SELECT setval ('foro_comentarios_id_seq', 3);

SELECT setval ('seguidores_id_seq', 2);