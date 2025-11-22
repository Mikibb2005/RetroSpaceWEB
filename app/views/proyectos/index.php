<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="xp-window">
    <div class="xp-titlebar">
        <span>⚙️ Proyectos - Portfolio</span>
    </div>
    <div class="xp-content">
        <h2>Proyectos por Categoría</h2>
        
        <?php if ($this->user->isAdmin()): ?>
            <button class="xp-button" onclick="location.href='/proyectos/crear'">+ Nuevo Proyecto</button>
        <?php endif; ?>
        
        <!-- Filtros -->
        <div class="filter-buttons">
            <button class="xp-button" onclick="location.href='/proyectos'">Todos</button>
            <button class="xp-button" onclick="location.href='/proyectos?categoria=Programacion'">Programación</button>
            <button class="xp-button" onclick="location.href='/proyectos?categoria=Hardware'">Hardware</button>
            <button class="xp-button" onclick="location.href='/proyectos?categoria=Mods'">Mods</button>
            <button class="xp-button" onclick="location.href='/proyectos?categoria=GameMaker'">GameMaker</button>
        </div>
        
        <div class="home-grid">
            <?php foreach ($proyectos as $proyecto): ?>
                <div class="xp-window">
                    <div class="xp-titlebar">
                        <span><?php echo $proyecto['categoria']; ?></span>
                    </div>
                    <div class="xp-content">
                        <h3><?php echo htmlspecialchars($proyecto['titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($proyecto['descripcion']); ?></p>
                        
                        <?php if ($proyecto['imagen']): ?>
                            <img src="<?php echo $proyecto['imagen']; ?>" style="width:100%; margin:10px 0;">
                        <?php endif; ?>
                        
                        <?php if ($proyecto['video_url']): ?>
                            <iframe src="<?php echo $proyecto['video_url']; ?>" width="100%" height="150"></iframe>
                        <?php endif; ?>
                        
                        <div style="margin-top:10px;">
                            <?php if ($proyecto['link1']): ?>
                                <a href="<?php echo $proyecto['link1']; ?>" target="_blank" class="xp-button">Demo</a>
                            <?php endif; ?>
                            <?php if ($proyecto['link2']): ?>
                                <a href="<?php echo $proyecto['link2']; ?>" target="_blank" class="xp-button">GitHub</a>
                            <?php endif; ?>
                        </div>
                        
                        <small>por <?php echo $proyecto['autor']; ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
