# Sistema de Traducción Automática

## 🌍 Visión General

RetroSpace implementa un sistema de traducción automática completo que soporta **10 idiomas** con traducción dinámica vía AJAX, caché local y detección automática del idioma del navegador.

## 🎯 Características

- ✅ **10 idiomas**: Español, Inglés, Catalán, Francés, Alemán, Italiano, Portugués, Ruso, Japonés, Chino Simplificado
- ✅ **Traducción dual**: Textos estáticos (PHP) y dinámicos (JavaScript/AJAX)
- ✅ **APIs gratuitas**: MyMemory, LibreTranslate.de, ArgoOpenTech con fallback
- ✅ **Caché inteligente**: localStorage 24h con versionado
- ✅ **Indicadores claros**: "Traducido automáticamente de..."
- ✅ **Opción de original**: Botón "Mostrar original"
- ✅ **Persistencia**: Cookie + localStorage
- ✅ **Detección automática**: Del idioma del navegador

## 📁 Estructura

```
mikisito-web/
├── app/
│   ├── lang/                    # Archivos de idioma PHP
│   │   ├── es.php              # Español (default)
│   │   ├── en.php              # English
│   │   ├── ca.php              # Català
│   │   ├── fr.php              # Français
│   │   ├── de.php              # Deutsch
│   │   ├── it.php              # Italiano
│   │   ├── pt.php              # Português
│   │   ├── ru.php              # Русский
│   │   ├── ja.php              # 日本語
│   │   └── zh.php              # 中文
│   └── helpers/
│       └── LanguageHelper.php   # Funciones __() y formatDate()
└── public/
    ├── api/
    │   └── translate.php        # API de traducción
    └── js/
        └── translation.js        # Sistema cliente

```

## 🔧 Componentes

### 1. Archivos de Idioma (PHP)

**Ubicación**: `app/lang/*.php`

**Estructura**:
```php
<?php
// app/lang/es.php
return [
    // Navegación
    'nav.home' => 'Inicio',
    'nav.projects' => 'Proyectos',
    'nav.forum' => 'Foro',
    'nav.diary' => 'Diario',
    
    // Proyectos
    'projects.title' => 'Proyectos Comunitarios',
    'projects.new' => 'Nuevo Proyecto',
    'projects.filter' => 'Filtrar por categoría',
    'projects.none' => 'No hay proyectos',
    
    // Foro
    'forum.title' => 'Foro Comunitario',
    'forum.threads' => 'Hilos Recientes',
    'forum.replies' => 'Comentarios',
    
    // ... más de 100 traducciones
];
```

**Uso en vistas**:
```php
<h1><?php echo __('projects.title'); ?></h1>
// Español: "Proyectos Comunitarios"
// English: "Community Projects"
```

### 2. Helper de Idioma

**Ubicación**: `app/helpers/LanguageHelper.php`

```php
<?php
class Lang {
    private static $translations = [];
    private static $currentLang = 'es';
    
    // Cargar archivo de idioma
    public static function load($lang = 'es') {
        $file = __DIR__ . '/../lang/' . $lang . '.php';
        if (file_exists($file)) {
            self::$translations = require $file;
            self::$currentLang = $lang;
        }
    }
    
    // Obtener traducción
    public static function get($key, $default = null) {
        return self::$translations[$key] ?? $default ?? $key;
    }
    
    // Formatear fecha según idioma
    public static function formatDate($date, $time = false) {
        $timestamp = strtotime($date);
        
        switch(self::$currentLang) {
            case 'en':
                return $time 
                    ? date('M d, Y H:i', $timestamp)
                    : date('M d, Y', $timestamp);
            
            case 'ja':
            case 'zh':
                return $time 
                    ? date('Y年m月d日 H:i', $timestamp)
                    : date('Y年m月d日', $timestamp);
            
            default: // es, ca, fr, de, it, pt, ru
                return $time 
                    ? date('d/m/Y H:i', $timestamp)
                    : date('d/m/Y', $timestamp);
        }
    }
}

// Función helper global
function __($key, $default = null) {
    return Lang::get($key, $default);
}
```

