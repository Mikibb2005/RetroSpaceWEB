    </main>

    <!-- Barra de tareas XP -->
    <div class="taskbar">
        <button class="start-button">
            <span style="font-size:18px;">🚀</span> Inicio
        </button>
        
        <!-- Selector de Temas -->
        <div class="theme-selector-container" style="margin-left: 10px; margin-right: 10px;">
            <label for="theme-selector" style="margin-right: 5px; font-size: 11px; color: #000;">🎨</label>
            <select id="theme-selector" name="theme" onchange="changeTheme(this.value)" class="xp-select" style="width: auto; margin: 0; height: 24px; padding: 0;" aria-label="Selector de tema visual">
                <option value="win98">Windows 98</option>
                <option value="xp">Windows XP</option>
                <option value="vista">Windows Vista</option>
                <option value="win7">Windows 7</option>
                <option value="win8">Windows 8</option>
                <option value="win10">Windows 10</option>
                <option value="win11">Windows 11</option>
                <option value="macos">macOS</option>
            </select>
        </div>
        
        <!-- Selector de Idiomas -->
        <div class="language-selector-container" style="margin-left: 10px; margin-right: 10px;">
            <label for="language-selector" style="margin-right: 5px; font-size: 11px; color: #000;">🌐</label>
            <select id="language-selector" name="language" onchange="changeLanguage(this.value)" class="xp-select" style="width: auto; margin: 0; height: 24px; padding: 0;" aria-label="Selector de idioma">
                <option value="es">🇪🇸 Español</option>
                <option value="en">🇬🇧 English</option>
                <option value="ca">🏴 Català</option>
                <option value="fr">🇫🇷 Français</option>
                <option value="de">🇩🇪 Deutsch</option>
                <option value="it">🇮🇹 Italiano</option>
                <option value="pt">🇵🇹 Português</option>
                <option value="ru">🇷🇺 Русский</option>
                <option value="ja">🇯🇵 日本語</option>
                <option value="zh">🇨🇳 中文</option>
            </select>
        </div>

        <!-- User Menu Dropdown en Taskbar -->
        <div class="user-menu" style="margin-left: auto;">
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

    <script>
        // Configuración global de la aplicación
        window.APP_CONFIG = {
            BASE_URL: '<?php echo BASE_URL; ?>'
        };
    </script>
    <script src="<?php echo BASE_URL; ?>/js/main.js"></script>
    <script src="<?php echo BASE_URL; ?>/js/translation.js"></script>
    <script>
        // Lógica de Temas
        const themeSelector = document.getElementById('theme-selector');
        const currentTheme = localStorage.getItem('retro_theme') || 'xp';
        themeSelector.value = currentTheme;

        function changeTheme(theme) {
            const link = document.getElementById('theme-style');
            link.href = '<?php echo BASE_URL; ?>/css/' + theme + '.css';
            localStorage.setItem('retro_theme', theme);
        }
        
        // Lógica de Idiomas
        const languageSelector = document.getElementById('language-selector');
        const currentLang = localStorage.getItem('preferredLanguage') || TranslationSystem.detectBrowserLanguage();
        languageSelector.value = currentLang;
        
        function changeLanguage(lang) {
            TranslationSystem.changeLanguage(lang);
        }

        // Inyectar botones de ventana
        document.addEventListener('DOMContentLoaded', function() {
            const titlebars = document.querySelectorAll('.xp-titlebar');
            titlebars.forEach(bar => {
                // Verificar si ya tiene botones (para evitar duplicados)
                if (bar.querySelector('.window-controls')) return;

                const controls = document.createElement('div');
                controls.className = 'window-controls';
                
                // Botones decorativos
                controls.innerHTML = `
                    <div class="win-btn min">_</div>
                    <div class="win-btn max">□</div>
                    <div class="win-btn close">X</div>
                `;
                
                bar.appendChild(controls);
            });
        });
    </script>
</body>
</html>
