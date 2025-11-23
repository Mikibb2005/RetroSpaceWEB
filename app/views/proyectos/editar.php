<?php 
$pageTitle = 'Editar Proyecto - RetroSpace';
require __DIR__ . '/../layout/header.php'; 
?>

<div class="xp-window" style="max-width: 800px; margin: 20px auto;">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            📝 Editar Proyecto
        </div>
    </div>
    <div class="xp-content">
        <form action="<?php echo BASE_URL; ?>/proyectos/editar/<?php echo $proyecto['id']; ?>" method="POST" enctype="multipart/form-data">
            
            <?php 
            // Mostrar archivos actuales
            $archivosActuales = isset($proyecto['archivos']) ? json_decode($proyecto['archivos'], true) : [];
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
                <input type="text" id="titulo" name="titulo" class="xp-input" required value="<?php echo htmlspecialchars($proyecto['titulo']); ?>" style="width: 100%; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="categoria"><strong>Categoría:</strong></label>
                <select id="categoria" name="categoria" class="xp-input" required style="width: 100%; margin-top: 5px;">
                    <option value="">-- Selecciona --</option>
                    <option value="Programacion" <?php echo $proyecto['categoria'] === 'Programacion' ? 'selected' : ''; ?>>💻 Programación</option>
                    <option value="Hardware" <?php echo $proyecto['categoria'] === 'Hardware' ? 'selected' : ''; ?>>🔧 Hardware</option>
                    <option value="Mods" <?php echo $proyecto['categoria'] === 'Mods' ? 'selected' : ''; ?>>🎨 Mods</option>
                    <option value="GameMaker" <?php echo $proyecto['categoria'] === 'GameMaker' ? 'selected' : ''; ?>>🎮 GameMaker</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="descripcion"><strong>Descripción:</strong></label>
                <textarea id="descripcion" name="descripcion" class="xp-textarea" required rows="10" style="width: 100%; margin-top: 5px;"><?php echo htmlspecialchars($proyecto['descripcion']); ?></textarea>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="link1"><strong>Link 1 (opcional):</strong></label>
                <input type="url" id="link1" name="link1" class="xp-input" value="<?php echo htmlspecialchars($proyecto['link1'] ?? ''); ?>" style="width: 100%; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="link2"><strong>Link 2 (opcional):</strong></label>
                <input type="url" id="link2" name="link2" class="xp-input" value="<?php echo htmlspecialchars($proyecto['link2'] ?? ''); ?>" style="width: 100%; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="video_url"><strong>Video URL (opcional):</strong></label>
                <input type="url" id="video_url" name="video_url" class="xp-input" value="<?php echo htmlspecialchars($proyecto['video_url'] ?? ''); ?>" style="width: 100%; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="imagen"><strong>Imagen URL (opcional):</strong></label>
                <input type="url" id="imagen" name="imagen" class="xp-input" value="<?php echo htmlspecialchars($proyecto['imagen'] ?? ''); ?>" style="width: 100%; margin-top: 5px;">
            </div>

            <div style="display: flex; gap: 10px;">
                <a href="<?php echo BASE_URL; ?>/proyectos/<?php echo $proyecto['id']; ?>" class="xp-button">❌ Cancelar</a>
                <button type="submit" class="xp-button" style="font-weight: bold;">💾 Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
