<?php
// Language Helper Class
class Lang {
    private static $currentLang = 'es';
    private static $translations = [];
    private static $fallbackLang = 'es';
    
    // Idiomas soportados con sus nombres nativos
    public static $supportedLanguages = [
        'es' => ['name' => 'Español', 'flag' => '🇪🇸'],
        'en' => ['name' => 'English', 'flag' => '🇬🇧'],
        'ca' => ['name' => 'Català', 'flag' => '🏴'],
        'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
        'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪'],
        'it' => ['name' => 'Italiano', 'flag' => '🇮🇹'],
        'pt' => ['name' => 'Português', 'flag' => '🇵🇹'],
        'ru' => ['name' => 'Русский', 'flag' => '🇷🇺'],
        'ja' => ['name' => '日本語', 'flag' => '🇯🇵'],
        'zh' => ['name' => '中文', 'flag' => '🇨🇳'],
    ];
    
    /**
     * Inicializar el idioma
     */
    public static function init($lang = null) {
        // Detectar idioma del navegador si no hay preferencia
        if ($lang === null) {
            $lang = self::detectBrowserLanguage();
        }
        
        self::setLanguage($lang);
        self::loadTranslations();
    }
    
    /**
     * Detectar idioma del navegador
     */
    private static function detectBrowserLanguage() {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            foreach ($langs as $lang) {
                $lang = substr($lang, 0, 2);
                if (isset(self::$supportedLanguages[$lang])) {
                    return $lang;
                }
            }
        }
        return self::$fallbackLang;
    }
    
    /**
     * Establecer idioma actual
     */
    public static function setLanguage($lang) {
        if (isset(self::$supportedLanguages[$lang])) {
            self::$currentLang = $lang;
        } else {
            self::$currentLang = self::$fallbackLang;
        }
    }
    
    /**
     * Obtener idioma actual
     */
    public static function getCurrentLanguage() {
        return self::$currentLang;
    }
    
    /**
     * Cargar traducciones
     */
    private static function loadTranslations() {
        $file = __DIR__ . '/../lang/' . self::$currentLang . '.php';
        
        if (file_exists($file)) {
            self::$translations = require $file;
        } else {
            // Cargar fallback
            $fallbackFile = __DIR__ . '/../lang/' . self::$fallbackLang . '.php';
            if (file_exists($fallbackFile)) {
                self::$translations = require $fallbackFile;
            }
        }
    }
    
    /**
     * Traducir una clave
     */
    public static function get($key, $default = null) {
        if (isset(self::$translations[$key])) {
            return self::$translations[$key];
        }
        
        // Si no existe, intentar con fallback
        if (self::$currentLang !== self::$fallbackLang) {
            $fallbackFile = __DIR__ . '/../lang/' . self::$fallbackLang . '.php';
            if (file_exists($fallbackFile)) {
                $fallback = require $fallbackFile;
                if (isset($fallback[$key])) {
                    return $fallback[$key];
                }
            }
        }
        
        return $default ?? $key;
    }
    
    /**
     * Formatear fecha según idioma
     */
    public static function formatDate($date, $includeTime = false) {
        $format = self::get($includeTime ? 'date.format_time' : 'date.format');
        return date($format, strtotime($date));
    }
}

// Helper function global
function __($key, $default = null) {
    return Lang::get($key, $default);
}

// Inicializar idioma basado en cookie/sesión
if (isset($_COOKIE['lang'])) {
    Lang::init($_COOKIE['lang']);
} else {
    Lang::init();
}