### 3. API de Traducción

**Ubicación**: `public/api/translate.php`

**Servicios soportados**:
1. **MyMemory** (prioridad 1): Más confiable
2. **LibreTranslate.de** (prioridad 2): Backup
3. **ArgoOpenTech** (prioridad 3): Último recurso

**Endpoint**:
```
POST /api/translate.php
Content-Type: application/json

{
    "text": "Este proyecto está hecho para...",
    "source": "es",
    "target": "en"
}
```

**Respuesta exitosa**:
```json
{
    "translatedText": "This project is made to...",
    "detectedLanguage": {"language": "es"},
    "service": "MyMemory",
    "method": "cURL"
}
```

**Respuesta de error**:
```json
{
    "translatedText": "Este proyecto está hecho para...",
    "error": "All translation services unavailable",
    "detectedLanguage": {"language": "es"}
}
```

**Implementación**:
```php
<?php
// Configuración CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

// Detectar si cURL está disponible
$useCurl = function_exists('curl_init');

// Obtener parámetros
$input = json_decode(file_get_contents('php://input'), true);
$text = trim($input['text']);
$source = $input['source'] ?? 'auto';
$target = $input['target'];

// Validar idioma
$validLanguages = ['es', 'en', 'fr', 'de', 'it', 'pt', 'ca', 'ru', 'ja', 'zh'];
if (!in_array($target, $validLanguages)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid target language']);
    exit;
}

// Traducir con MyMemory
function translateMyMemory($url, $text, $source, $target, $useCurl) {
    $langpair = ($source === 'auto' ? 'es' : $source) . '|' . $target;
    $query = $url . '?q=' . urlencode($text) . '&langpair=' . urlencode($langpair);
    
    if ($useCurl) {
        $ch = curl_init($query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $result = curl_exec($ch);
        curl_close($ch);
    } else {
        // Fallback a file_get_contents
        $context = stream_context_create([
            'http' => ['timeout' => 5],
            'ssl' => ['verify_peer' => false]
        ]);
        $result = @file_get_contents($query, false, $context);
    }
    
    if ($result) {
        $response = json_decode($result, true);
        if ($response['responseStatus'] === 200) {
            return $response['responseData']['translatedText'];
        }
    }
    
    return false;
}

// Intentar con cada servicio
$services = [/* MyMemory, LibreTranslate, ArgoOpenTech */];
foreach ($services as $service) {
    $translated = translateWithService($service, $text, $source, $target, $useCurl);
    if ($translated) {
        echo json_encode([
            'translatedText' => $translated,
            'service' => $service['name']
        ]);
        exit;
    }
}

// Si todo falla, devolver original
echo json_encode([
    'translatedText' => $text,
    'error' => 'All translation services unavailable'
]);
```

### 4. Sistema JavaScript

**Ubicación**: `public/js/translation.js`

