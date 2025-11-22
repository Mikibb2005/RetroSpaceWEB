<?php 
$pageTitle = 'Editar Post - RetroSpace';
require __DIR__ . '/../layout/header.php'; 
?>

<div class="xp-window" style="max-width: 800px; margin: 20px auto;">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            📝 Editar Entrada del Diario
        </div>
    </div>
    <div class="xp-content">
        <form action="<?php echo BASE_URL; ?>/diario/editar/<?php echo $post['id']; ?>" method="POST" enctype="multipart/form-data">
            
            <?php 
            // Mostrar archivos actuales
            $archivosActuales = isset($post['archivos']) ? json_decode($post['archivos'], true) : [];
            if (!empty($archivosActuales)): 
            ?>
            <div style="margin-bottom: 15px; padding: 10px; background: #ffffcc; border: 1px solid #999;">
                <strong>📎 Archivos actuales:</strong>
                <div style="display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap;">
                    <?php foreach ($archivosActuales as $archivo): ?>
                        <?php 
                        $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
                        $isVideo = in_array($ext, ['mp4', 'webm', 'ogg']);
                        ?>
                        <div style="border: 1px solid #999; padding: 5px;">
                            <?php if ($isVideo): ?>
                                <video style="max-width: 100px; max-height: 100px;">
                                    <source src="<?php echo BASE_URL . htmlspecialchars($archivo); ?>">
                                </video>
                                <div>🎥 Video</div>
                            <?php else: ?>
                                <img src="<?php echo BASE_URL . htmlspecialchars($archivo); ?>" style="max-width: 100px; max-height: 100px;">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <small style="color: #cc6600;">⚠️ Si subes nuevos archivos, estos reemplazarán los actuales.</small>
            </div>
            <?php endif; ?>
            
            <div style="margin-bottom: 15px;">
                <label for="archivos"><strong>📎 Subir Nuevos Archivos (opcional - Reemplaza actuales):</strong></label>
                <input type="file" id="archivos" name="archivos[]" multiple accept="image/*,video/*" class="xp-input" style="width: 100%; margin-top: 5px;">
                <small style="color: #666;">Máximo 3 archivos, 10MB cada uno. JPG, PNG, GIF, WEBP, MP4, WEBM.</small>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="titulo"><strong>Título:</strong></label>
                <input type="text" id="titulo" name="titulo" class="xp-input" required value="<?php echo htmlspecialchars($post['titulo']); ?>" style="width: 100%; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="contenido"><strong>Contenido:</strong></label>
                <textarea id="contenido" name="contenido" class="xp-textarea" required rows="15" style="width: 100%; margin-top: 5px;"><?php echo htmlspecialchars($post['contenido']); ?></textarea>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="imagen"><strong>URL de Imagen (opcional):</strong></label>
                <input type="url" id="imagen" name="imagen" class="xp-input" value="<?php echo htmlspecialchars($post['imagen'] ?? ''); ?>" style="width: 100%; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="codigo_embed"><strong>Código Embed (opcional):</strong></label>
                <textarea id="codigo_embed" name="codigo_embed" class="xp-textarea" rows="4" style="width: 100%; margin-top: 5px;"><?php echo htmlspecialchars($post['codigo_embed'] ?? ''); ?></textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <a href="<?php echo BASE_URL; ?>/diario/<?php echo $post['id']; ?>" class="xp-button">❌ Cancelar</a>
                <button type="submit" class="xp-button" style="font-weight: bold;">💾 Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
