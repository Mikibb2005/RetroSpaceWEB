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
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
