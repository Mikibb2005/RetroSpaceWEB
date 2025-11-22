-- Script para crear usuario admin en RetroSpace

-- Eliminar admin anterior si existe
DELETE FROM usuarios WHERE username = 'admin';

-- Crear usuario admin
-- Usuario: admin
-- Contraseña: admin123
-- Hash generado con password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO
    usuarios (
        username,
        email,
        password_hash,
        rol
    )
VALUES (
        'admin',
        'admin@retrospace.local',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'admin'
    );

SELECT 'Usuario admin creado exitosamente!' as mensaje;

SELECT id, username, email, rol
FROM usuarios
WHERE
    username = 'admin';