**Objeto principal**:
```javascript
const TranslationSystem = {
    currentLang: 'es',
    defaultLang: 'es',
    cache: {},
    cacheExpiry: 24 * 60 * 60 * 1000, // 24 horas
    cacheVersion: '1.0',
    
    // Inicializar
    init() {
        // 1. Cargar idioma guardado o detectar del navegador
        const savedLang = localStorage.getItem('preferredLanguage');
        this.currentLang = savedLang || this.detectBrowserLanguage();
        
        // 2. Cargar caché
        this.loadCache();
        
        // 3. Establecer cookie para PHP
        document.cookie = `lang=${this.currentLang}; path=/; max-age=31536000`;
        
        // 4. Traducir si no es español
        if (this.currentLang !== this.defaultLang) {
            this.translatePage();
        }
    },
    
    // Detectar idioma del navegador
    detectBrowserLanguage() {
        const lang = (navigator.language || 'es').substring(0, 2);
        const supported = ['es', 'en', 'fr', 'de', 'it', 'pt', 'ca', 'ru', 'ja', 'zh'];
        return supported.includes(lang) ? lang : 'es';
    },
    
    // Traducir toda la página
    async translatePage() {
        this.showLoadingIndicator();
        
        // Traducir elementos estáticos ([data-translate])
        const staticElements = document.querySelectorAll('[data-translate]');
        for (const el of staticElements) {
            await this.translateElement(el);
        }
        
        // Traducir contenido dinámico ([data-translatable])
        await this.translateDynamicContent();
        
        this.hideLoadingIndicator();
    },
    
    // Traducir elemento dinámico
    async translateDynamicElement(element) {
        const originalText = element.getAttribute('data-original-text');
        const originalLang = element.getAttribute('data-original-lang') || 'es';
        
        // Si ya es el idioma original, no traducir
        if (this.currentLang === originalLang) {
            element.textContent = originalText;
            this.removeTranslationIndicator(element);
            return;
        }
        
        // Verificar caché
        const cacheKey = this.getCacheKey(originalText, this.currentLang);
        if (this.cache[cacheKey]) {
            element.textContent = this.cache[cacheKey].translation;
            this.addTranslationIndicator(element, originalLang, originalText);
            return;
        }
        
        // Llamar API
        const translation = await this.translate(originalText, this.currentLang, originalLang);
        
        // Solo guardar si hubo traducción real
        if (translation && translation !== originalText) {
            element.textContent = translation;
            this.addTranslationIndicator(element, originalLang, originalText);
            
            // Guardar en caché
            this.cache[cacheKey] = {
                translation,
                timestamp: Date.now()
            };
            this.saveCache();
        }
    },
    
    // Llamar API de traducción
    async translate(text, targetLang, sourceLang = 'auto') {
        try {
            const response = await fetch('/api/translate.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({text, target: targetLang, source: sourceLang})
            });
            
            const data = await response.json();
            return data.translatedText;
        } catch (error) {
            console.error('Translation failed:', error);
            return text;
        }
    },
    
    // Agregar indicador "Traducido automáticamente"
    addTranslationIndicator(element, sourceLang, originalText) {
        if (element.nextElementSibling?.classList.contains('translation-indicator')) {
            return; // Ya tiene indicador
        }
        
        const langNames = {
            'es': 'Español', 'en': 'English', 'ca': 'Català',
            'fr': 'Français', 'de': 'Deutsch', 'it': 'Italiano',
            'pt': 'Português', 'ru': 'Русский', 'ja': '日本語', 'zh': '中文'
        };
        
        const indicator = document.createElement('small');
        indicator.className = 'translation-indicator';
        indicator.style.cssText = 'display: block; color: #666; font-size: 11px; margin-top: 2px;';
        indicator.innerHTML = `
            <em>Traducido automáticamente de ${langNames[sourceLang]}</em>
            <a href="#" style="margin-left: 8px; color: #0066cc;" 
               onclick="TranslationSystem.showOriginal(this); return false;">
                Mostrar original
            </a>
        `;
        
        element.parentNode.insertBefore(indicator, element.nextSibling);
    },
    
    // Alternar entre original y traducción
    showOriginal(link) {
        const indicator = link.parentElement;
        const element = indicator.previousElementSibling;
        const originalText = element.getAttribute('data-original-text');
        
        if (link.textContent === 'Mostrar original') {
            element.textContent = originalText;
            link.textContent = 'Mostrar traducción';
        } else {
            const cacheKey = this.getCacheKey(originalText, this.currentLang);
            element.textContent = this.cache[cacheKey].translation;
            link.textContent = 'Mostrar original';
        }
    }
};

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => TranslationSystem.init());
} else {
    TranslationSystem.init();
}
```

## 📝 Uso en Vistas

### Textos Estáticos (PHP)
```php
<!-- Título de sección -->
<h1><?php echo __('projects.title'); ?></h1>

<!-- Botones -->
<button><?php echo __('btn.save'); ?></button>

<!-- Mensajes -->
<p><?php echo __('msg.success'); ?></p>

<!-- Con parámetros (si se implementa) -->
<!-- <p><?php echo __f('user.greeting', ['name' => $user['username']]); ?></p> -->
```

