<?php
$router = $GLOBALS['router'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : ('Mikisito - ' . (isset($router) ? ucfirst($router->getCurrentPage()) : 'Home')); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/xp.css">
</head>
<body>
    <!-- Header fijo XP -->
    <header class="xp-header">
        <div class="nav-grid">
            <a href="<?php echo BASE_URL; ?>/" class="nav-item <?php echo (isset($router) && $router->isActive('home')) ? 'active' : ''; ?>">Inicio</a>
            <a href="<?php echo BASE_URL; ?>/diario" class="nav-item <?php echo (isset($router) && $router->isActive('diario')) ? 'active' : ''; ?>">Diario Mikisito</a>
            <a href="<?php echo BASE_URL; ?>/proyectos" class="nav-item <?php echo (isset($router) && $router->isActive('proyectos')) ? 'active' : ''; ?>">Proyectos</a>
            <a href="<?php echo BASE_URL; ?>/juegos" class="nav-item <?php echo (isset($router) && $router->isActive('juegos')) ? 'active' : ''; ?>">Juegos (Mantenimiento)</a>
            <a href="<?php echo BASE_URL; ?>/foro" class="nav-item <?php echo (isset($router) && $router->isActive('foro')) ? 'active' : ''; ?>">Foro</a>
            <a href="<?php echo BASE_URL; ?>/youtube" class="nav-item <?php echo (isset($router) && $router->isActive('youtube')) ? 'active' : ''; ?>">YouTube</a>
            <a href="<?php echo BASE_URL; ?>/contacto" class="nav-item <?php echo (isset($router) && $router->isActive('contacto')) ? 'active' : ''; ?>">Contacto</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
