# RetroSpace - Documentación Completa / Complete Documentation

## 📚 Índice / Table of Contents

### Español
1. [Arquitectura General](./es/01-arquitectura.md) - Visión general del sistema
2. [Estructura de Directorios](./es/02-estructura.md) - Organización de archivos
3. [Sistema de Enrutamiento](./es/03-enrutamiento.md) - Cómo funcionan las URLs
4. [Base de Datos](./es/04-base-de-datos.md) - Esquema y migraciones
5. [Modelos](./es/05-modelos.md) - Capa de datos
6. [Controladores](./es/06-controladores.md) - Lógica de negocio
7. [Vistas](./es/07-vistas.md) - Interfaz de usuario
8. [Sistema de Traducción](./es/08-traduccion.md) - Multiidioma
9. [Sistema de Temas](./es/09-temas.md) - Windows XP, 7, 8, 10, Vista, 98
10. [APIs](./es/10-apis.md) - Endpoints REST
11. [JavaScript](./es/11-javascript.md) - Scripts del cliente
12. [Guía de Desarrollo](./es/12-desarrollo.md) - Cómo contribuir

### English
1. [General Architecture](./en/01-architecture.md) - System overview
2. [Directory Structure](./en/02-structure.md) - File organization
3. [Routing System](./en/03-routing.md) - How URLs work
4. [Database](./en/04-database.md) - Schema and migrations
5. [Models](./en/05-models.md) - Data layer
6. [Controllers](./en/06-controllers.md) - Business logic
7. [Views](./en/07-views.md) - User interface
8. [Translation System](./en/08-translation.md) - Multi-language
9. [Theme System](./en/09-themes.md) - Windows XP, 7, 8, 10, Vista, 98
10. [APIs](./en/10-apis.md) - REST endpoints
11. [JavaScript](./en/11-javascript.md) - Client scripts
12. [Development Guide](./en/12-development.md) - How to contribute

---

## 🚀 Inicio Rápido / Quick Start

### Español

#### Requisitos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache/Nginx con mod_rewrite
- Extensiones PHP: pdo_mysql, mbstring, json

#### Instalación
```bash
# Clonar el repositorio
git clone https://github.com/tuusuario/retrospace.git
cd retrospace

# Configurar base de datos
mysql -u root -p < sql/schema.sql

# Configurar archivo de configuración
cp config/config.example.php config/config.php
# Editar config/config.php con tus credenciales

# Establecer permisos
chmod -R 755 public/
chmod -R 777 public/uploads/

# Acceder a http://localhost/
```

### English

#### Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx with mod_rewrite
- PHP extensions: pdo_mysql, mbstring, json

#### Installation
```bash
# Clone repository
git clone https://github.com/yourusername/retrospace.git
cd retrospace

# Set up database
mysql -u root -p < sql/schema.sql

# Configure settings
cp config/config.example.php config/config.php
# Edit config/config.php with your credentials

# Set permissions
chmod -R 755 public/
chmod -R 777 public/uploads/

# Access http://localhost/
```

---

## 🏗️ Arquitectura / Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    CLIENTE / CLIENT                      │
│  (Navegador con JavaScript + CSS tematizado)            │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              CAPA DE PRESENTACIÓN / VIEW LAYER           │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐              │
│  │  Header  │  │   Body   │  │  Footer  │              │
│  │  (Nav)   │  │ (Content)│  │ (Themes) │              │
│  └──────────┘  └──────────┘  └──────────┘              │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│           CAPA DE CONTROL / CONTROLLER LAYER             │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐              │
│  │  Router  │→ │Controller│→ │  Model   │              │
│  │ (index)  │  │  Logic   │  │  Data    │              │
│  └──────────┘  └──────────┘  └──────────┘              │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              CAPA DE DATOS / DATA LAYER                  │
│  ┌──────────────────────────────────────────┐           │
│  │         Base de Datos MySQL              │           │
│  │  Usuarios │ Proyectos │ Foro │ Diario   │           │
│  └──────────────────────────────────────────┘           │
└─────────────────────────────────────────────────────────┘
```

## 📂 Estructura Principal / Main Structure

```
mikisito-web/
├── 📁 app/               # Lógica de aplicación / Application logic
│   ├── controllers/       # Controladores MVC
│   ├── models/           # Modelos de datos
│   ├── views/            # Vistas/Plantillas
│   ├── lang/             # Archivos de idioma
│   └── helpers/          # Funciones auxiliares
├── 📁 public/            # Archivos públicos / Public files
│   ├── css/              # Hojas de estilo (temas)
│   ├── js/               # JavaScript
│   ├── uploads/          # Archivos subidos
│   ├── api/              # Endpoints API REST
│   └── index.php         # Punto de entrada
├── 📁 config/            # Configuración / Configuration
├── 📁 sql/               # Esquemas y migraciones
└── 📁 docs/              # Esta documentación
```

---

## 🌟 Características Principales / Main Features

### ✅ Implementadas / Implemented

- **Sistema MVC**: Arquitectura Modelo-Vista-Controlador
- **Multi-idioma**: 10 idiomas con traducción automática
- **Temas Retro**: 6 temas de Windows (XP, Vista, 7, 8, 10, 98)
- **Foro Comunitario**: Hilos, comentarios anidados, sticky posts
- **Proyectos Comunitarios**: Devlogs, actualizaciones, comentarios
- **Diario Personal**: Posts con multimedia
- **Sistema de Usuarios**: Login, registro, perfiles, follows
- **Comentarios Anidados**: Respuestas recursivas
- **Galería Multimedia**: Imágenes y videos con lightbox
- **API REST**: Traducción automática
- **Responsive**: Compatible con móviles y tablets

### 🔜 Por Implementar / To Be Implemented

- Sistema de notificaciones en tiempo real
- Chat privado entre usuarios
- Búsqueda avanzada
- Moderación avanzada
- Panel de administración completo
- Sistema de badges/logros

---

## 📖 Convenciones de Código / Code Conventions

### PHP
- PSR-4 para autoloading
- camelCase para métodos
- PascalCase para clases
- snake_case para nombres de base de datos

### JavaScript
- camelCase para variables y funciones
- PascalCase para clases/constructores
- UPPER_SNAKE_CASE para constantes

### CSS
- kebab-case para clases
- Prefijo `xp-`, `win7-`, etc. para estilos específicos de tema

---

## 🤝 Contribuir / Contributing

Ver [Guía de Desarrollo](./es/12-desarrollo.md) para español
See [Development Guide](./en/12-development.md) for English

---

## 📝 Licencia / License

Este proyecto es de código abierto bajo licencia MIT.
This project is open source under the MIT license.

---

## 👨‍💻 Autor / Author

**Miki** - [GitHub](https://github.com/Mikibb2005)

---

## 📞 Soporte / Support

- **Issues**: [GitHub Issues](https://github.com/Mikibb2005/MikissitoWEB/issues)
- **Email**: contacto@retrospace.com
- **Documentación**: Ver archivos en `/docs/`

---

**Última actualización / Last update**: Noviembre 2025
**Versión / Version**: 1.0.0
