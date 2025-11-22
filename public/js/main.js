// Efectos XP
document.addEventListener('DOMContentLoaded', function() {
    // Efecto de onda en botones
    const buttons = document.querySelectorAll('.xp-button, .nav-item, .xp-list-item');
    buttons.forEach(btn => {
        btn.addEventListener('mousedown', function(e) {
            this.style.transform = 'scale(0.95)';
        });
        btn.addEventListener('mouseup', function(e) {
            this.style.transform = 'scale(1)';
        });
    });

    // Simular reloj en taskbar
    function updateClock() {
        const now = new Date();
        const time = now.toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'});
        const taskbar = document.querySelector('.taskbar');
        let clock = taskbar.querySelector('.clock');
        if (!clock) {
            clock = document.createElement('div');
            clock.className = 'clock';
            clock.style.cssText = 'color:white; padding:0 10px; border-left:1px solid #fff;';
            taskbar.appendChild(clock);
        }
        clock.textContent = time;
    }
    updateClock();
    setInterval(updateClock, 60000);
});
