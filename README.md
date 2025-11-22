# MikisitoOS - Red Social Estilo Windows XP

![MikisitoOS](https://img.shields.io/badge/PHP-7.4+-blue.svg)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

Una red social con diseño nostálgico de Windows XP que incluye sistema de usuarios, foro con comentarios anidados, perfiles personalizables y más.

## 🎨 Características

- **Diseño Windows XP**: Interfaz nostálgica con estilo Windows XP completo
- **Sistema de Usuarios**: Registro, login, perfiles personalizables
- **Avatares Dinámicos**: 15 avatares SVG generados dinámicamente
- **Foro Completo**: 
  - Creación de hilos por categorías
  - Comentarios anidados infinitos (estilo Reddit)
  - Sistema de respuestas
- **Sistema Social**: 
  - Seguir/Dejar de seguir usuarios
  - Ver seguidores y seguidos
  - Feed de actividad
- **Perfiles**: Biografía, nombre real, avatares, estadísticas

## 🛠️ Tecnologías

- **Backend**: PHP 7.4+
- **Base de Datos**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript vanilla
- **Arquitectura**: MVC personalizado

## 📋 Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache/Nginx con mod_rewrite habilitado
- Extensiones PHP: PDO, pdo_mysql

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/TU-USUARIO/mikisito-web.git
cd mikisito-web
```

### 2. Configurar la base de datos

```bash
# Crear la base de datos
mysql -u root -p

CREATE DATABASE mikisito;
USE mikisito;

# Importar el esquema
SOURCE sql/database.sql;

# (Opcional) Importar datos de ejemplo
SOURCE sql/update_schema.sql;
```

### 3. Configurar Apache/Nginx

#### Apache (.htaccess ya incluido)
Asegúrate de que `mod_rewrite` esté habilitado:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Configura el DocumentRoot apuntando a la carpeta `public/`:
```apache
<VirtualHost *:80>
    ServerName mikisito.local
    DocumentRoot /ruta/a/mikisito-web/public
    
    <Directory /ruta/a/mikisito-web/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name mikisito.local;
    root /ruta/a/mikisito-web/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}
```

### 4. Configurar conexión a la base de datos

Edita `app/core/Database.php` con tus credenciales:

```php
private $host = 'localhost';
private $db_name = 'mikisito';
private $username = 'root';
private $password = 'tu_password';
```

### 5. Acceder a la aplicación

Abre tu navegador en `http://mikisito.local` o `http://localhost`

## 📁 Estructura del Proyecto

```
mikisito-web/
├── app/
│   ├── controllers/     # Controladores MVC
│   ├── models/          # Modelos de datos
│   ├── views/           # Vistas PHP
│   │   ├── layout/      # Header, footer
│   │   ├── profile/     # Vistas de perfil
│   │   ├── foro/        # Vistas del foro
│   │   └── auth/        # Login, registro
│   ├── core/            # Clases core (Router, Database)
│   └── helpers/         # Funciones auxiliares
├── public/              # Carpeta pública (DocumentRoot)
│   ├── css/            # Estilos
│   ├── js/             # JavaScript
│   └── index.php       # Entry point
└── sql/                # Scripts SQL
```

## 🎯 Uso

### Crear un usuario
1. Ve a "Registrarse" en el menú
2. Completa el formulario
3. Inicia sesión con tus credenciales

### Personalizar perfil
1. Haz clic en tu nombre de usuario
2. "Editar Perfil"
3. Selecciona avatar, añade nombre real y biografía

### Crear un hilo en el foro
1. Ve a "Foro"
2. "Crear Nuevo Hilo"
3. Completa título, categoría y descripción

### Comentar
1. Entra en cualquier hilo
2. Escribe tu comentario
3. Puedes responder a comentarios existentes (anidación infinita)

## 🔧 Características Técnicas

- **Routing personalizado** con soporte para parámetros dinámicos
- **PDO** con prepared statements para seguridad
- **Password hashing** con bcrypt
- **Sesiones PHP** para autenticación
- **Comentarios anidados** con algoritmo recursivo de árbol
- **Avatares SVG** generados dinámicamente
- **BASE_URL dinámica** para portabilidad entre entornos

## 🐛 Troubleshooting

### Los estilos no cargan
- Verifica que `.htaccess` tenga las reglas de rewrite correctas
- Asegúrate de que `mod_rewrite` esté habilitado

### Errores de conexión a BD
- Verifica las credenciales en `app/core/Database.php`
- Asegúrate de que MySQL esté corriendo
- Verifica que la base de datos `mikisito` exista

### La página de perfil está en blanco
- Ejecuta `sql/update_schema.sql` para crear tablas faltantes
- Verifica que `PDO::FETCH_ASSOC` esté configurado

## 📝 TODO / Roadmap

- [ ] Sistema de mensajería privada
- [ ] Notificaciones en tiempo real
- [ ] Sistema de búsqueda
- [ ] Moderación del foro
- [ ] Sistema de reputación/karma
- [ ] Subida de imágenes
- [ ] Temas/skins alternativos

## 🤝 Contribuir

¡Las contribuciones son bienvenidas! Si quieres contribuir:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👤 Autor

**MikisitoOS Team**

---

⭐ Si te gusta el proyecto, ¡dale una estrella en GitHub!
