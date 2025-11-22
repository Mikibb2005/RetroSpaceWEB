<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="xp-window" style="max-width: 1000px; margin: 20px auto;">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            📓 Diario - Blog Personal
        </div>
    </div>
    <div class="xp-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">Entradas del Diario</h2>
            <?php if ($isAdmin): ?>
                <a href="<?php echo BASE_URL; ?>/diario/crear" class="xp-button" style="font-weight: bold;">✍️ Nueva Entrada</a>
            <?php endif; ?>
        </div>
        
        <?php if (empty($posts)): ?>
            <p style="text-align: center; padding: 40px; color: #666;">
                No hay entradas todavía.
                <?php if ($isAdmin): ?>
                    <br><a href="<?php echo BASE_URL; ?>/diario/crear">¡Crea la primera entrada!</a>
                <?php endif; ?>
            </p>
        <?php else: ?>
            <ul class="xp-list">
                <?php foreach ($posts as $post): ?>
                    <li class="xp-list-item" style="cursor: pointer;" onclick="location.href='<?php echo BASE_URL; ?>/diario/<?php echo $post['id']; ?>'">
                        <h3 style="margin: 0 0 10px 0; color: #0066cc;"><?php echo htmlspecialchars($post['titulo']); ?></h3>
                        <p style="margin: 5px 0;"><?php echo substr(strip_tags($post['contenido']), 0, 200); ?>...</p>
                        <small>👤 por <strong><?php echo htmlspecialchars($post['autor']); ?></strong> | 📅 <?php echo date('d/m/Y', strtotime($post['fecha_publicacion'])); ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
