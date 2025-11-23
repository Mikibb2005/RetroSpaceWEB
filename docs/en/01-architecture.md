# RetroSpace General Architecture

## 📐 Overview

RetroSpace is built following the **MVC (Model-View-Controller)** pattern with a well-defined three-layer architecture. The system is designed to be modular, scalable, and easy to maintain.

## 🎯 MVC Pattern

```
┌────────────────── REQUEST FLOW ──────────────────────┐
│                                                        │
│  1. User requests: /proyectos/ver/1                  │
│          ↓                                             │
│  2. Router (public/index.php)                         │
│          ↓                                             │
│  3. ProyectosController::ver(1)                       │
│          ↓                                             │
│  4. Proyecto::getById(1) [Model]                      │
│          ↓                                             │
│  5. Database (MySQL)                                  │
│          ↓                                             │
│  6. Project data                                      │
│          ↓                                             │
│  7. View: app/views/proyectos/ver.php                 │
│          ↓                                             │
│  8. Rendered HTML to user                             │
│                                                        │
└────────────────────────────────────────────────────────┘
```

### Model
- **Location**: `/app/models/`
- **Responsibility**: Interact with database
- **Examples**: `Usuario.php`, `Proyecto.php`, `Hilo.php`

**Features**:
- Database abstraction
- Data validation
- Entity relationships
- Prepared queries (PDO) for security

### View
- **Location**: `/app/views/`
- **Responsibility**: Present data to user
- **Examples**: `proyectos/index.php`, `foro/hilo.php`

**Features**:
- Complete separation of logic and presentation
- Pure PHP templates (no template engine)
- Layout system (shared header/footer)
- Multi-language support with `__()` function

### Controller
- **Location**: `/app/controllers/`
- **Responsibility**: Business logic and coordination
- **Examples**: `ProyectosController.php`, `ForoController.php`

**Features**:
- Process user input
- Call models
- Prepare data for views
- Handle sessions and authentication

## 🗂️ Application Layers

### 1. Presentation Layer
```
app/views/
├── layout/
│   ├── header.php       # Navigation, meta tags
│   └── footer.php       # Footer, theme/language selector
├── home/
│   └── index.php        # Home page
├── proyectos/
│   ├── index.php        # Project list
│   ├── ver.php          # Project detail
│   ├── crear.php        # New project form
│   └── actualizacion.php # Update detail
├── foro/
│   ├── index.php        # Thread list
│   ├── hilo.php         # Thread detail + comments
│   └── crear.php        # New thread
├── diario/
│   ├── index.php        # Post list
│   ├── post.php         # Post detail
│   └── crear.php        # New post
└── profile/
    ├── index.php        # Public profile
    └── edit.php         # Edit profile
```

### 2. Business Logic Layer
```
app/controllers/
├── HomeController.php       # Main page
├── ProyectosController.php  # Projects CRUD
├── ForoController.php       # Forum CRUD
├── DiarioController.php     # Diary CRUD
├── ProfileController.php    # Profile management
└── AuthController.php       # Login/Register
```

**Typical controller structure**:
```php
class ProyectosController {
    private $db;
    private $user;
    
    public function __construct($db, $user) {
        $this->db = $db;
        $this->user = $user;
    }
    
    public function index() {
        // 1. Get data from model
        $proyectos = Proyecto::getAll($this->db);
        
        // 2. Prepare data for view
        $data = ['proyectos' => $proyectos];
        
        // 3. Render view
        require 'app/views/proyectos/index.php';
    }
    
    public function ver($id) {
        // Logic for specific project
    }
}
```

### 3. Data Layer
```
app/models/
├── Usuario.php             # User model
├── Proyecto.php            # Project model
├── ProyectoActualizacion.php # Updates
├── Hilo.php                # Forum threads
├── DiarioPost.php          # Diary posts
└── Comentario.php          # Comments (universal)
```

**Typical model structure**:
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
        // Get project by ID
    }
    
    public static function create($db, $data) {
        // Create new project
    }
}
```

## 🔄 Data Flow

### GET Request (Display content)
```
User → Router → Controller → Model → DB
                                 ↓
                        View ← Data
                                 ↓
                        User ← HTML
```

### POST Request (Save content)
```
User → Form → Router → Controller
                            ↓
                   Data Validation
                            ↓
                   Model → DB (INSERT/UPDATE)
                            ↓
                   Redirect → New page
