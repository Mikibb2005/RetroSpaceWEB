<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="xp-window">
    <div class="xp-titlebar">
        <span><?php echo htmlspecialchars($post['titulo']); ?></span>
    </div>
    <div class="xp-content">
        <div style="margin-bottom: 20px;">
            <small>por <?php echo $post['autor']; ?> | <?php echo $post['fecha_publicacion']; ?></small>
        </div>
        
        <?php if ($post['imagen']): ?>
            <img src="<?php echo htmlspecialchars($post['imagen']); ?>" 
                 alt="Imagen del post" style="max-width:100%; margin:10px 0;">
        <?php endif; ?>
        
        <div style="margin: 20px 0;">
            <?php echo nl2br(htmlspecialchars($post['contenido'])); ?>
        </div>
        
        <?php if ($post['codigo_embed']): ?>
            <div style="background:#000; color:#0f0; padding:10px; margin:15px 0; font-family:monospace;">
                <pre><?php echo htmlspecialchars($post['codigo_embed']); ?></pre>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
