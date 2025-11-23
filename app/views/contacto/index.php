<?php
/**
 * Vista de Contacto
 * Formulario de contacto con estilo retro
 */
require_once __DIR__ . '/../layout/header.php';
?>

<div class="xp-window" style="max-width: 800px; margin: 20px auto;">
    <!-- Barra de título -->
    <div class="xp-window-title">
        <span>📧 <?php echo __('contact.title'); ?></span>
        <div class="xp-window-controls">
            <button class="xp-btn-minimize">_</button>
            <button class="xp-btn-maximize">□</button>
        </div>
    </div>
    
    <!-- Contenido -->
    <div class="xp-window-content">
        <!-- Mensaje de éxito -->
        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <div class="xp-message-box success" style="margin-bottom: 20px; padding: 15px; border: 2px solid #00aa00; background: #e0ffe0;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="font-size: 32px;">✅</div>
                    <div>
                        <strong><?php echo __('contact.success_title'); ?></strong>
                        <p style="margin: 5px 0 0 0;"><?php echo __('contact.success_message'); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Errores generales -->
        <?php if (isset($errors['general'])): ?>
            <div class="xp-message-box error" style="margin-bottom: 20px; padding: 15px; border: 2px solid #aa0000; background: #ffe0e0;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="font-size: 32px;">❌</div>
                    <div>
                        <strong><?php echo __('contact.error_title'); ?></strong>
                        <p style="margin: 5px 0 0 0;"><?php echo htmlspecialchars($errors['general']); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Información de contacto alternativa -->
        <div style="margin-bottom: 30px; padding: 15px; background: #f0f0f0; border-left: 4px solid #0066cc;">
            <h3 style="margin-top: 0;"><?php echo __('contact.info_title'); ?></h3>
            <p><?php echo __('contact.info_text'); ?></p>
            
            <div style="margin-top: 15px; display: grid; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 20px;">📧</span>
                    <strong><?php echo __('contact.email'); ?>:</strong> 
                    <a href="mailto:contacto@retrospace.com" style="color: #0066cc;">contacto@retrospace.com</a>
                </div>
                
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 20px;">🐙</span>
                    <strong>GitHub:</strong> 
                    <a href="https://github.com/Mikibb2005" target="_blank" style="color: #0066cc;">@Mikibb2005</a>
                </div>
                
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 20px;">💬</span>
                    <strong><?php echo __('contact.social'); ?>:</strong> 
                    <span><?php echo __('contact.social_text'); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Formulario -->
        <form method="POST" action="<?php echo BASE_URL; ?>/contacto/enviar" class="xp-form" id="contact-form">
            <!-- Token CSRF -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            
            <!-- Nombre -->
            <div class="xp-form-group" style="margin-bottom: 20px;">
                <label for="nombre" class="xp-label">
                    <?php echo __('contact.name'); ?> <span style="color: red;">*</span>
                </label>
                <input 
                    type="text" 
                    id="nombre" 
                    name="nombre" 
                    class="xp-input <?php echo isset($errors['nombre']) ? 'error' : ''; ?>"
                    value="<?php echo htmlspecialchars($nombre ?? $_SESSION['contact_form_data']['nombre'] ?? ''); ?>"
                    maxlength="100"
                    required
                    placeholder="<?php echo __('contact.name_placeholder'); ?>"
                >
                <?php if (isset($errors['nombre'])): ?>
                    <small class="xp-error" style="color: #aa0000; display: block; margin-top: 5px;">
                        <?php echo htmlspecialchars($errors['nombre']); ?>
                    </small>
                <?php endif; ?>
            </div>
            
            <!-- Email -->
            <div class="xp-form-group" style="margin-bottom: 20px;">
                <label for="email" class="xp-label">
                    <?php echo __('contact.email'); ?> <span style="color: red;">*</span>
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="xp-input <?php echo isset($errors['email']) ? 'error' : ''; ?>"
                    value="<?php echo htmlspecialchars($email ?? $_SESSION['contact_form_data']['email'] ?? ''); ?>"
                    maxlength="150"
                    required
                    placeholder="<?php echo __('contact.email_placeholder'); ?>"
                >
                <?php if (isset($errors['email'])): ?>
                    <small class="xp-error" style="color: #aa0000; display: block; margin-top: 5px;">
                        <?php echo htmlspecialchars($errors['email']); ?>
                    </small>
                <?php endif; ?>
            </div>
            
            <!-- Asunto -->
            <div class="xp-form-group" style="margin-bottom: 20px;">
                <label for="asunto" class="xp-label">
                    <?php echo __('contact.subject'); ?> <span style="color: red;">*</span>
                </label>
                <input 
                    type="text" 
                    id="asunto" 
                    name="asunto" 
                    class="xp-input <?php echo isset($errors['asunto']) ? 'error' : ''; ?>"
                    value="<?php echo htmlspecialchars($_SESSION['contact_form_data']['asunto'] ?? ''); ?>"
                    maxlength="200"
                    required
                    placeholder="<?php echo __('contact.subject_placeholder'); ?>"
                >
                <?php if (isset($errors['asunto'])): ?>
                    <small class="xp-error" style="color: #aa0000; display: block; margin-top: 5px;">
                        <?php echo htmlspecialchars($errors['asunto']); ?>
                    </small>
                <?php endif; ?>
            </div>
            
            <!-- Mensaje -->
            <div class="xp-form-group" style="margin-bottom: 20px;">
                <label for="mensaje" class="xp-label">
                    <?php echo __('contact.message'); ?> <span style="color: red;">*</span>
                </label>
                <textarea 
                    id="mensaje" 
                    name="mensaje" 
                    class="xp-textarea <?php echo isset($errors['mensaje']) ? 'error' : ''; ?>"
                    rows="8"
                    maxlength="5000"
                    required
                    placeholder="<?php echo __('contact.message_placeholder'); ?>"
                ><?php echo htmlspecialchars($_SESSION['contact_form_data']['mensaje'] ?? ''); ?></textarea>
                <small style="display: block; color: #666; margin-top: 5px;">
                    <span id="char-count">0</span> / 5000 <?php echo __('contact.characters'); ?>
                </small>
                <?php if (isset($errors['mensaje'])): ?>
                    <small class="xp-error" style="color: #aa0000; display: block; margin-top: 5px;">
                        <?php echo htmlspecialchars($errors['mensaje']); ?>
                    </small>
                <?php endif; ?>
            </div>
            
            <!-- Botones -->
            <div class="xp-form-actions" style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="reset" class="xp-button" style="padding: 10px 20px;">
                    <?php echo __('btn.reset'); ?>
                </button>
                <button type="submit" class="xp-button xp-button-primary" style="padding: 10px 30px; background: #0066cc; color: white; font-weight: bold;">
                    📧 <?php echo __('btn.send'); ?>
                </button>
            </div>
        </form>
        
        <!-- Nota de privacidad -->
        <div style="margin-top: 30px; padding: 15px; background: #fffacd; border: 1px solid #ffd700; border-radius: 4px;">
            <p style="margin: 0; font-size: 13px; color: #666;">
                <strong>🔒 <?php echo __('contact.privacy_title'); ?>:</strong>
                <?php echo __('contact.privacy_text'); ?>
            </p>
        </div>
    </div>
</div>

<script>
// Contador de caracteres
document.addEventListener('DOMContentLoaded', function() {
    const mensajeTextarea = document.getElementById('mensaje');
    const charCount = document.getElementById('char-count');
    
    if (mensajeTextarea && charCount) {
        function updateCount() {
            charCount.textContent = mensajeTextarea.value.length;
        }
        
        mensajeTextarea.addEventListener('input', updateCount);
        updateCount();
    }
    
    // Validación en tiempo real
    const form = document.getElementById('contact-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const email = document.getElementById('email').value.trim();
            const asunto = document.getElementById('asunto').value.trim();
            const mensaje = document.getElementById('mensaje').value.trim();
            
            if (!nombre || !email || !asunto || !mensaje) {
                e.preventDefault();
                alert('<?php echo __('contact.error_required'); ?>');
                return false;
            }
            
            if (mensaje.length < 10) {
                e.preventDefault();
                alert('<?php echo __('contact.error_message_short'); ?>');
                return false;
            }
            
            // Mostrar indicador de carga
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '⏳ <?php echo __('contact.sending'); ?>...';
        });
    }
});
</script>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>
