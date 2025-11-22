<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="xp-window">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            <span>📓</span> Diario Mikisito - Blog Personal
        </div>
        <div class="xp-buttons">
            <button class="xp-btn">_</button>
            <button class="xp-btn">□</button>
            <button class="xp-btn">✕</button>
        </div>
    </div>
    <div class="xp-content">
        <h2>Entradas del Diario</h2>
        
        <?php if ($this->user->isAdmin()): ?>
            <button class="xp-button" onclick="location.href='/diario/crear'">+ Nueva Entrada</button>
        <?php endif; ?>
        
        <ul class="xp-list">
            <?php foreach ($posts as $post): ?>
                <li class="xp-list-item" onclick="location.href='/diario/<?php echo $post['id']; ?>'">
                    <h3><?php echo htmlspecialchars($post['titulo']); ?></h3>
                    <p><?php echo substr(strip_tags($post['contenido']), 0, 150); ?>...</p>
                    <small>por <?php echo $post['autor']; ?> | <?php echo $post['fecha_publicacion']; ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
