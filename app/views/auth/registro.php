<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="xp-window" style="max-width:400px;margin:50px auto;">
    <div class="xp-titlebar">
        <span>📝 Registro de Usuario</span>
    </div>
    <div class="xp-content">
        <?php if (isset($error)): ?>
            <div style="background: #ffcccc; padding: 10px; margin-bottom: 10px; border: 1px solid #cc0000;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <label for="username"><strong>Usuario:</strong></label>
            <input type="text" name="username" id="username" placeholder="Usuario" class="xp-input" required><br>
            
            <label for="email"><strong>Email:</strong></label>
            <input type="email" name="email" id="email" placeholder="Email" class="xp-input" required><br>
            
            <label for="password"><strong>Contraseña:</strong></label>
            <input type="password" name="password" id="password" placeholder="Contraseña" class="xp-input" required><br>
            
            <button type="submit" class="xp-button">Registrarse</button>
            <a href="/login" class="xp-button">Ya tengo cuenta</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
