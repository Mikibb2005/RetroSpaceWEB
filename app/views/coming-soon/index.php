<?php
/**
 * Vista de "Coming Soon" / En Desarrollo
 * Estilo retro Windows con mensaje de construcción
 */
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container" style="max-width: 900px; margin: 40px auto; padding: 20px;">
    
    <!-- Ventana de "En Desarrollo" estilo Windows -->
    <div class="xp-window" style="margin: 0 auto;">
        <!-- Barra de título -->
        <div class="xp-window-title">
            <span><?php echo $icon; ?> <?php echo htmlspecialchars($title); ?> - Información del Sistema</span>
            <div class="xp-window-controls">
                <button class="xp-btn-minimize">_</button>
                <button class="xp-btn-maximize">□</button>
            </div>
        </div>
        
        <!-- Contenido -->
        <div class="xp-window-content" style="padding: 40px 30px; text-align: center;">
            
            <!-- Icono grande de construcción -->
            <div style="font-size: 120px; margin-bottom: 20px; animation: pulse 2s ease-in-out infinite;">
                <?php echo $icon; ?>
            </div>
            
            <!-- Título -->
            <h1 style="color: #0066cc; margin-bottom: 15px; font-size: 32px;">
                🚧 En Desarrollo
            </h1>
            
            <!-- Mensaje principal -->
            <div style="background: #fff3cd; border: 2px solid #ffc107; padding: 20px; margin: 20px 0; border-radius: 4px; text-align: left;">
                <div style="display: flex; align-items: flex-start; gap: 15px;">
                    <div style="font-size: 32px; flex-shrink: 0;">⚠️</div>
                    <div>
                        <strong style="display: block; margin-bottom: 10px; font-size: 18px; color: #856404;">
                            Esta sección está actualmente en construcción
                        </strong>
                        <p style="margin: 0; color: #856404; line-height: 1.6;">
                            <?php echo htmlspecialchars($message); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Características planeadas -->
            <div style="margin: 30px 0; text-align: left;">
                <h3 style="color: #0066cc; margin-bottom: 15px; font-size: 20px;">
                    📋 Características Planeadas
                </h3>
                
                <div style="background: #f0f0f0; border: 2px solid #ddd; padding: 20px; border-radius: 4px;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <?php foreach ($features as $feature): ?>
                            <li style="padding: 10px 0; border-bottom: 1px solid #ddd;">
                                <?php echo htmlspecialchars($feature); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Barra de progreso simulada -->
            <div style="margin: 30px 0;">
                <h4 style="color: #666; margin-bottom: 10px;">Estado de Desarrollo</h4>
                <div style="background: #e0e0e0; border: 1px solid #999; height: 30px; border-radius: 4px; overflow: hidden; position: relative;">
                    <div style="background: linear-gradient(to right, #4CAF50, #8BC34A); height: 100%; width: 35%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px; transition: width 0.3s ease;">
                        35% Completado
                    </div>
                </div>
                <p style="color: #666; font-size: 13px; margin-top: 10px;">
                    <em>Estimado de lanzamiento: Próximamente</em>
                </p>
            </div>
            
            <!-- Información adicional -->
            <div style="background: #e3f2fd; border-left: 4px solid #2196F3; padding: 15px; margin: 20px 0; text-align: left;">
                <strong style="color: #1976D2; display: block; margin-bottom: 8px;">💡 ¿Quieres ser notificado?</strong>
                <p style="margin: 0; color: #1565C0; font-size: 14px;">
                    Mientras tanto, puedes visitar las demás secciones de RetroSpace o contactarme para sugerencias sobre qué te gustaría ver aquí.
                </p>
            </div>
            
            <!-- Botones de acción -->
            <div style="margin-top: 30px; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo BASE_URL; ?>/" class="xp-button" style="padding: 12px 30px; text-decoration: none; display: inline-block; background: #0066cc; color: white; font-weight: bold; border-radius: 3px;">
                    🏠 Volver al Inicio
                </a>
                
                <a href="<?php echo BASE_URL; ?>/proyectos" class="xp-button" style="padding: 12px 30px; text-decoration: none; display: inline-block;">
                    💻 Ver Proyectos
                </a>
                
                <a href="<?php echo BASE_URL; ?>/contacto" class="xp-button" style="padding: 12px 30px; text-decoration: none; display: inline-block;">
                    📧 Contactar
                </a>
            </div>
            
            <!-- Timestamp -->
            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #999; font-size: 12px;">
                <p style="margin: 0;">
                    🕐 Última actualización: <?php echo date('d/m/Y'); ?> | 
                    Página visualizada: <?php echo date('H:i:s'); ?>
                </p>
            </div>
            
        </div>
    </div>
    
    <!-- Easter egg: Consola de desarrollador simulada -->
    <div class="xp-window" style="margin-top: 20px; background: #000; color: #0f0; font-family: 'Courier New', monospace;">
        <div class="xp-window-title" style="background: #000; color: #0f0; border-bottom: 1px solid #0f0;">
            <span>C:\RetroSpace\<?php echo $section; ?>\&gt;_</span>
        </div>
        <div class="xp-window-content" style="padding: 15px; background: #000; color: #0f0; font-size: 13px;">
            <pre style="margin: 0; color: #0f0;">
> Iniciando módulo "<?php echo $title; ?>"...
> Cargando componentes... [<span class="loading-dots"></span>]
> ERROR: Módulo no encontrado
> Estado: EN_DESARROLLO
> Progreso: 35%
> Fecha estimada: PRONTO™
> 
> Tip: Mientras tanto, visita /proyectos o /foro
> 
> Presiona cualquier tecla para continuar..._
            </pre>
        </div>
    </div>
    
</div>

<!-- Animaciones CSS -->
<style>
@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.8; }
}

@keyframes loadingDots {
    0%, 20% { content: '.'; }
    40% { content: '..'; }
    60%, 100% { content: '...'; }
}

.loading-dots::after {
    content: '';
    animation: loadingDots 1.5s infinite;
}

/* Responsive para móvil */
@media (max-width: 768px) {
    .xp-window-content {
        padding: 20px 15px !important;
    }
    
    h1 {
        font-size: 24px !important;
    }
    
    .xp-window-content > div:first-child {
        font-size: 80px !important;
    }
    
    .xp-window-content a {
        width: 100%;
        text-align: center;
    }
}
</style>

<!-- Script para Easter Egg -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animación de tecleo en consola
    const consoleText = document.querySelector('pre');
    let konamiCode = [];
    const konamiPattern = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];
    
    document.addEventListener('keydown', function(e) {
        konamiCode.push(e.key);
        konamiCode = konamiCode.slice(-10);
        
        if (konamiCode.join(',') === konamiPattern.join(',')) {
            consoleText.innerHTML += '\n\n> CÓDIGO KONAMI DETECTADO! 🎮\n> Desbloqueando modo retro...\n> ¡Eres un verdadero gamer!';
            consoleText.style.color = '#ff00ff';
            
            setTimeout(() => {
                consoleText.style.color = '#0f0';
            }, 3000);
        }
    });
});
</script>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>
