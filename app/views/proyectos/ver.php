<?php 
$pageTitle = htmlspecialchars($proyecto['titulo']) . ' - RetroSpace Proyectos';
require __DIR__ . '/../layout/header.php'; 
?>

<div class="xp-window" style="max-width: 900px; margin: 20px auto;">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            ⚡ <span data-translatable="title" data-original-lang="es" data-original-text="<?php echo htmlspecialchars($proyecto['titulo']); ?>"><?php echo htmlspecialchars($proyecto['titulo']); ?></span>
        </div>
    </div>
    <div class="xp-content">
        <!-- Cabecera del Proyecto -->
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <span style="background: #0066cc; color: white; padding: 2px 8px; border-radius: 3px; font-size: 0.9em;" data-translatable="category" data-original-lang="es" data-original-text="<?php echo htmlspecialchars($proyecto['categoria']); ?>">
                    <?php echo htmlspecialchars($proyecto['categoria']); ?>
                </span>
                <div style="margin-top: 5px; font-size: 0.9em; color: #444;">
                    <?php echo __('diary.by_author'); ?>: <strong><?php echo htmlspecialchars($proyecto['autor'] ?? 'Anónimo'); ?></strong>
                    <br>
                    <?php echo __('diary.published_on'); ?>: <?php echo Lang::formatDate($proyecto['fecha_creacion']); ?>
                </div>
            </div>
            
            <?php if ($canEdit): ?>
                <div style="display: flex; gap: 10px;">
                    <a href="<?php echo BASE_URL; ?>/proyectos/editar/<?php echo $proyecto['id']; ?>" class="xp-button"><?php echo __('btn.edit'); ?></a>
                    <button onclick="confirmarEliminar(<?php echo $proyecto['id']; ?>)" class="xp-button" style="background: #cc0000; color: white;"><?php echo __('btn.delete'); ?></button>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Descripción Principal -->
        <div style="margin: 20px 0; line-height: 1.6; font-size: 1.1em;" data-translatable="description" data-original-lang="es" data-original-text="<?php echo htmlspecialchars($proyecto['descripcion']); ?>">
            <?php echo nl2br(htmlspecialchars($proyecto['descripcion'])); ?>
        </div>
        
        <!-- Enlaces -->
        <?php if ($proyecto['link1'] || $proyecto['link2'] || $proyecto['video_url']): ?>
        <div style="margin: 20px 0; padding: 15px; background: #f0f0f0; border: 1px solid #999; border-radius: 4px;">
            <strong>🔗 Enlaces:</strong>
            <ul style="margin-top: 10px; list-style-type: square; padding-left: 20px;">
                <?php if ($proyecto['link1']): ?>
                    <li><a href="<?php echo htmlspecialchars($proyecto['link1']); ?>" target="_blank">Enlace 1</a></li>
                <?php endif; ?>
                <?php if ($proyecto['link2']): ?>
                    <li><a href="<?php echo htmlspecialchars($proyecto['link2']); ?>" target="_blank">Enlace 2</a></li>
                <?php endif; ?>
                <?php if ($proyecto['video_url']): ?>
                    <li><a href="<?php echo htmlspecialchars($proyecto['video_url']); ?>" target="_blank">Ver Video</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Galería del Proyecto (Thumbnails estilo Reddit) -->
        <?php 
        $archivosProyecto = isset($proyecto['archivos']) ? json_decode($proyecto['archivos'], true) : [];
        if (!empty($archivosProyecto)): 
        ?>
            <div style="margin: 20px 0;">
                <strong style="display: block; margin-bottom: 10px;">📸 Multimedia:</strong>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <?php foreach ($archivosProyecto as $index => $archivo): 
                        $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
                        $isVideo = in_array($ext, ['mp4', 'webm', 'ogg']);
                    ?>
                        <div onclick="openModal('<?php echo BASE_URL . htmlspecialchars($archivo); ?>', <?php echo $isVideo ? 'true' : 'false'; ?>)" class="thumbnail-hover" style="cursor: pointer; border: 1px solid #ccc; padding: 2px; background: #f9f9f9; transition: all 0.2s;">
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
        
        <hr style="border: 0; border-top: 2px solid #999; margin: 30px 0;">

        <!-- HISTORIAL DE ACTUALIZACIONES -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">📋 <?php echo __('projects.updates'); ?></h2>
                <?php if ($isOwner): ?>
                    <a href="<?php echo BASE_URL; ?>/proyectos/crear-actualizacion/<?php echo $proyecto['id']; ?>" class="xp-button" style="font-weight: bold;">+ Nueva Actualización</a>
                <?php endif; ?>
            </div>

            <?php if (empty($actualizaciones)): ?>
                <div style="text-align: center; padding: 20px; background: #eee; border: 1px dashed #999;">
                    <p>No hay actualizaciones publicadas aún.</p>
                </div>
            <?php else: ?>
                <?php foreach ($actualizaciones as $act): ?>
                    <div class="xp-window" style="margin-bottom: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <div class="xp-titlebar">
                            <div class="xp-titlebar-text">
                                📅 <?php echo Lang::formatDate($act['fecha_creacion']); ?> - <strong data-translatable="title" data-original-lang="es" data-original-text="<?php echo htmlspecialchars($act['titulo']); ?>"><?php echo htmlspecialchars($act['titulo']); ?></strong>
                            </div>
                        </div>
                        <div class="xp-content">
                            <div style="margin-bottom: 15px; line-height: 1.5;" data-translatable="description" data-original-lang="es" data-original-text="<?php echo htmlspecialchars($act['contenido']); ?>">
                                <?php echo nl2br(htmlspecialchars($act['contenido'])); ?>
                            </div>

                            <!-- Galería de la actualización (Thumbnails) -->
                            <?php 
                            $archivosAct = isset($act['archivos']) ? json_decode($act['archivos'], true) : [];
                            if (!empty($archivosAct)): 
                            ?>
                                <div style="margin: 15px 0;">
                                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                        <?php foreach ($archivosAct as $index => $archivo): 
                                            $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
                                            $isVideo = in_array($ext, ['mp4', 'webm', 'ogg']);
                                        ?>
                                            <div onclick="openModal('<?php echo BASE_URL . htmlspecialchars($archivo); ?>', <?php echo $isVideo ? 'true' : 'false'; ?>)" class="thumbnail-hover" style="cursor: pointer; border: 1px solid #ccc; padding: 2px; background: #f9f9f9; transition: all 0.2s;">
                                                <?php if ($isVideo): ?>
                                                    <div style="width: 120px; height: 80px; background: #000; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5em;">▶️</div>
                                                <?php else: ?>
                                                    <img src="<?php echo BASE_URL . htmlspecialchars($archivo); ?>" style="width: 120px; height: 80px; object-fit: cover; display: block;">
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Comentarios Preview -->
                            <div style="margin-top: 15px; background: #f4f4f4; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                                <div style="font-weight: bold; font-size: 0.9em; margin-bottom: 5px;">💬 <?php echo __('projects.comments'); ?>:</div>
                                <?php if (empty($act['comentarios_preview'])): ?>
                                    <p style="font-size: 0.85em; color: #666; margin: 0;">Sé el primero en comentar.</p>
                                <?php else: ?>
                                    <?php foreach ($act['comentarios_preview'] as $com): ?>
                                        <div style="font-size: 0.85em; margin-bottom: 4px; border-bottom: 1px dotted #ccc; padding-bottom: 2px;">
                                            <strong><?php echo htmlspecialchars($com['username']); ?>:</strong>
                                            <span data-translatable="comment" data-original-lang="es" data-original-text="<?php echo htmlspecialchars($com['contenido']); ?>">
                                                <?php echo htmlspecialchars(substr($com['contenido'], 0, 80)) . (strlen($com['contenido']) > 80 ? '...' : ''); ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                                <div style="margin-top: 10px; text-align: right;">
                                    <a href="<?php echo BASE_URL; ?>/proyectos/actualizacion/<?php echo $act['id']; ?>" class="xp-button" style="font-size: 0.9em;">
                                        <?php echo __('btn.view'); ?> <?php echo $act['total_comentarios']; ?> <?php echo __('projects.comments'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div style="margin-top: 30px;">
            <a href="<?php echo BASE_URL; ?>/proyectos" class="xp-button">← Volver a Proyectos</a>
        </div>
    </div>
</div>

<style>
.thumbnail-hover:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
}
</style>

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

function confirmarEliminar(id) {
    if (confirm('⚠️ ¿Estás seguro de que quieres eliminar este proyecto?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo BASE_URL; ?>/proyectos/eliminar/' + id;
        document.body.appendChild(form);
        form.submit();
    }
}

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
