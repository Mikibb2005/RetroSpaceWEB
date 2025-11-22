<?php 
$pageTitle = 'Crear Post - RetroSpace';
require __DIR__ . '/../layout/header.php'; 
?>

<div class="xp-window" style="max-width: 800px; margin: 20px auto;">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            ✍️ Crear Nueva Entrada del Diario
        </div>
    </div>
    <div class="xp-content">
        <form action="<?php echo BASE_URL; ?>/diario/crear" method="POST" enctype="multipart/form-data">
            <div style="margin-bottom: 15px;">
                <label for="archivos"><strong>📎 Archivos (Imágenes/Videos - Máx. 3):</strong></label>
                <input type="file" id="archivos" name="archivos[]" multiple accept="image/*,video/*" class="xp-input" style="width: 100%; margin-top: 5px;">
                <small style="color: #666;">Formatos: JPG, PNG, GIF, WEBP, MP4, WEBM. Máximo 10MB por archivo.</small>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="titulo"><strong>Título:</strong></label>
                <input type="text" id="titulo" name="titulo" class="xp-input" required style="width: 100%; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="contenido"><strong>Contenido:</strong></label>
                <textarea id="contenido" name="contenido" class="xp-textarea" required rows="15" style="width: 100%; margin-top: 5px;"></textarea>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="imagen"><strong>URL de Imagen (opcional):</strong></label>
                <input type="url" id="imagen" name="imagen" class="xp-input" placeholder="https://ejemplo.com/imagen.jpg" style="width: 100%; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="codigo_embed"><strong>Código Embed (opcional - YouTube, etc):</strong></label>
                <textarea id="codigo_embed" name="codigo_embed" class="xp-textarea" rows="4" placeholder='<iframe src="..." ...></iframe>' style="width: 100%; margin-top: 5px;"></textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <a href="<?php echo BASE_URL; ?>/diario" class="xp-button">❌ Cancelar</a>
                <button type="submit" class="xp-button" style="font-weight: bold;">✅ Publicar Post</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
