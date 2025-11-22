<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="xp-window" style="max-width:600px; margin:50px auto;">
    <div class="xp-titlebar">
        <span>✉️ Contacto</span>
    </div>
    <div class="xp-content">
        <h2>Envíame un mensaje</h2>
        <p>Correo directo: mikisito@example.com</p>
        
        <form method="post" action="/contacto/enviar">
            <label>Nombre:</label>
            <input type="text" name="nombre" class="xp-input" required>
            
            <label>Email:</label>
            <input type="email" name="email" class="xp-input" required>
            
            <label>Asunto:</label>
            <input type="text" name="asunto" class="xp-input" required>
            
            <label>Mensaje:</label>
            <textarea name="mensaje" class="xp-textarea" required></textarea>
            
            <button type="submit" class="xp-button">Enviar Email</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
