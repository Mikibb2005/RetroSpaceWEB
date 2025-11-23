<?php 
$pageTitle = htmlspecialchars($actualizacion['titulo']) . ' - ' . htmlspecialchars($proyecto['titulo']);
require __DIR__ . '/../layout/header.php'; 
?>

<div class="xp-window" style="max-width: 800px; margin: 20px auto;">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            📜 <?php echo htmlspecialchars($proyecto['titulo']); ?>: <?php echo htmlspecialchars($actualizacion['titulo']); ?>
        </div>
    </div>
    <div class="xp-content">
        <div style="margin-bottom: 15px;">
            <a href="<?php echo BASE_URL; ?>/proyectos/<?php echo $proyecto['id']; ?>" class="xp-button">← Volver al Proyecto</a>
        </div>

        <h2 style="margin-top: 0;"><?php echo htmlspecialchars($actualizacion['titulo']); ?></h2>
        <div style="font-size: 0.9em; color: #666; margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
            Publicado el <?php echo date('d/m/Y H:i', strtotime($actualizacion['fecha_creacion'])); ?>
            por <strong><?php echo htmlspecialchars($proyecto['autor']); ?></strong>
        </div>

        <div style="line-height: 1.6; font-size: 1.1em; margin-bottom: 20px;">
            <?php echo nl2br(htmlspecialchars($actualizacion['contenido'])); ?>
        </div>

        <!-- Galería (Thumbnails) -->
        <?php 
        $archivos = isset($actualizacion['archivos']) ? json_decode($actualizacion['archivos'], true) : [];
        if (!empty($archivos)): 
        ?>
            <div style="margin-bottom: 20px;">
                <strong style="display: block; margin-bottom: 10px;">📸 Multimedia:</strong>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <?php foreach ($archivos as $archivo): 
                        $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
                        $isVideo = in_array($ext, ['mp4', 'webm', 'ogg']);
                    ?>
                        <div onclick="openModal('<?php echo BASE_URL . htmlspecialchars($archivo); ?>', <?php echo $isVideo ? 'true' : 'false'; ?>)" style="cursor: pointer; border: 1px solid #ccc; padding: 2px; background: #f9f9f9;">
                            <?php if ($isVideo): ?>
                                <div style="width: 150px; height: 100px; background: #000; display: flex; align-items: center; justify-content: center; color: white; font-size: 2em;">▶️</div>
                            <?php else: ?>
                                <img src="<?php echo BASE_URL . htmlspecialchars($archivo); ?>" style="width: 150px; height: 100px; object-fit: cover; display: block;">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Sección de Comentarios -->
        <div style="margin-top: 40px; border-top: 2px solid #999; padding-top: 20px;">
            <h3>💬 Comentarios (<?php echo count($comentarios); ?>)</h3>

            <!-- Lista de comentarios -->
            <div style="margin-bottom: 30px;">
                <?php if (empty($comentarios)): ?>
                    <p>No hay comentarios aún. ¡Sé el primero!</p>
                <?php else: ?>
                    <?php foreach ($comentarios as $com): 
                        $esAutorProyecto = ($com['autor_id'] == $proyecto['autor_id']);
                        $estiloAutor = $esAutorProyecto ? 'border: 2px solid #ffcc00; background: #fffff0;' : 'border: 1px solid #ccc; background: #f9f9f9;';
                        $badgeAutor = $esAutorProyecto ? '<span style="background: #ffcc00; color: black; font-size: 0.8em; padding: 1px 4px; border-radius: 3px; margin-left: 5px; border: 1px solid #999;">👑 AUTOR</span>' : '';
                    ?>
                        <div style="margin-bottom: 15px; padding: 10px; border-radius: 5px; <?php echo $estiloAutor; ?>">
                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                <?php if ($com['avatar']): ?>
                                    <img src="<?php echo htmlspecialchars($com['avatar']); ?>" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px; border: 1px solid #666;">
                                <?php else: ?>
                                    <span style="font-size: 20px; margin-right: 10px;">👤</span>
                                <?php endif; ?>
                                
                                <strong><?php echo htmlspecialchars($com['username']); ?></strong>
                                <?php echo $badgeAutor; ?>
                                <small style="margin-left: auto; color: #666;"><?php echo date('d/m/Y H:i', strtotime($com['fecha_creacion'])); ?></small>
                            </div>
                            <div style="padding-left: 40px;">
                                <?php echo nl2br(htmlspecialchars($com['contenido'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Formulario -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div style="background: #eee; padding: 15px; border: 1px solid #999;">
                    <form method="POST" action="<?php echo BASE_URL; ?>/proyectos/comentar/<?php echo $actualizacion['id']; ?>">
                        <label><strong>Escribir un comentario:</strong></label>
                        <textarea name="contenido" class="xp-textarea" rows="3" required placeholder="Comparte tu opinión..."></textarea>
                        <div style="text-align: right; margin-top: 10px;">
                            <button type="submit" class="xp-button">Enviar Comentario</button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <p><a href="<?php echo BASE_URL; ?>/login">Inicia sesión</a> para comentar.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Lightbox -->
<div id="imageModal" onclick="closeModal()" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; cursor: pointer; align-items: center; justify-content: center;">
    <div onclick="event.stopPropagation()" style="max-width: 90%; max-height: 90%; position: relative;">
        <img id="modalImage" src="" style="max-width: 100%; max-height: 90vh; display: none;">
        <video id="modalVideo" controls style="max-width: 100%; max-height: 90vh; display: none;"></video>
        <button onclick="closeModal()" style="position: absolute; top: -40px; right: 0; background: white; border: none; font-size: 30px; cursor: pointer; width: 40px; height: 40px; border-radius: 50%;">×</button>
    </div>
</div>

<script>
function openModal(src, isVideo) {
    const modal = document.getElementById('imageModal');
    const img = document.getElementById('modalImage');
    const video = document.getElementById('modalVideo');
    
    modal.style.display = 'flex';
    
    if (isVideo) {
        video.src = src;
        video.style.display = 'block';
        img.style.display = 'none';
    } else {
        img.src = src;
        img.style.display = 'block';
        video.style.display = 'none';
        video.pause();
    }
    
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('imageModal');
    const video = document.getElementById('modalVideo');
    
    modal.style.display = 'none';
    video.pause();
    video.src = '';
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
