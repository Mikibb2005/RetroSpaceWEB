/**
 * Translation System - JavaScript
 * Maneja traducciones AJAX, caché en localStorage, y UI
 */

const TranslationSystem = {
    currentLang: 'es',
    defaultLang: 'es',
    cache: {},
    cacheExpiry: 24 * 60 * 60 * 1000, // 1 día en milisegundos
    cacheVersion: '1.0', // Incrementar para invalidar caché antiguo

    /**
     * Inicializar el sistema de traducción
     */
    init() {
        console.log('🚀 Inicializando TranslationSystem...');

        // Cargar idioma de localStorage o detectar del navegador
        const savedLang = localStorage.getItem('preferredLanguage');
        if (savedLang) {
            this.currentLang = savedLang;
        } else {
            this.currentLang = this.detectBrowserLanguage();
            localStorage.setItem('preferredLanguage', this.currentLang);
        }

        console.log(`ℹ️ Idioma actual: ${this.currentLang}`);

        // Cargar caché del localStorage
        this.loadCache();

        // Establecer cookie para PHP
        document.cookie = `lang=${this.currentLang}; path=/; max-age=31536000`; // 1 año

        // Si no es español, traducir la página
        if (this.currentLang !== this.defaultLang) {
            this.translatePage();
        } else {
            console.log('ℹ️ Idioma es default (es), no se requiere traducción.');
        }
    },

    /**
     * Detectar idioma del navegador
     */
    detectBrowserLanguage() {
        const browserLang = navigator.language || navigator.userLanguage;
        const lang = browserLang.substring(0, 2);

        const supported = ['es', 'en', 'fr', 'de', 'it', 'pt', 'ca', 'ru', 'ja', 'zh'];
        return supported.includes(lang) ? lang : this.defaultLang;
    },

    /**
     * Cambiar idioma
     */
    async changeLanguage(newLang) {
        if (newLang === this.currentLang) return;

        this.currentLang = newLang;
        localStorage.setItem('preferredLanguage', newLang);
        document.cookie = `lang=${newLang}; path=/; max-age=31536000`;

        // Recargar la página con el nuevo idioma
        window.location.reload();
    },

    /**
     * Traducir toda la página
     */
    async translatePage() {
        console.log('🔄 Iniciando traducción de página...');

        // Mostrar un indicador de carga
        this.showLoadingIndicator();

        // Obtener todos los elementos con data-translate (estáticos)
        const elements = document.querySelectorAll('[data-translate]');

        // Traducir elementos estáticos
        for (const element of elements) {
            await this.translateElement(element);
        }

        // Traducir textos dinámicos (proyectos, posts, etc) en paralelo
        // No usamos await aquí para que no bloquee si tarda mucho, 
        // pero idealmente deberían ir cargando progresivamente.
        this.translateDynamicContent().then(() => {
            this.hideLoadingIndicator();
            console.log('✅ Traducción dinámica completada.');
        }).catch(err => {
            console.error('❌ Error en traducción dinámica:', err);
            this.hideLoadingIndicator();
        });
    },

    /**
     * Traducir un elemento específico (estático)
     */
    async translateElement(element) {
        const originalText = element.getAttribute('data-original') || element.textContent.trim();

        // Guardar original si no existe
        if (!element.getAttribute('data-original')) {
            element.setAttribute('data-original', originalText);
        }

        // Si ya está traducido en caché, usar esa traducción
        const cacheKey = this.getCacheKey(originalText, this.currentLang);
        if (this.cache[cacheKey]) {
            element.textContent = this.cache[cacheKey].translation;
            return;
        }

        // Traducir
        const translation = await this.translate(originalText, this.currentLang);

        // Solo guardar si hubo traducción real (diferente al original)
        if (translation && translation !== originalText) {
            element.textContent = translation;

            // Guardar en caché
            this.cache[cacheKey] = {
                translation: translation,
                timestamp: Date.now()
            };
            this.saveCache();
        }
    },

    /**
     * Traducir contenido dinámico (títulos, descripciones, etc)
     */
    async translateDynamicContent() {
        console.log('🌐 Iniciando traducción de contenido dinámico...');

        // Obtener todos los elementos traducibles
        const elements = document.querySelectorAll('[data-translatable]');
        console.log(`🌐 Encontrados ${elements.length} elementos dinámicos para traducir.`);

        const promises = [];
        for (const element of elements) {
            // Agregamos un pequeño delay aleatorio para no saturar el navegador/API si son muchos
            promises.push(this.translateDynamicElement(element));
        }

        await Promise.all(promises);
    },

    /**
     * Traducir un elemento dinámico con indicador de traducción
     */
    async translateDynamicElement(element) {
        const originalText = element.getAttribute('data-original-text') || element.textContent.trim();
        const originalLang = element.getAttribute('data-original-lang') || 'es';

        // Guardar original si no existe
        if (!element.getAttribute('data-original-text')) {
            element.setAttribute('data-original-text', originalText);
            element.setAttribute('data-original-lang', originalLang);
        }

        // Si es el idioma original, mostrar sin traducir
        if (this.currentLang === originalLang) {
            element.textContent = originalText;
            this.removeTranslationIndicator(element);
            return;
        }

        // Verificar caché
        const cacheKey = this.getCacheKey(originalText, this.currentLang);
        if (this.cache[cacheKey]) {
            console.log(`✅ Usando caché para: "${originalText.substring(0, 20)}..."`);
            element.textContent = this.cache[cacheKey].translation;
            this.addTranslationIndicator(element, originalLang, originalText);
            return;
        }

        // Traducir
        console.log(`🌍 Traduciendo: "${originalText.substring(0, 20)}..." a ${this.currentLang}`);
        const translation = await this.translate(originalText, this.currentLang, originalLang);

        // Solo guardar si hubo traducción real
        if (translation && translation !== originalText) {
            console.log(`✨ Traducido: "${translation.substring(0, 20)}..."`);
            element.textContent = translation;
            this.addTranslationIndicator(element, originalLang, originalText);

            // Guardar en caché
            this.cache[cacheKey] = {
                translation: translation,
                timestamp: Date.now()
            };
            this.saveCache();
        } else {
            console.warn(`⚠️ Fallo o texto idéntico: "${originalText.substring(0, 20)}..."`);
            // NO guardamos en caché para permitir reintentos
        }
    },

    /**
     * Llamar a la API de traducción
     */
    async translate(text, targetLang, sourceLang = 'auto') {
        try {
            const baseUrl = (window.APP_CONFIG && window.APP_CONFIG.BASE_URL) || '';
            const response = await fetch(baseUrl + '/api/translate.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    text: text,
                    target: targetLang,
                    source: sourceLang
                })
            });

            const data = await response.json();

            if (data.error) {
                console.error('Translation error:', data.error);
                return text; // Devolver original si hay error
            }

            return data.translatedText;
        } catch (error) {
            console.error('Translation failed:', error);
            return text; // Devolver original si falla
        }
    },

    /**
     * Agregar indicador de traducción automática
     */
    addTranslationIndicator(element, sourceLang, originalText) {
        // Verificar si ya tiene indicador
        if (element.nextElementSibling && element.nextElementSibling.classList.contains('translation-indicator')) {
            return;
        }

        const langNames = {
            'es': 'Español',
            'en': 'English',
            'ca': 'Català',
            'fr': 'Français',
            'de': 'Deutsch',
            'it': 'Italiano',
            'pt': 'Português',
            'ru': 'Русский',
            'ja': '日本語',
            'zh': '中文'
        };

        const indicator = document.createElement('small');
        indicator.className = 'translation-indicator';
        indicator.style.cssText = 'display: block; color: #666; font-size: 11px; margin-top: 2px;';
        indicator.innerHTML = `
            <em>Traducido automáticamente de ${langNames[sourceLang] || sourceLang}</em>
            <a href="#" style="margin-left: 8px; color: #0066cc;" onclick="TranslationSystem.showOriginal(this); return false;">Mostrar original</a>
        `;
        // Guardamos el texto original en el indicador también por si acaso
        indicator.setAttribute('data-original-text-backup', originalText);

        element.parentNode.insertBefore(indicator, element.nextSibling);
    },

    /**
     * Remover indicador de traducción
     */
    removeTranslationIndicator(element) {
        const indicator = element.nextElementSibling;
        if (indicator && indicator.classList.contains('translation-indicator')) {
            indicator.remove();
        }
    },

    /**
     * Mostrar texto original
     */
    showOriginal(link) {
        const indicator = link.parentElement;
        const element = indicator.previousElementSibling;
        const originalText = element.getAttribute('data-original-text');

        if (link.textContent === 'Mostrar original') {
            element.textContent = originalText;
            link.textContent = 'Mostrar traducción';
        } else {
            const cacheKey = this.getCacheKey(originalText, this.currentLang);
            if (this.cache[cacheKey]) {
                element.textContent = this.cache[cacheKey].translation;
            }
            link.textContent = 'Mostrar original';
        }
    },

    /**
     * Generar clave de caché
     */
    getCacheKey(text, lang) {
        // Usar hash simple para la clave
        return `${this.hashCode(text)}_${lang}`;
    },

    /**
     * Hash simple para textos
     */
    hashCode(str) {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            const char = str.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash;
        }
        return hash.toString(36);
    },

    /**
     * Cargar caché del localStorage
     */
    loadCache() {
        try {
            const cached = localStorage.getItem('translationCache');
            const version = localStorage.getItem('translationCacheVersion');

            // Si la versión no coincide, limpiar caché
            if (version !== this.cacheVersion) {
                console.log('🧹 Limpiando caché antiguo por cambio de versión.');
                localStorage.removeItem('translationCache');
                localStorage.setItem('translationCacheVersion', this.cacheVersion);
                this.cache = {};
                return;
            }

            if (cached) {
                const data = JSON.parse(cached);
                // Limpiar entradas expiradas
                const now = Date.now();
                for (const key in data) {
                    if (now - data[key].timestamp < this.cacheExpiry) {
                        this.cache[key] = data[key];
                    }
                }
            }
        } catch (e) {
            console.error('Error loading translation cache:', e);
        }
    },

    /**
     * Guardar caché en localStorage
     */
    saveCache() {
        try {
            localStorage.setItem('translationCache', JSON.stringify(this.cache));
        } catch (e) {
            // Si excede el límite, limpiar caché antiguo
            console.warn('Translation cache full, cleaning old entries');
            this.cleanOldCache();
        }
    },

    /**
     * Limpiar entradas antiguas del caché
     */
    cleanOldCache() {
        const now = Date.now();
        const cleaned = {};

        for (const key in this.cache) {
            if (now - this.cache[key].timestamp < this.cacheExpiry / 2) {
                cleaned[key] = this.cache[key];
            }
        }

        this.cache = cleaned;
        this.saveCache();
    },

    /**
     * Mostrar indicador de carga
     */
    showLoadingIndicator() {
        if (document.getElementById('translation-loading')) return;

        const indicator = document.createElement('div');
        indicator.id = 'translation-loading';
        indicator.style.cssText = 'position: fixed; top: 60px; right: 20px; background: rgba(0,0,0,0.8); color: white; padding: 10px 20px; border-radius: 4px; z-index: 10000; font-size: 14px; box-shadow: 0 2px 5px rgba(0,0,0,0.3);';
        indicator.textContent = '🌐 Traduciendo contenido...';
        document.body.appendChild(indicator);
    },

    /**
     * Ocultar indicador de carga
     */
    hideLoadingIndicator() {
        const indicator = document.getElementById('translation-loading');
        if (indicator) {
            indicator.remove();
        }
    }
};

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        TranslationSystem.init();
    });
} else {
    TranslationSystem.init();
}
