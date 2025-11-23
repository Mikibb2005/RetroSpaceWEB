<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="xp-window">
    <div class="xp-titlebar">
        <span>💬 <?php echo __('forum.title'); ?></span>
    </div>
    <div class="xp-content">
        <h2><?php echo __('forum.threads'); ?></h2>
        
        <?php if ($this->user->isLogged()): ?>
            <button class="xp-button" onclick="location.href='<?php echo BASE_URL; ?>/foro/crear'"><?php echo __('forum.new_thread'); ?></button>
        <?php else: ?>
            <p><a href="<?php echo BASE_URL; ?>/login" class="xp-button"><?php echo __('user.login'); ?></a></p>
        <?php endif; ?>
        
        <?php if (empty($hilos)): ?>
            <p style="text-align: center; padding: 20px; color: #666;">
                <?php echo __('forum.none'); ?>
            </p>
        <?php else: ?>
            <ul class="xp-list">
                <?php foreach ($hilos as $hilo): ?>
                    <li class="xp-list-item" onclick="location.href='<?php echo BASE_URL; ?>/foro/hilo/<?php echo $hilo['id']; ?>'">
                        <h3>
                            <span data-translatable="title" data-original-lang="es" data-original-text="<?php echo htmlspecialchars($hilo['titulo']); ?>">
                                <?php echo htmlspecialchars($hilo['titulo']); ?>
                            </span>
                            <?php if ($hilo['sticky']): ?><span style="color:red;">[STICKY]</span><?php endif; ?>
                        </h3>
                        <p data-translatable="description" data-original-lang="es" data-original-text="<?php echo htmlspecialchars($hilo['descripcion']); ?>">
                            <?php echo htmlspecialchars($hilo['descripcion']); ?>
                        </p>
                        <small>
                            <?php echo __('forum.started_by'); ?> <?php echo htmlspecialchars($hilo['autor']); ?> | 
                            <?php echo Lang::formatDate($hilo['fecha_creacion']); ?> | 
                            <?php echo $hilo['comentarios']; ?> <?php echo __('forum.replies'); ?>
                        </small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
