# 🌐 Sistema de Traducción Automática

Sistema completo de traducción multi-idioma para RetroSpace, inspirado en Reddit.

## 📋 Características

- ✅ **10 idiomas soportados**: Español, Inglés, Catalán, Francés, Alemán, Italiano, Portugués, Ruso, Japonés, Chino
- ✅ **Traducción automática** de contenido dinámico usando LibreTranslate (gratuito)
- ✅ **Caché inteligente** en localStorage (1 día de duración)
- ✅ **Detección automática** del idioma del navegador
- ✅ **Sin recargas de página** (AJAX)
- ✅ **Indicador de traducción** con opción "Mostrar original"
- ✅ **Compatible** con todos los temas (XP, Win98, Vista, Win7, Win8, Win10, Win11, macOS)
- ✅ **Responsive** para móviles

## 🚀 Uso

### Textos Estáticos de la UI

Para traducir textos estáticos (botones, etiquetas, menús), usa la función `__()`:

```php
<h2><?php echo __('projects.title'); ?></h2>
<a href="..." class="xp-button"><?php echo __('btn.view'); ?></a>
```

### Contenido Dinámico (Base de Datos)

Para marcar contenido dinámico como traducible, usa los atributos `data-translatable`:

```php
<!-- Título del proyecto -->
<h3 data-translatable="title" data-original-lang="es">
    <?php echo htmlspecialchars($proyecto['titulo']); ?>
</h3>

<!-- Descripción -->
<p data-translatable="description">
    <?php echo htmlspecialchars($proyecto['descripcion']); ?>
</p>

<!-- Categoría -->
<span data-translatable="category">
    <?php echo htmlspecialchars($proyecto['categoria']); ?>
</span>
```

### Formateo de Fechas

Usa el helper para formatear fechas según el idioma:

```php
<?php echo Lang::formatDate($proyecto['fecha_actualizacion']); ?>
```

## 📁 Estructura de Archivos

```
app/
├── lang/                       # Archivos de idioma
│   ├── es.php                 # Español
│   ├── en.php                 # Inglés
│   └── ca.php                 # Catalán
└── helpers/
    └── LanguageHelper.php     # Helper de idiomas

public/
├── api/
│   └── translate.php          # API de traducción
└── js/
    └── translation.js         # Sistema JS de traducción
```

## 🔧 Agregar Nuevas Traducciones

### 1. Agregar nueva clave de traducción

Edita `app/lang/es.php`:

```php
return [
    // ...
    'mi.nueva.clave' => 'Mi texto en español',
];
```

Edita `app/lang/en.php`:

```php
return [
    // ...
    'mi.nueva.clave' => 'My text in English',
];
```

### 2. Usar en la vista

```php
<p><?php echo __('mi.nueva.clave'); ?></p>
```

## ⚙️ Configuración de LibreTranslate

Por defecto, el sistema usa la instancia pública de LibreTranslate:

```
https://libretranslate.com/translate
```

### Instalar LibreTranslate localmente (opcional)

Si quieres tu propia instancia:

```bash
# Con Docker
docker run -p 5000:5000 libretranslate/libretranslate

# Luego edita public/api/translate.php
$LIBRETRANSLATE_URL = 'http://localhost:5000/translate';
```

## 🧹 Limpiar Caché de Traducciones

El caché se limpia automáticamente después de 1 día. Para limpiar manualmente:

```javascript
// En la consola del navegador
localStorage.removeItem('translationCache');
localStorage.removeItem('preferredLanguage');
location.reload();
```

## 🎨 Selector de Idiomas

El selector está en el footer (taskbar) junto al selector de temas. Los idiomas disponibles son:

- 🇪🇸 Español
- 🇬🇧 English
- 🏴 Català
- 🇫🇷 Français
- 🇩🇪 Deutsch
- 🇮🇹 Italiano
- 🇵🇹 Português
- 🇷🇺 Русский
- 🇯🇵 日本語
- 🇨🇳 中文

## 📝 Ejemplos Completos

### Ejemplo: Página de Proyectos

```php
<div class="xp-window">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            ⚙️ <?php echo __('projects.title'); ?>
        </div>
    </div>
    <div class="xp-content">
        <h2><?php echo __('projects.subtitle'); ?></h2>
        
        <?php foreach ($proyectos as $proyecto): ?>
            <div class="project-card">
                <h3 data-translatable="title" data-original-lang="es">
                    <?php echo htmlspecialchars($proyecto['titulo']); ?>
                </h3>
                
                <p data-translatable="description">
                    <?php echo htmlspecialchars($proyecto['descripcion']); ?>
                </p>
                
                <small>
                    <?php echo __('projects.by'); ?> 
                    <strong><?php echo htmlspecialchars($proyecto['autor']); ?></strong>
                    | <?php echo Lang::formatDate($proyecto['fecha']); ?>
                </small>
            </div>
        <?php endforeach; ?>
    </div>
</div>
```

## 🐛 Solución de Problemas

### La traducción no funciona

1. Verifica que `/js/translation.js` se carga correctamente
2. Abre la consola del navegador y busca errores
3. Verifica que los elementos tengan el atributo `data-translatable`

### "Translation service unavailable"

LibreTranslate puede estar caído. Opciones:

1. Espera unos minutos e inténtalo de nuevo
2. Usa una instancia alternativa: `https://translate.argosopentech.com/translate`
3. Instala tu propia instancia local

### El caché no se limpia

```javascript
localStorage.clear();
location.reload();
```

## 📚 Recursos

- [LibreTranslate](https://github.com/LibreTranslate/LibreTranslate) - Traducción gratuita y open-source
- [Códigos de idioma ISO 639-1](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes)

---

**🎉 ¡Sistema de traducción completamente funcional y listo para usar!**
