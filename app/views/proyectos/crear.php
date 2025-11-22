<?php 
$pageTitle = 'Crear Proyecto - RetroSpace';
require __DIR__ . '/../layout/header.php'; 
?>

<div class="xp-window" style="max-width: 800px; margin: 20px auto;">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            ⚡ Crear Nuevo Proyecto
        </div>
    </div>
    <div class="xp-content">
        <form action="<?php echo BASE_URL; ?>/proyectos/crear" method="POST" enctype="multipart/form-data">
            <div style="margin-bottom: 15px;">
                <label for="archivos"><strong>📎 Archivos (Imágenes/Videos - Máx. 3):</strong></label>
                <input type="file" id="archivos" name="archivos[]" multiple accept="image/*,video/*" class="xp-input" style="width: 100%; margin-top: 5px;">
                <small style="color: #666;">JPG, PNG, GIF, WEBP, MP4, WEBM. Máximo 10MB por archivo.</small>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="titulo"><strong>Título:</strong></label>
                <input type="text" id="titulo" name="titulo" class="xp-input" required style="width: 100%; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="categoria"><strong>Categoría:</strong></label>
                <select id="categoria" name="categoria" class="xp-input" required style="width: 100%; margin-top: 5px;">
                    <option value="">-- Selecciona --</option>
                    <option value="Programacion">💻 Programación</option>
                    <option value="Hardware">🔧 Hardware</option>
                    <option value="Mods">🎨 Mods</option>
                    <option value="GameMaker">🎮 GameMaker</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="descripcion"><strong>Descripción:</strong></label>
                <textarea id="descripcion" name="descripcion" class="xp-textarea" required rows="10" style="width: 100%; margin-top: 5px;"></textarea>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="link1"><strong>Link 1 (opcional):</strong></label>
                <input type="url" id="link1" name="link1" class="xp-input" style="width: 100%; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="link2"><strong>Link 2 (opcional):</strong></label>
                <input type="url" id="link2" name="link2" class="xp-input" style="width: 100%; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="video_url"><strong>Video URL (opcional):</strong></label>
                <input type="url" id="video_url" name="video_url" class="xp-input" placeholder="https://youtube.com/..." style="width: 100%; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="imagen"><strong>Imagen URL (opcional):</strong></label>
                <input type="url" id="imagen" name="imagen" class="xp-input" style="width: 100%; margin-top: 5px;">
            </div>

            <div style="display: flex; gap: 10px;">
                <a href="<?php echo BASE_URL; ?>/proyectos" class="xp-button">❌ Cancelar</a>
                <button type="submit" class="xp-button" style="font-weight: bold;">✅ Crear Proyecto</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