### Contenido Dinámico (JavaScript)
```php
<!-- Título de proyecto (generado por usuario) -->
<h3>
    <span data-translatable="title" 
          data-original-lang="es" 
          data-original-text="<?php echo htmlspecialchars($proyecto['titulo']); ?>">
        <?php echo htmlspecialchars($proyecto['titulo']); ?>
    </span>
</h3>

<!-- Descripción -->
<p data-translatable="description" 
   data-original-lang="es" 
   data-original-text="<?php echo htmlspecialchars($proyecto['descripcion']); ?>">
    <?php echo htmlspecialchars($proyecto['descripcion']); ?>
</p>

<!-- Categoría -->
<span data-translatable="category" 
      data-original-lang="es" 
      data-original-text="<?php echo htmlspecialchars($proyecto['categoria']); ?>">
    <?php echo htmlspecialchars($proyecto['categoria']); ?>
</span>
```

**IMPORTANTE**: El atributo `data-original-text` debe contener el valor FINAL, no código PHP complejo. Pre-calcular en variables si es necesario:

```php
<?php
// ✅ CORRECTO
$titulo = htmlspecialchars($proyecto['titulo']);
?>
<span data-translatable="title" data-original-text="<?php echo $titulo; ?>">
    <?php echo $titulo; ?>
</span>

<?php
// ❌ INCORRECTO (código PHP within atributo)
?>
<span data-translatable="title" data-original-text="<?php 
    $t = $proyecto['titulo'];
    echo htmlspecialchars($t);
?>">
```

## 🗂️ Caché

### localStorage
```javascript
// Estructura del caché
{
    "hash_1234_en": {
        "translation": "Translated text",
        "timestamp": 1700000000000
    },
    "hash_5678_fr": {
        "translation": "Texte traduit",
        "timestamp": 1700000000000
    }
}

// Versión del caché
localStorage.setItem('translationCacheVersion', '1.0');

// Si la versión cambia, se limpia todo el caché
```

### Expiración
- **Duración**: 24 horas
- **Limpieza**: Automática al cargar (entradas expiradas se eliminan)
- **Invalidación**: Cambio de `cacheVersion` en `translation.js`

## 🎨 Selector de Idioma

**Ubicación**: `app/views/layout/footer.php`

```php
<div class="language-selector">
    <label for="language-select"><?php echo __('footer.language'); ?>:</label>
    <select id="language-select" name="language" aria-label="Selector de idioma">
        <option value="es" <?php echo ($currentLang === 'es') ? 'selected' : ''; ?>>🇪🇸 Español</option>
        <option value="en" <?php echo ($currentLang === 'en') ? 'selected' : ''; ?>>🇬🇧 English</option>
        <option value="ca" <?php echo ($currentLang === 'ca') ? 'selected' : ''; ?>>🏴 Català</option>
        <option value="fr" <?php echo ($currentLang === 'fr') ? 'selected' : ''; ?>>🇫🇷 Français</option>
        <option value="de" <?php echo ($currentLang === 'de') ? 'selected' : ''; ?>>🇩🇪 Deutsch</option>
        <option value="it" <?php echo ($currentLang === 'it') ? 'selected' : ''; ?>>🇮🇹 Italiano</option>
        <option value="pt" <?php echo ($currentLang === 'pt') ? 'selected' : ''; ?>>🇵🇹 Português</option>
        <option value="ru" <?php echo ($currentLang === 'ru') ? 'selected' : ''; ?>>🇷🇺 Русский</option>
        <option value="ja" <?php echo ($currentLang === 'ja') ? 'selected' : ''; ?>>🇯🇵 日本語</option>
        <option value="zh" <?php echo ($currentLang === 'zh') ? 'selected' : ''; ?>>🇨🇳 中文</option>
    </select>
</div>

<script>
document.getElementById('language-select').addEventListener('change', async function(e) {
    const newLang = e.target.value;
    await TranslationSystem.changeLanguage(newLang);
});
</script>
```

## 📅 Formato de Fechas