```

## 🛣️ Routing System

Routing is handled in `/public/index.php`:

```php
// Parse URL
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$uri = str_replace($scriptName, '', $requestUri);
$uri = trim($uri, '/');

// Split into parts
$parts = explode('/', $uri);
$controller = $parts[0] ?? 'home';
$action = $parts[1] ?? 'index';
$param = $parts[2] ?? null;

// Route mapping
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

**URL Examples**:
- `/` → HomeController::index()
- `/proyectos` → ProyectosController::index()
- `/proyectos/ver/5` → ProyectosController::ver(5)
- `/foro/hilo/12` → ForoController::hilo(12)
- `/perfil/3` → ProfileController::index(3)

## 🔐 Security

### 1. Authentication and Sessions
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

### 2. SQL Injection Protection
```php
// WRONG ❌
$query = "SELECT * FROM usuarios WHERE id = " . $_GET['id'];

// CORRECT ✅
$stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$stmt->execute();
```

### 3. XSS Protection
```php
// Always escape output in views
echo htmlspecialchars($proyecto['titulo']);
```

### 4. CSRF Protection
```php
// Generate token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// In forms
<input type="hidden" name="csrf_token" 
       value="<?php echo $_SESSION['csrf_token']; ?>">

// Validate
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Invalid CSRF token');
}
```

## 📊 Database

### Connection
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
    die("Connection error: " . $e->getMessage());
}
```

### Table Hierarchy
```
usuarios (auth, profiles)
├── proyectos (autor_id → usuarios.id)
│   └── proyecto_actualizaciones (proyecto_id → proyectos.id)
│       └── comentarios (entidad_tipo='proyecto_actualizacion')
├── hilos_foro (autor_id → usuarios.id)
│   └── comentarios_foro (hilo_id → hilos_foro.id)
│       └── nested replies (parent_id → comentarios_foro.id)  
└── diario_posts (autor_id → usuarios.id)
    └── comentarios (entidad_tipo='diario_post')
```

## 🌍 Multi-language System

### Language Files
```
app/lang/
├── es.php   # Spanish
├── en.php   # English
├── ca.php   # Catalan
├── fr.php   # French
└── ... (10 languages)
```

### Usage in Views
```php
// Static text
<?php echo __('projects.title'); ?>
// → "Proyectos Comunitarios" (if lang = es)
// → "Community Projects" (if lang = en)

// Dynamic content
<p data-translatable="description" 
   data-original-lang="es" 
   data-original-text="<?php echo $proyecto['desc']; ?>">
    <?php echo $proyecto['desc']; ?>
</p>
// → Auto-translated via JavaScript
```

## 🎨 Theme System

### Modular CSS per Theme
```
public/css/
├── xp.css      # Windows XP
├── vista.css   # Windows Vista
├── win7.css    # Windows 7
├── win8.css    # Windows 8
├── win10.css   # Windows 10
└── win98.css   # Windows 98
```

### Dynamic Switching
```javascript
// In footer.php
document.getElementById('theme-selector').addEventListener('change', (e) => {
    const theme = e.target.value;
    localStorage.setItem('theme', theme);
    document.getElementById('theme-css').href = `/css/${theme}.css`;
});
```

## 📈 Component Diagram

```
┌─────────────────────────────────────────────────────────┐
│                    PUBLIC (Web Access)                   │
├─────────────────────────────────────────────────────────┤
│  index.php (Router)  │  CSS  │  JS  │  Uploads  │ API  │
└───────────┬─────────────────────────────────────────────┘
            │
            ▼
┌─────────────────────────────────────────────────────────┐
│                    APP (Logic)                           │
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
│                CONFIG (Configuration)                    │
├─────────────────────────────────────────────────────────┤
│  database.php  │  config.php  │  constants.php          │
└─────────────────────────────────────────────────────────┘
```

## 🚀 Optimizations

### 1. Translation Cache
```javascript
// localStorage for 24h cache
TranslationSystem.cache = {
    'text_hash_en': {
        translation: 'Translated text',
        timestamp: Date.now()
    }
};
```

### 2. Lazy Loading Images
```html
<img data-src="/uploads/image.jpg" class="lazy">
<script>
document.querySelectorAll('.lazy').forEach(img => {
    img.src = img.dataset.src;
});
</script>
```

### 3. CSS/JS Minification (Production)
```bash
# Use tools like:
npm install -g uglify-js clean-css-cli
uglifyjs main.js -o main.min.js
cleancss -o style.min.css style.css
```

---

**Next**: [Directory Structure](./02-structure.md)
