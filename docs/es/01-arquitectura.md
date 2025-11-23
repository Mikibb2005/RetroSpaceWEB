# Arquitectura General de RetroSpace

## 📐 Visión General

RetroSpace está construido siguiendo el patrón **MVC (Modelo-Vista-Controlador)** con una arquitectura de tres capas bien definidas. El sistema está diseñado para ser modular, escalable y fácil de mantener.

## 🎯 Patrón MVC

```
┌────────────────── FLUJO DE PETICIÓN ──────────────────┐
│                                                         │
│  1. Usuario solicita: /proyectos/ver/1                │
│          ↓                                              │
│  2. Router (public/index.php)                          │
│          ↓                                              │
│  3. ProyectosController::ver(1)                        │
│          ↓                                              │
│  4. Proyecto::getById(1) [Modelo]                      │
│          ↓                                              │
│  5. Base de Datos (MySQL)                              │
│          ↓                                              │
│  6. Datos del proyecto                                 │
│          ↓                                              │
│  7. Vista: app/views/proyectos/ver.php                 │
│          ↓                                              │
│  8. HTML renderizado al usuario                        │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

### Modelo (Model)
- **Ubicación**: `/app/models/`
- **Responsabilidad**: Interactuar con la base de datos
- **Ejemplos**: `Usuario.php`, `Proyecto.php`, `Hilo.php`

**Características**:
- Abstracción de la base de datos
- Validación de datos
- Relaciones entre entidades
- Queries preparadas (PDO) para seguridad

### Vista (View)
- **Ubicación**: `/app/views/`
- **Responsabilidad**: Presentación de datos al usuario
- **Ejemplos**: `proyectos/index.php`, `foro/hilo.php`

**Características**:
- Separación completa de lógica y presentación
- Plantillas PHP puras (sin motor de templates)
- Sistema de layouts (header/footer compartidos)
- Soporte multi-idioma con función `__()`

### Controlador (Controller)
- **Ubicación**: `/app/controllers/`
- **Responsabilidad**: Lógica de negocio y coordinación
- **Ejemplos**: `ProyectosController.php`, `ForoController.php`

**Características**:
- Procesamiento de entrada del usuario
- Llamadas a modelos
- Preparación de datos para vistas
- Manejo de sesiones y autenticación

## 🗂️ Capas de la Aplicación

### 1. Capa de Presentación
```
app/views/
├── layout/
│   ├── header.php       # Navegación, meta tags
│   └── footer.php       # Pie, selector de tema/idioma
├── home/
│   └── index.php        # Página de inicio
├── proyectos/
│   ├── index.php        # Lista de proyectos
│   ├── ver.php          # Detalle de proyecto
│   ├── crear.php        # Formulario nuevo proyecto
│   └── actualizacion.php # Detalle de actualización
├── foro/
│   ├── index.php        # Lista de hilos
│   ├── hilo.php         # Detalle de hilo + comentarios
│   └── crear.php        # Nuevo hilo
├── diario/
│   ├── index.php        # Lista de posts
│   ├── post.php         # Detalle de post
│   └── crear.php        # Nuevo post
└── profile/
    ├── index.php        # Perfil público
    └── edit.php         # Editar perfil
```

### 2. Capa de Lógica de Negocio
```
app/controllers/
├── HomeController.php       # Página principal
├── ProyectosController.php  # CRUD proyectos
├── ForoController.php       # CRUD foro
├── DiarioController.php     # CRUD diario
├── ProfileController.php    # Gestión de perfiles
└── AuthController.php       # Login/Registro
```

**Estructura típica de un controlador**:
```php
class ProyectosController {
    private $db;
    private $user;
    
    public function __construct($db, $user) {
        $this->db = $db;
        $this->user = $user;
    }
    
    public function index() {
        // 1. Obtener datos del modelo
        $proyectos = Proyecto::getAll($this->db);
        
        // 2. Preparar datos para la vista
        $data = ['proyectos' => $proyectos];
        
        // 3. Renderizar vista
        require 'app/views/proyectos/index.php';
    }
    
