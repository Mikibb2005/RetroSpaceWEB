<?php 
$pageTitle = htmlspecialchars($hilo['titulo']) . ' - RetroSpace Foro';
require __DIR__ . '/../layout/header.php'; 
?>

<style>
.comment-thread {
    margin-left: 0;
}
.comment-reply {
    margin-left: 30px;
    border-left: 2px solid #999;
    padding-left: 10px;
}
.reply-form {
    display: none;
    margin: 10px 0;
    padding: 10px;
    background: #f0f0f0;
    border: 1px solid #999;
}
.reply-form.active {
    display: block;
}
.comment-thread .xp-content {
    padding: 8px !important;
    min-height: auto !important;
}
.comment-thread .xp-content p {
    margin: 0 0 8px 0;
}
.gallery-item { display: none; }
.gallery-item.active { display: block; }
.gallery-prev:hover, .gallery-next:hover { background: rgba(0,0,0,0.9) !important; }
</style>

<div class="xp-window" style="max-width: 900px; margin: 20px auto;">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            💬 <?php echo htmlspecialchars($hilo['titulo']); ?>
        </div>
    </div>
    <div class="xp-content">
        <!-- Post Original -->
        <div style="margin-bottom:20px; padding:15px; background:#ECE9D8; border: 2px solid #999;">
            <div style="margin-bottom: 10px;">
                <a href="<?php echo BASE_URL; ?>/perfil/<?php echo $hilo['autor_id']; ?>" 
                   style="text-decoration: none; color: #0066cc; font-weight: bold;">
                    👤 <?php echo htmlspecialchars($hilo['autor']); ?>
                </a>
                <small style="margin-left: 10px; color: #666;">
                    📅 <?php echo date('d/m/Y H:i', strtotime($hilo['fecha_creacion'])); ?>
                </small>
            </div>
        </div>
            <p style="margin: 10px 0;"><?php echo nl2br(htmlspecialchars($hilo['descripcion'])); ?></p>
            
            <?php 
            // Mostrar galería de archivos
            $archivos = isset($hilo['archivos']) ? json_decode($hilo['archivos'], true) : [];
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
                                <video controls style="max-width: 100%; max-height: 500px; display: block; margin: 0 auto;">
                                    <source src="<?php echo BASE_URL . htmlspecialchars($archivo); ?>" type="video/<?php echo $ext; ?>">
                                </video>
                            <?php else: ?>
                                <img src="<?php echo BASE_URL . htmlspecialchars($archivo); ?>" 
                                     alt="Imagen <?php echo $index + 1; ?>" 
                                     style="max-width: 100%; max-height: 500px; display: block; margin: 0 auto;">
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
        </div>
        
        <h3 style="margin: 20px 0 10px 0;">💬 Comentarios</h3>
        
        <!-- Formulario principal de comentario -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="xp-window" style="margin:15px 0;">
                <div class="xp-titlebar"><span>✍️ Añadir Comentario</span></div>
                <div class="xp-content">
                    <form method="post" action="<?php echo BASE_URL; ?>/foro/comentar">
                        <textarea name="contenido" class="xp-textarea" rows="4" required placeholder="Escribe tu comentario..."></textarea>
                        <input type="hidden" name="hilo_id" value="<?php echo $hilo['id']; ?>">
                        <input type="hidden" name="parent_id" value="">
                        <button type="submit" class="xp-button" style="margin-top: 5px;">💬 Comentar</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p style="padding: 10px; background: #ffffcc; border: 1px solid #999;">
                <a href="<?php echo BASE_URL; ?>/login">Inicia sesión</a> para comentar
            </p>
        <?php endif; ?>
        
        <!-- Árbol de comentarios -->
        <div id="comentarios">
            <?php 
            function renderComentario($comentario, $hilo_id, $nivel = 0) { 
                $isLoggedIn = isset($_SESSION['user_id']);
                $replyId = 'reply-' . $comentario['id'];
            ?>
                <div class="comment-thread <?php echo $nivel > 0 ? 'comment-reply' : ''; ?>" style="margin-bottom: 15px;">
                    <div class="xp-window">
                        <div class="xp-titlebar" style="background: <?php echo $nivel > 0 ? '#9999CC' : '#0066cc'; ?>;">
                            <span style="color: white;">
                                <a href="<?php echo BASE_URL; ?>/perfil/<?php echo $comentario['autor_id']; ?>" 
                                   style="text-decoration: none; color: white; font-weight: bold;"
                                   onmouseover="this.style.textDecoration='underline'" 
                                   onmouseout="this.style.textDecoration='none'">
                                    👤 <?php echo htmlspecialchars($comentario['autor']); ?>
                                </a>
                                <small style="margin-left: 10px;">
                                    📅 <?php echo date('d/m/Y H:i', strtotime($comentario['fecha_publicacion'])); ?>
                                </small>
                            </span>
                        </div>
                        <div class="xp-content" style="padding: 8px;">
                            <p style="margin: 0 0 8px 0; line-height: 1.4;"><?php echo nl2br(htmlspecialchars($comentario['contenido'])); ?></p>
                            
                            <?php if ($isLoggedIn): ?>
                                <button class="xp-button" style="margin: 0; padding: 3px 8px; font-size: 11px;" onclick="toggleReply('<?php echo $replyId; ?>')">
                                    ↩️ Responder
                                </button>
                            <?php endif; ?>
                            
                            <!-- Formulario de respuesta anidada -->
                            <?php if ($isLoggedIn): ?>
                                <div id="<?php echo $replyId; ?>" class="reply-form">
                                    <form method="post" action="<?php echo BASE_URL; ?>/foro/comentar">
                                        <textarea name="contenido" class="xp-textarea" rows="3" required placeholder="Escribe tu respuesta..."></textarea>
                                        <input type="hidden" name="hilo_id" value="<?php echo $hilo_id; ?>">
                                        <input type="hidden" name="parent_id" value="<?php echo $comentario['id']; ?>">
                                        <div style="margin-top: 5px;">
                                            <button type="submit" class="xp-button">💬 Responder</button>
                                            <button type="button" class="xp-button" onclick="toggleReply('<?php echo $replyId; ?>')">Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Renderizar respuestas anidadas -->
                    <?php if (isset($comentario['children']) && count($comentario['children']) > 0): ?>
                        <div style="margin-top: 10px;">
                            <?php foreach ($comentario['children'] as $child): ?>
                                <?php renderComentario($child, $hilo_id, $nivel + 1); ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php } ?>
            
            <?php if (empty($comentarios_tree)): ?>
                <p style="padding: 15px; text-align: center; background: #f0f0f0; border: 1px solid #999;">
                    No hay comentarios aún. ¡Sé el primero en comentar!
                </p>
            <?php else: ?>
                <?php foreach ($comentarios_tree as $comentario): ?>
                    <?php renderComentario($comentario, $hilo['id']); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

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
    const counter = document.getElementById('gallery-counter');
    if (counter) counter.textContent = (currentSlide + 1) + ' / ' + totalSlides;
}

function changeSlide(direction) {
    currentSlide += direction;
    showSlide(currentSlide);
}

// Soporte de teclado
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft') changeSlide(-1);
    if (e.key === 'ArrowRight') changeSlide(1);
});

function toggleReply(replyId) {
    var replyForm = document.getElementById(replyId);
    if (replyForm.classList.contains('active')) {
        replyForm.classList.remove('active');
    } else {
        // Cerrar todos los demás formularios
        var allForms = document.querySelectorAll('.reply-form');
        allForms.forEach(function(form) {
            form.classList.remove('active');
        });
        // Abrir este formulario
        replyForm.classList.add('active');
        // Hacer scroll al formulario
        replyForm.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        // Enfocar el textarea
        var textarea = replyForm.querySelector('textarea');
        if (textarea) textarea.focus();
    }
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
