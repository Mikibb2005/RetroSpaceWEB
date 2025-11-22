<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="xp-window">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            <span>🖥️</span> MikisitoOS - Home
        </div>
        <div class="xp-buttons">
            <button class="xp-btn">_</button>
            <button class="xp-btn">□</button>
            <button class="xp-btn">✕</button>
        </div>
    </div>
    <div class="xp-content">
        <h2>¡Bienvenido a MikisitoOS!</h2>
        <p>Explora mi mundo de proyectos, devlogs y comunidad.</p>
        
        <div class="home-grid">
            <!-- Últimos posts -->
            <div class="xp-window">
                <div class="xp-titlebar">
                    <span>📖 Últimos Posts</span>
                </div>
                <div class="xp-content">
                    <ul class="xp-list">
                        <?php foreach ($datos['ultimos_posts'] as $post): ?>
                            <li class="xp-list-item" onclick="location.href='/diario/<?php echo $post['id']; ?>'">
                                <strong><?php echo htmlspecialchars($post['titulo']); ?></strong><br>
                                <small>por <?php echo $post['autor']; ?> - <?php echo $post['fecha_publicacion']; ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Proyectos destacados -->
            <div class="xp-window">
                <div class="xp-titlebar">
                    <span>⚡ Proyectos</span>
                </div>
                <div class="xp-content">
                    <ul class="xp-list">
                        <?php foreach ($datos['proyectos_destacados'] as $proyecto): ?>
                            <li class="xp-list-item" onclick="location.href='/proyectos'">
                                <strong><?php echo htmlspecialchars($proyecto['titulo']); ?></strong><br>
                                <small><?php echo $proyecto['categoria']; ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="xp-window">
                <div class="xp-titlebar">
                    <span>📊 Estadísticas</span>
                </div>
                <div class="xp-content">
                    <p>Usuarios: <strong><?php echo $datos['estadisticas']['usuarios']; ?></strong></p>
                    <p>Posts: <strong><?php echo $datos['estadisticas']['posts']; ?></strong></p>
                    <p>Proyectos: <strong><?php echo $datos['estadisticas']['proyectos']; ?></strong></p>
                </div>
            </div>

            <!-- Último video -->
            <div class="xp-window">
                <div class="xp-titlebar">
                    <span>▶️ Último Video</span>
                </div>
                <div class="xp-content">
                    <iframe width="100%" height="200" 
                            src="<?php echo $datos['ultimo_video']; ?>" 
                            frameborder="0" allowfullscreen></iframe>
                </div>
            </div>

            <!-- Contacto rápido -->
            <div class="xp-window" style="grid-column: span 2;">
                <div class="xp-titlebar">
                    <span>✉️ Contacto Rápido</span>
                </div>
                <div class="xp-content">
                    <form action="/contacto" method="post">
                        <input type="text" name="nombre" placeholder="Tu nombre" class="xp-input">
                        <input type="email" name="email" placeholder="Tu email" class="xp-input">
                        <textarea name="mensaje" placeholder="Mensaje..." class="xp-textarea"></textarea>
                        <button type="submit" class="xp-button">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