**Función**: `Lang::formatDate($date, $includeTime = false)`

**Formatos por idioma**:
- **es, ca, fr, de, it, pt, ru**: `dd/mm/YYYY` o `dd/mm/YYYY HH:mm`
- **en**: `MMM dd, YYYY` o `MMM dd, YYYY HH:mm`
- **ja, zh**: `YYYY年mm月dd日` o `YYYY年mm月dd日 HH:mm`

**Ejemplos**:
```php
<?php
$fecha = '2025-11-23 14:30:00';

// Español
Lang::load('es');
echo Lang::formatDate($fecha);        // 23/11/2025
echo Lang::formatDate($fecha, true);  // 23/11/2025 14:30

// English
Lang::load('en');
echo Lang::formatDate($fecha);        // Nov 23, 2025
echo Lang::formatDate($fecha, true);  // Nov 23, 2025 14:30

// 日本語
Lang::load('ja');
echo Lang::formatDate($fecha);        // 2025年11月23日
echo Lang::formatDate($fecha, true);  // 2025年11月23日 14:30
?>
```

## 🔧 Troubleshooting

### Problema: Traducciones no aparecen
**Solución**:
1. Verificar que cURL o `allow_url_fopen` esté habilitado en PHP
2. Limpiar caché: `localStorage.removeItem('translationCache')`
3. Ver consola del navegador para errores

### Problema: Error 500 en `/api/translate.php`
**Solución**:
1. Verificar logs de error de PHP
2. Asegurar que `file_get_contents` pueda hacer peticiones HTTPS
3. Verificar que el servidor permita conexiones salientes

### Problema: Textos no se traducen en idioma específico
**Solución**:
1. Verificar que el idioma esté en `$validLanguages` en `translate.php`
2. Comprobar que MyMemory soporta ese par de idiomas

## 📊 Diagrama de Flujo

```
┌──────────────────────────────────────────────────┐
│ Usuario cambia idioma en selector                │
└────────────────┬─────────────────────────────────┘
                 │
                 ▼
┌──────────────────────────────────────────────────┐
│ JavaScript: TranslationSystem.changeLanguage()   │
│  - Guardar en localStorage                       │
│  - Guardar cookie                                │
│  - Recargar página                               │
└────────────────┬─────────────────────────────────┘
                 │
                 ▼
┌──────────────────────────────────────────────────┐
│ PHP: Cargar idioma de cookie                     │
│  - Lang::load($_COOKIE['lang'])                  │
│  - Traducir textos estáticos con __()            │
└────────────────┬─────────────────────────────────┘
                 │
                 ▼
┌──────────────────────────────────────────────────┐
│ JavaScript: Detectar elementos [data-translatable]│
│  - Extraer texto original                        │
│  - Verificar caché                               │
└────────────────┬─────────────────────────────────┘
                 │
        ┌────────┴────────┐
        │                 │
        ▼                 ▼
   ┌─────────┐      ┌──────────┐
   │ En caché│      │ No cache  │
   └────┬────┘      └─────┬────┘
        │                 │
        │                 ▼
        │         ┌────────────────┐
        │         │ POST /api/     │
        │         │ translate.php  │
        │         └───────┬────────┘
        │                 │
        │         ┌───────┴────────┐
        │         │ Llamar APIs:   │
        │         │ 1. MyMemory    │
        │         │ 2. LibreTranslate│
        │         │ 3. ArgoOpenTech │
        │         └───────┬────────┘
        │                 │
        │                 ▼
        │         ┌────────────────┐
        │         │ Respuesta JSON │
        │         └───────┬────────┘
        │                 │
        └─────────────────┘
                 │
                 ▼
┌──────────────────────────────────────────────────┐
│ Actualizar elemento DOM                          │
│  - Cambiar textContent                           │
│  - Añadir indicador "Traducido de..."           │
│  - Guardar en caché localStorage                 │
└──────────────────────────────────────────────────┘
```

---

**Anterior**: [Vistas](./07-vistas.md)  
**Siguiente**: [Sistema de Temas](./09-temas.md)
