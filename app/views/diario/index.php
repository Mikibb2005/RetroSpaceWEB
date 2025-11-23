<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="xp-window" style="max-width: 800px; margin: 20px auto;">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            📖 <?php echo __('diary.title'); ?>
        </div>
    </div>
    <div class="xp-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;"><?php echo __('diary.entries'); ?></h2>
            <?php if ($isAdmin): ?>
                <a href="<?php echo BASE_URL; ?>/diario/crear" class="xp-button" style="font-weight: bold;"><?php echo __('diary.new'); ?></a>
            <?php endif; ?>
        </div>
        
        <?php if (empty($posts)): ?>
            <p style="text-align: center; padding: 40px; color: #666;">
                📭 <?php echo __('diary.none'); ?>
                <?php if ($isAdmin): ?>
                    <br><a href="<?php echo BASE_URL; ?>/diario/crear"><?php echo __('diary.create_first'); ?></a>
                <?php endif; ?>
            </p>
        <?php else: ?>
            <ul class="xp-list">
                <?php foreach ($posts as $post): ?>
                    <li class="xp-list-item" style="cursor: pointer;" onclick="location.href='<?php echo BASE_URL; ?>/diario/<?php echo $post['id']; ?>'">
                        <h3 style="margin: 0 0 10px 0;">
                            <a href="<?php echo BASE_URL; ?>/diario/<?php echo $post['id']; ?>" 
                               style="color: #0066cc; text-decoration: none;"
                               onclick="event.stopPropagation();">
                                <span data-translatable="title" data-original-lang="es" data-original-text="<?php echo htmlspecialchars($post['titulo']); ?>">
                                    <?php echo htmlspecialchars($post['titulo']); ?>
                                </span>
                            </a>
                        </h3>
                        
                        <div style="color: #333; margin-bottom: 10px;" data-translatable="description" data-original-lang="es" data-original-text="<?php 
                            $contenido = strip_tags($post['contenido']);
                            if (strlen($contenido) > 200) {
                                echo htmlspecialchars(substr($contenido, 0, 200)) . '...';
                            } else {
                                echo htmlspecialchars($contenido);
                            }
                        ?>">
                            <?php 
                            $contenido = strip_tags($post['contenido']);
                            if (strlen($contenido) > 200) {
                                echo htmlspecialchars(substr($contenido, 0, 200)) . '...';
                            } else {
                                echo htmlspecialchars($contenido);
                            }
                            ?>
                        </div>
                        
                        <small>
                            👤 <?php echo __('diary.by_author'); ?> <strong><?php echo htmlspecialchars($post['autor']); ?></strong> 
                            | 📅 <?php echo Lang::formatDate($post['fecha_publicacion']); ?>
                        </small>
                        
                        <?php if (!empty($post['imagen'])): ?>
                            <div style="margin-top: 10px;">
                                <img src="<?php echo BASE_URL . htmlspecialchars($post['imagen']); ?>" 
                                     alt="Imagen del post" 
                                     style="max-width: 100%; max-height: 150px; border: 1px solid #999; object-fit: cover;">
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
