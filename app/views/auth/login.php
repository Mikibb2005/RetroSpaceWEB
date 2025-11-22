<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="xp-window" style="max-width:400px;margin:50px auto;">
    <div class="xp-titlebar">
        <span>🔐 Iniciar Sesión</span>
    </div>
    <div class="xp-content">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div style="background: #ccffcc; padding: 10px; margin-bottom: 10px; border: 1px solid #00cc00; color: #006600;">
                <?php 
                    echo $_SESSION['success_message']; 
                    unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div style="background: #ffcccc; padding: 10px; margin-bottom: 10px; border: 1px solid #cc0000;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <label for="username"><strong>Usuario:</strong></label>
            <input type="text" name="username" id="username" placeholder="Usuario" class="xp-input" required><br>
            
            <label for="password"><strong>Contraseña:</strong></label>
            <input type="password" name="password" id="password" placeholder="Contraseña" class="xp-input" required><br>
            
            <button type="submit" class="xp-button">Entrar</button>
            <a href="/registro" class="xp-button">Crear cuenta</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
