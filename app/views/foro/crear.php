<?php 
$pageTitle = 'Crear Hilo - RetroSpace';
require __DIR__ . '/../layout/header.php'; 
?>

<div class="xp-window" style="max-width: 800px; margin: 20px auto;">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            📝 Crear Nuevo Hilo
        </div>
    </div>
    <div class="xp-content">
        <form action="<?php echo BASE_URL; ?>/foro/crear" method="POST" enctype="multipart/form-data">
            <div style="margin-bottom: 15px;">
                <label for="archivos"><strong>📎 Archivos (Imágenes/Videos - Máx. 3):</strong></label>
                <input type="file" id="archivos" name="archivos[]" multiple accept="image/*,video/*" class="xp-input" style="width: 100%; margin-top: 5px;">
                <small style="color: #666;">JPG, PNG, GIF, WEBP, MP4, WEBM. Máximo 10MB por archivo.</small>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="titulo"><strong>Título del hilo:</strong></label>
                <input type="text" 
                       id="titulo" 
                       name="titulo" 
                       class="xp-input" 
                       required 
                       maxlength="200"
                       placeholder="Escribe un título descriptivo..."
                       style="width: 100%; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="categoria"><strong>Categoría:</strong></label>
                <select id="categoria" 
                        name="categoria" 
                        class="xp-input" 
                        required
                        style="width: 100%; margin-top: 5px;">
                    <option value="">-- Selecciona una categoría --</option>
                    <option value="general">💬 General</option>
                    <option value="programacion">💻 Programación</option>
                    <option value="juegos">🎮 Juegos</option>
                    <option value="devlog">📊 Devlog</option>
                    <option value="ayuda">❓ Ayuda</option>
                    <option value="off-topic">🗨️ Off-Topic</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="descripcion"><strong>Descripción:</strong></label>
                <textarea id="descripcion" 
                          name="descripcion" 
                          class="xp-textarea" 
                          required
                          rows="10"
                          placeholder="Describe tu tema en detalle..."
                          style="width: 100%; margin-top: 5px; resize: vertical;"></textarea>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <a href="<?php echo BASE_URL; ?>/foro" class="xp-button">Cancelar</a>
                <button type="submit" class="xp-button" style="font-weight: bold;">✅ Crear Hilo</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
