<?php
$router = $GLOBALS['router'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : ('RetroSpace - ' . (isset($router) ? ucfirst($router->getCurrentPage()) : 'Home')); ?></title>
    <link id="theme-style" rel="stylesheet" href="<?php echo BASE_URL; ?>/css/xp.css">
    <!-- CSS Responsive (se carga después del tema) -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/responsive.css">
    <script>
        // Cargar tema guardado inmediatamente para evitar flash
        (function() {
            var savedTheme = localStorage.getItem('retro_theme');
            if (savedTheme) {
                var link = document.getElementById('theme-style');
                link.href = '<?php echo BASE_URL; ?>/css/' + savedTheme + '.css';
            }
        })();
    </script>
</head>
<body>
    <!-- Header fijo XP -->
    <header class="xp-header">
        <!-- Menú hamburguesa para móvil -->
        <button class="mobile-menu-toggle mobile-only" id="mobile-menu-toggle" aria-label="Toggle menu">
            ☰
        </button>
        
        <div class="nav-grid" id="main-nav">
            <a href="<?php echo BASE_URL; ?>/" class="nav-item <?php echo (isset($router) && $router->isActive('home')) ? 'active' : ''; ?>"><?php echo __('nav.home'); ?></a>
            <a href="<?php echo BASE_URL; ?>/proyectos" class="nav-item <?php echo (isset($router) && $router->isActive('proyectos')) ? 'active' : ''; ?>"><?php echo __('nav.projects'); ?></a>
            <a href="<?php echo BASE_URL; ?>/foro" class="nav-item <?php echo (isset($router) && $router->isActive('foro')) ? 'active' : ''; ?>"><?php echo __('nav.forum'); ?></a>
            <a href="<?php echo BASE_URL; ?>/juegos" class="nav-item <?php echo (isset($router) && $router->isActive('juegos')) ? 'active' : ''; ?>"><?php echo __('nav.games'); ?></a>
            <a href="<?php echo BASE_URL; ?>/youtube" class="nav-item <?php echo (isset($router) && $router->isActive('youtube')) ? 'active' : ''; ?>"><?php echo __('nav.youtube'); ?></a>
            <a href="<?php echo BASE_URL; ?>/contacto" class="nav-item <?php echo (isset($router) && $router->isActive('contacto')) ? 'active' : ''; ?>"><?php echo __('nav.contact'); ?></a>
            
            <!-- Mobile Only Settings (Moved from Footer) -->
            <div class="mobile-settings mobile-only">
                <div class="mobile-separator"></div>
                
                <!-- Mobile User Menu -->
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="mobile-user-info">
                        👤 <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/perfil" class="nav-item">Mi Perfil</a>
                    <a href="<?php echo BASE_URL; ?>/logout" class="nav-item">Salir</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/login" class="nav-item">Iniciar Sesión</a>
                    <a href="<?php echo BASE_URL; ?>/registro" class="nav-item">Registrarse</a>
                <?php endif; ?>

                <div class="mobile-separator"></div>

                <!-- Mobile Theme Selector -->
                <div class="mobile-selector-group">
                    <label for="mobile-theme-selector">🎨 Tema:</label>
                    <select id="mobile-theme-selector" onchange="changeTheme(this.value)" class="xp-select full-width">
                        <option value="win98">Windows 98</option>
                        <option value="xp">Windows XP</option>
                        <option value="vista">Windows Vista</option>
                        <option value="win7">Windows 7</option>
                        <option value="win8">Windows 8</option>
                        <option value="win10">Windows 10</option>
                        <option value="win11">Windows 11</option>
                        <option value="macos">macOS</option>
                    </select>
                </div>

                <!-- Mobile Language Selector -->
                <div class="mobile-selector-group">
                    <label for="mobile-language-selector">🌐 Idioma:</label>
                    <select id="mobile-language-selector" onchange="changeLanguage(this.value)" class="xp-select full-width">
                        <option value="es">🇪🇸 Español</option>
                        <option value="en">🇬🇧 English</option>
                        <option value="ca">🏴 Català</option>
                        <option value="fr">🇫🇷 Français</option>
                        <option value="de">🇩🇪 Deutsch</option>
                        <option value="it">🇮🇹 Italiano</option>
                        <option value="pt">🇵🇹 Português</option>
                        <option value="ru">🇷🇺 Русский</option>
                        <option value="ja">🇯🇵 日本語</option>
                        <option value="zh">🇨🇳 中文</option>
                    </select>
                </div>

                <div class="mobile-separator"></div>
                
                <!-- Mobile Clock -->
                <div class="mobile-clock" style="color: white; text-align: center; font-weight: bold; padding: 10px;">
                    --:--
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
