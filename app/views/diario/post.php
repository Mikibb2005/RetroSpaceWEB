<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="xp-window" style="max-width: 900px; margin: 20px auto;">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            📝 <?php echo htmlspecialchars($post['titulo']); ?>
        </div>
    </div>
    <div class="xp-content">
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <small>👤 por <strong><?php echo htmlspecialchars($post['autor']); ?></strong> | 📅 <?php echo date('d/m/Y H:i', strtotime($post['fecha_publicacion'])); ?></small>
            
            <?php if ($isAdmin): ?>
                <div style="display: flex; gap: 10px;">
                    <a href="<?php echo BASE_URL; ?>/diario/editar/<?php echo $post['id']; ?>" class="xp-button">✏️ Editar</a>
                    <button onclick="confirmarEliminar(<?php echo $post['id']; ?>)" class="xp-button" style="background: #cc0000; color: white;">🗑️ Eliminar</button>
                </div>
            <?php endif; ?>
        </div>
        
        <?php 
        // Mostrar galería de archivos
        $archivos = isset($post['archivos']) ? json_decode($post['archivos'], true) : [];
        if (!empty($archivos)): 
        ?>
        <div class="media-gallery" style="position: relative; margin: 20px 0; background: #000; border: 2px solid #999;">
            <div class="gallery-container" id="gallery">
                <?php foreach ($archivos as $index => $archivo): ?>
                    <?php 
                    $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
                    $isVideo = in_array($ext, ['mp4', 'webm', 'ogg']);
                    ?>
                    <div class="gallery-item <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                        <?php if ($isVideo): ?>
                            <video controls style="max-width: 100%; max-height: 600px; display: block; margin: 0 auto;">
                                <source src="<?php echo BASE_URL . htmlspecialchars($archivo); ?>" type="video/<?php echo $ext; ?>">
                            </video>
                        <?php else: ?>
                            <img src="<?php echo BASE_URL . htmlspecialchars($archivo); ?>" 
                                 alt="Imagen <?php echo $index + 1; ?>" 
                                 style="max-width: 100%; max-height: 600px; display: block; margin: 0 auto;">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($archivos) > 1): ?>
                <button class="gallery-prev" onclick="changeSlide(-1)" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.7); color: white; border: 2px solid #fff; padding: 10px 15px; cursor: pointer; font-size: 18px; z-index: 10;">❮</button>
                <button class="gallery-next" onclick="changeSlide(1)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.7); color: white; border: 2px solid #fff; padding: 10px 15px; cursor: pointer; font-size: 18px; z-index: 10;">❯</button>
                <div style="text-align: center; padding: 10px; background: rgba(0,0,0,0.8); color: white;">
                    <span id="gallery-counter">1 / <?php echo count($archivos); ?></span>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($post['imagen']): ?>
            <img src="<?php echo htmlspecialchars($post['imagen']); ?>" 
                 alt="Imagen del post" style="max-width:100%; margin:10px 0; border: 2px solid #999;">
        <?php endif; ?>
        
        <div style="margin: 20px 0; line-height: 1.6;">
            <?php echo nl2br(htmlspecialchars($post['contenido'])); ?>
        </div>
        
        <?php if ($post['codigo_embed']): ?>
            <div style="margin:15px 0;">
                <?php echo $post['codigo_embed']; ?>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #999;">
            <a href="<?php echo BASE_URL; ?>/diario" class="xp-button">← Volver al Diario</a>
        </div>
    </div>
</div>

<style>
.gallery-item {
    display: none;
}
.gallery-item.active {
    display: block;
}
.gallery-prev:hover, .gallery-next:hover {
    background: rgba(0,0,0,0.9) !important;
}
</style>

<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.gallery-item');
const totalSlides = slides.length;

function showSlide(n) {
    if (totalSlides === 0) return;
    
    slides.forEach(slide => slide.classList.remove('active'));
    
    if (n >= totalSlides) currentSlide = 0;
    if (n < 0) currentSlide = totalSlides - 1;
    
    slides[currentSlide].classList.add('active');
    
    // Actualizar contador
    const counter = document.getElementById('gallery-counter');
    if (counter) {
        counter.textContent = (currentSlide + 1) + ' / ' + totalSlides;
    }
}

function changeSlide(direction) {
    currentSlide += direction;
    showSlide(currentSlide);
}

function confirmarEliminar(id) {
    if (confirm('⚠️ ¿Estás seguro de que quieres eliminar este post?\n\nEsta acción NO SE PUEDE DESHACER.')) {
        if (confirm('🔴 ÚLTIMA CONFIRMACIÓN: ¿Realmente quieres eliminar este post?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo BASE_URL; ?>/diario/eliminar/' + id;
            document.body.appendChild(form);
            form.submit();
        }
    }
}

// Soporte de teclado
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft') changeSlide(-1);
    if (e.key === 'ArrowRight') changeSlide(1);
});
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
