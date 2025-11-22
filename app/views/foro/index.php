<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="xp-window">
    <div class="xp-titlebar">
        <span>💬 Foro Comunitario</span>
    </div>
    <div class="xp-content">
        <h2>Hilos Recientes</h2>
        
        <?php if ($this->user->isLogged()): ?>
            <button class="xp-button" onclick="location.href='/foro/crear'">+ Crear Hilo</button>
        <?php else: ?>
            <p><a href="/login" class="xp-button">Inicia sesión para crear hilos</a></p>
        <?php endif; ?>
        
        <ul class="xp-list">
            <?php foreach ($hilos as $hilo): ?>
                <li class="xp-list-item" onclick="location.href='/foro/hilo/<?php echo $hilo['id']; ?>'">
                    <h3><?php echo htmlspecialchars($hilo['titulo']); ?>
                        <?php if ($hilo['sticky']): ?><span style="color:red;">[STICKY]</span><?php endif; ?>
                    </h3>
                    <p><?php echo htmlspecialchars($hilo['descripcion']); ?></p>
                    <small>por <?php echo $hilo['autor']; ?> | 
                           <?php echo $hilo['fecha_creacion']; ?> | 
                           <?php echo $hilo['comentarios']; ?> comentarios</small>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