    public function ver($id) {
        // Lógica para un proyecto específico
    }
}
```

### 3. Capa de Datos
```
app/models/
├── Usuario.php             # Modelo de usuarios
├── Proyecto.php            # Modelo de proyectos
├── ProyectoActualizacion.php # Actualizaciones
├── Hilo.php                # Hilos del foro
├── DiarioPost.php          # Posts del diario
└── Comentario.php          # Comentarios (universal)
```

**Estructura típica de un modelo**:
```php
class Proyecto {
    public static function getAll($db, $categoria = null) {
        $query = "SELECT p.*, u.username as autor 
                  FROM proyectos p 
                  LEFT JOIN usuarios u ON p.autor_id = u.id";
        
        if ($categoria) {
            $query .= " WHERE p.categoria = :categoria";
        }
        
        $query .= " ORDER BY p.fecha_actualizacion DESC";
        
        $stmt = $db->prepare($query);
        if ($categoria) {
            $stmt->bindParam(':categoria', $categoria);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getById($db, $id) {
        // Obtener proyecto por ID
    }
    
    public static function create($db, $data) {
        // Crear nuevo proyecto
    }
}
```

## 🔄 Flujo de Datos

### Petición GET (Mostrar contenido)
```
Usuario → Router → Controlador → Modelo → DB
                                     ↓
                            Vista ← Datos
                                     ↓
                            Usuario ← HTML
```

### Petición POST (Guardar contenido)
```
Usuario → Formulario → Router → Controlador
                                     ↓
                           Validación de datos
                                     ↓
                           Modelo → DB (INSERT/UPDATE)
                                     ↓
                           Redirección → Nueva página
```

## 🛣️ Sistema de Enrutamiento

El enrutamiento se gestiona en `/public/index.php`:

```php
// Analizar URL
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$uri = str_replace($scriptName, '', $requestUri);
$uri = trim($uri, '/');

// Separar en partes
$parts = explode('/', $uri);
$controller = $parts[0] ?? 'home';
$action = $parts[1] ?? 'index';
$param = $parts[2] ?? null;

// Mapeo de rutas
switch($controller) {
    case 'proyectos':
        $ctrl = new ProyectosController($db, $user);
        if ($action === 'ver' && $param) {
            $ctrl->ver($param);
        } elseif ($action === 'crear') {
            $ctrl->crear();
        } else {
            $ctrl->index();
        }
        break;
    
    case 'foro':
        $ctrl = new ForoController($db, $user);
        // Similar...
        break;
    
    default:
        $ctrl = new HomeController($db, $user);
        $ctrl->index();
}
```

**Ejemplos de URLs**:
- `/` → HomeController::index()
- `/proyectos` → ProyectosController::index()
- `/proyectos/ver/5` → ProyectosController::ver(5)
- `/foro/hilo/12` → ForoController::hilo(12)
- `/perfil/3` → ProfileController::index(3)

## 🔐 Seguridad

### 1. Autenticación y Sesiones
```php
// app/helpers/Auth.php
class Auth {
    public static function check() {
        return isset($_SESSION['user_id']);
    }
    
    public static function user() {
        if (self::check()) {
            return Usuario::getById($_SESSION['user_id']);
        }
        return null;
    }
}
```

### 2. Protección contra SQL Injection
```php
// INCORRECTO ❌
$query = "SELECT * FROM usuarios WHERE id = " . $_GET['id'];

// CORRECTO ✅
$stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$stmt->execute();
```

### 3. Protección XSS
```php
// Siempre escapar output en vistas
echo htmlspecialchars($proyecto['titulo']);
```

### 4. CSRF Protection
```php
// Generar token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// En formularios
<input type="hidden" name="csrf_token" 
       value="<?php echo $_SESSION['csrf_token']; ?>">

// Validar
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token inválido');
}
```

## 📊 Base de Datos

### Conexión
```php
// config/database.php
try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
```

### Jerarquía de Tablas
```
usuarios (auth, perfiles)
├── proyectos (autor_id → usuarios.id)
│   └── proyecto_actualizaciones (proyecto_id → proyectos.id)
│       └── comentarios (entidad_tipo='proyecto_actualizacion')
├── hilos_foro (autor_id → usuarios.id)
│   └── comentarios_foro (hilo_id → hilos_foro.id)
│       └── respuestas anidadas (parent_id → comentarios_foro.id)  
└── diario_posts (autor_id → usuarios.id)
    └── comentarios (entidad_tipo='diario_post')
```

## 🌍 Sistema Multi-idioma

### Archivos de Idioma
```
app/lang/
├── es.php   # Español
├── en.php   # English
├── ca.php   # Català
├── fr.php   # Français
└── ... (10 idiomas)
```

### Uso en Vistas
```php
// Texto estático
<?php echo __('projects.title'); ?>
// → "Proyectos Comunitarios" (si idioma = es)
// → "Community Projects" (si idioma = en)

// Contenido dinámico
<p data-translatable="description" 
   data-original-lang="es" 
   data-original-text="<?php echo $proyecto['desc']; ?>">
    <?php echo $proyecto['desc']; ?>
</p>
// → Se traduce automáticamente via JavaScript
```

## 🎨 Sistema de Temas

### CSS Modular por Tema
```
public/css/
├── xp.css      # Windows XP
├── vista.css   # Windows Vista
├── win7.css    # Windows 7
├── win8.css    # Windows 8
├── win10.css   # Windows 10
└── win98.css   # Windows 98
```

### Cambio Dinámico
```javascript
// En footer.php
document.getElementById('theme-selector').addEventListener('change', (e) => {
    const theme = e.target.value;
    localStorage.setItem('theme', theme);
    document.getElementById('theme-css').href = `/css/${theme}.css`;
});
```

## 📈 Diagrama de Componentes

```
┌─────────────────────────────────────────────────────────┐
│                    PUBLIC (Acceso Web)                   │
├─────────────────────────────────────────────────────────┤
│  index.php (Router)  │  CSS  │  JS  │  Uploads  │ API  │
└───────────┬─────────────────────────────────────────────┘
            │
            ▼
┌─────────────────────────────────────────────────────────┐
│                    APP (Lógica)                          │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌──────────┐     ┌──────────┐     ┌──────────┐        │
│  │Controllers│────▶│  Models  │────▶│   DB     │        │
│  └─────┬────┘     └──────────┘     └──────────┘        │
│        │                                                 │
│        ▼                                                 │
│  ┌──────────┐     ┌──────────┐                          │
│  │  Views   │────▶│  Helpers │                          │
│  └──────────┘     └──────────┘                          │
│                                                          │
└─────────────────────────────────────────────────────────┘
            │
            ▼
┌─────────────────────────────────────────────────────────┐
│                CONFIG (Configuración)                    │
├─────────────────────────────────────────────────────────┤
│  database.php  │  config.php  │  constants.php          │
└─────────────────────────────────────────────────────────┘
```

## 🚀 Optimizaciones

### 1. Caché de Traducciones
```javascript
// localStorage para caché de 24h
TranslationSystem.cache = {
    'hash_texto_en': {
        translation: 'Translated text',
        timestamp: Date.now()
    }
};
```

### 2. Lazy Loading de Imágenes
```html
<img data-src="/uploads/imagen.jpg" class="lazy">
<script>
document.querySelectorAll('.lazy').forEach(img => {
    img.src = img.dataset.src;
});
</script>
```

### 3. Minificación CSS/JS (Producción)
```bash
# Usar herramientas como:
npm install -g uglify-js clean-css-cli
uglifyjs main.js -o main.min.js
cleancss -o style.min.css style.css
```

---

**Próximo**: [Estructura de Directorios](./02-estructura.md)
