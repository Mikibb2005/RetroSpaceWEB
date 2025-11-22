<?php require __DIR__ . '/../layout/header.php'; ?>

<style>
.home-main {
    max-width: 1200px;
    margin: 20px auto;
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

.home-sidebar {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.foro-item {
    cursor: pointer;
    transition: background-color 0.2s;
}

.foro-item:hover {
    background-color: #e0e0e0;
}

@media (max-width: 768px) {
    .home-main {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="home-main">
    <!-- Columna Principal: Últimos Hilos del Foro -->
    <div>
        <div class="xp-window">
            <div class="xp-titlebar">
                <div class="xp-titlebar-text">
                    💬 Últimas Discusiones del Foro
                </div>
            </div>
            <div class="xp-content">
                <?php if (empty($datos['ultimos_hilos'])): ?>
                    <p style="text-align: center; padding: 20px; color: #666;">
                        No hay hilos todavía. <a href="<?php echo BASE_URL; ?>/foro/crear">¡Sé el primero en crear uno!</a>
                    </p>
                <?php else: ?>
                    <ul class="xp-list">
                        <?php foreach ($datos['ultimos_hilos'] as $hilo): ?>
                            <li class="xp-list-item foro-item" onclick="location.href='<?php echo BASE_URL; ?>/foro/hilo/<?php echo $hilo['id']; ?>'">
                                <div style="display: flex; justify-content: space-between; align-items: start;">
                                    <div style="flex: 1;">
                                        <strong style="font-size: 1.1em; color: #0066cc;">
                                            <?php echo htmlspecialchars($hilo['titulo']); ?>
                                        </strong>
                                        <br>
                                        <span style="display: inline-block; margin-top: 5px; padding: 2px 8px; background: #f0f0f0; border: 1px solid #999; font-size: 0.85em;">
                                            📁 <?php echo htmlspecialchars(ucfirst($hilo['categoria'])); ?>
                                        </span>
                                        <br>
                                        <small style="color: #666;">
                                            👤 por <strong><?php echo htmlspecialchars($hilo['autor']); ?></strong>
                                            • 📅 <?php echo date('d/m/Y H:i', strtotime($hilo['fecha_creacion'])); ?>
                                        </small>
                                    </div>
                                    <div style="text-align: right; min-width: 80px;">
                                        <div style="background: #0066cc; color: white; padding: 5px 10px; border-radius: 3px; font-weight: bold;">
                                            💬 <?php echo $hilo['total_comentarios']; ?>
                                        </div>
                                        <small style="color: #666; display: block; margin-top: 3px;">respuestas</small>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div style="text-align: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #999;">
                        <a href="<?php echo BASE_URL; ?>/foro" class="xp-button">Ver Todos los Hilos →</a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="<?php echo BASE_URL; ?>/foro/crear" class="xp-button" style="margin-left: 10px;">✍️ Crear Hilo Nuevo</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Barra Lateral: Estadísticas y Widgets -->
    <div class="home-sidebar">
        <!-- Estadísticas -->
        <div class="xp-window">
            <div class="xp-titlebar">
                <span>📊 Estadísticas de RetroSpace</span>
            </div>
            <div class="xp-content">
                <p>👥 Usuarios: <strong><?php echo $datos['estadisticas']['usuarios']; ?></strong></p>
                <p>💬 Hilos: <strong><?php echo $datos['estadisticas']['hilos']; ?></strong></p>
                <p>💭 Comentarios: <strong><?php echo $datos['estadisticas']['comentarios']; ?></strong></p>
                <p>📝 Posts Blog: <strong><?php echo $datos['estadisticas']['posts']; ?></strong></p>
                <p>⚡ Proyectos: <strong><?php echo $datos['estadisticas']['proyectos']; ?></strong></p>
            </div>
        </div>

        <!-- Últimos Posts del Blog -->
        <div class="xp-window">
            <div class="xp-titlebar">
                <span>📖 Últimas Entradas del Blog</span>
            </div>
            <div class="xp-content">
                <?php if (empty($datos['ultimos_posts'])): ?>
                    <p style="color: #666; font-size: 0.9em;">No hay posts todavía</p>
                <?php else: ?>
                    <ul class="xp-list">
                        <?php foreach ($datos['ultimos_posts'] as $post): ?>
                            <li class="xp-list-item" style="cursor: pointer;" onclick="location.href='<?php echo BASE_URL; ?>/diario/<?php echo $post['id']; ?>'">
                                <strong><?php echo htmlspecialchars($post['titulo']); ?></strong><br>
                                <small>por <?php echo htmlspecialchars($post['autor']); ?> - <?php echo date('d/m/Y', strtotime($post['fecha_publicacion'])); ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Proyectos destacados -->
        <div class="xp-window">
            <div class="xp-titlebar">
                <span>⚡ Proyectos Destacados</span>
            </div>
            <div class="xp-content">
                <?php if (empty($datos['proyectos_destacados'])): ?>
                    <p style="color: #666; font-size: 0.9em;">No hay proyectos todavía</p>
                <?php else: ?>
                    <ul class="xp-list">
                        <?php foreach ($datos['proyectos_destacados'] as $proyecto): ?>
                            <li class="xp-list-item" style="cursor: pointer;" onclick="location.href='<?php echo BASE_URL; ?>/proyectos'">
                                <strong><?php echo htmlspecialchars($proyecto['titulo']); ?></strong><br>
                                <small><?php echo htmlspecialchars($proyecto['categoria']); ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Último video -->
        <div class="xp-window">
            <div class="xp-titlebar">
                <span>▶️ Último Video</span>
            </div>
            <div class="xp-content">
                <iframe width="100%" height="180" 
                        src="<?php echo $datos['ultimo_video']; ?>" 
                        frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
