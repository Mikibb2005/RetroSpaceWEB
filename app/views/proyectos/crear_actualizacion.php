<?php 
$pageTitle = 'Nueva Actualización - ' . htmlspecialchars($proyecto['titulo']);
require __DIR__ . '/../layout/header.php'; 
?>

<div class="xp-window" style="max-width: 800px; margin: 20px auto;">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            📝 Publicar Nueva Entrada - <?php echo htmlspecialchars($proyecto['titulo']); ?>
        </div>
    </div>
    <div class="xp-content">
        <div style="margin-bottom: 20px;">
            <a href="<?php echo BASE_URL; ?>/proyectos/<?php echo $proyecto['id']; ?>" class="xp-button">← Cancelar y Volver</a>
        </div>

        <form method="POST" action="<?php echo BASE_URL; ?>/proyectos/crear-actualizacion/<?php echo $proyecto['id']; ?>" enctype="multipart/form-data">
            <div style="margin-bottom: 15px;">
                <label for="titulo"><strong>Título de la Actualización:</strong></label>
                <input type="text" id="titulo" name="titulo" class="xp-input" required placeholder="Ej: Versión 1.2 - Nuevos niveles añadidos">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="contenido"><strong>Contenido:</strong></label>
                <textarea id="contenido" name="contenido" class="xp-textarea" rows="10" required placeholder="Describe los avances, cambios o novedades de esta actualización..."></textarea>
                <small style="color: #666;">Puedes usar saltos de línea para organizar el contenido.</small>
            </div>

            <div style="margin-bottom: 15px;">
                <label><strong>Archivos Adjuntos (Opcional - Máx 3 imágenes/videos):</strong></label>
                <input type="file" name="archivos[]" accept="image/*,video/*" multiple class="xp-input">
                <small style="color: #666;">Formatos: JPG, PNG, GIF, MP4, WEBM. Máximo 10MB por archivo.</small>
            </div>

            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="xp-button" style="font-weight: bold; background: #0066cc; color: white;">📢 Publicar Actualización</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
