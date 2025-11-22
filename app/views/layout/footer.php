    </main>

    <!-- Barra de tareas XP -->
    <div class="taskbar">
        <button class="start-button">
            <span style="font-size:18px;">🚀</span> Inicio
        </button>
        
        <!-- User Menu Dropdown en Taskbar -->
        <div class="user-menu" style="margin-left:auto;">
            <div class="user-menu-btn">
                <?php if (isset($_SESSION['username'])): ?>
                    👤 <?php echo htmlspecialchars($_SESSION['username']); ?>
                <?php else: ?>
                    👤 Cuenta
                <?php endif; ?>
            </div>
            <div class="user-menu-dropdown">
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="<?php echo BASE_URL; ?>/perfil" class="dropdown-item">Mi Perfil</a>
                    <a href="<?php echo BASE_URL; ?>/logout" class="dropdown-item">Salir</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/registro" class="dropdown-item">Registrarse</a>
                    <a href="<?php echo BASE_URL; ?>/login" class="dropdown-item">Iniciar Sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>/js/main.js"></script>
</body>
</html>
