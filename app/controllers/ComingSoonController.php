<?php
/**
 * Controlador para páginas en desarrollo
 * Muestra avisos de "Coming Soon" o "En Desarrollo"
 */

class ComingSoonController {
    
    public function games() {
        $pageTitle = 'Videojuegos - En Desarrollo | RetroSpace';
        $section = 'games';
        $icon = '🎮';
        $title = 'Videojuegos';
        $message = 'Esta sección está actualmente en desarrollo. Pronto podrás encontrar aquí mis proyectos de videojuegos, tutoriales de GameMaker y más contenido gaming.';
        $features = [
            '🎲 Listado de mis videojuegos',
            '📚 Tutoriales de GameMaker',
            '🕹️ Demos jugables',
            '💾 Descargas y recursos',
            '📝 Devlogs de desarrollo',
            '🎨 Assets y recursos gráficos'
        ];
        
        require __DIR__ . '/../views/coming-soon/index.php';
    }
    
    public function youtube() {
        $pageTitle = 'YouTube - En Desarrollo | RetroSpace';
        $section = 'youtube';
        $icon = '📺';
        $title = 'YouTube';
        $message = 'Esta sección está en construcción. Pronto podrás ver aquí mis últimos videos, series y tutoriales directamente desde mi canal de YouTube.';
        $features = [
            '📹 Últimos videos subidos',
            '🎬 Series y playlists',
            '👨‍💻 Tutoriales de programación',
            '🎮 Gameplays y reviews',
            '💡 Tips & tricks',
            '🔴 Notificaciones de directos'
        ];
        
        require __DIR__ . '/../views/coming-soon/index.php';
    }
}
