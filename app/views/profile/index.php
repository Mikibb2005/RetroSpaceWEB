<?php
$pageTitle = 'Perfil de ' . htmlspecialchars($user['username']) . ' - RetroSpace';
require __DIR__ . '/../layout/header.php'; 
?>

<style>
.activity-item:hover {
    background-color: #e0e0e0 !important;
    transform: translateX(3px);
    transition: all 0.2s ease;
}
</style>

<div class="xp-window" style="max-width: 900px; margin: 20px auto;">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            👤 Perfil de <?php echo htmlspecialchars($user['username']); ?>
        </div>
    </div>
    <div class="xp-content">
        <!-- Header del Perfil -->
        <div style="display: flex; gap: 20px; align-items: flex-start; margin-bottom: 20px;">
            <div>
                <?php if ($user['avatar']): ?>
                    <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" style="width: 120px; height: 120px; border: 2px solid #000; object-fit: cover;">
                <?php else: ?>
                    <div style="width: 120px; height: 120px; background: #ccc; border: 2px solid #000; display: flex; align-items: center; justify-content: center; font-size: 40px;">👤</div>
                <?php endif; ?>
            </div>
            <div style="flex: 1;">
                <h2 style="margin: 0;">
                    <?php echo htmlspecialchars($user['username']); ?>
                    <?php if (!empty($user['nombre_real'])): ?>
                        <span style="font-size: 0.7em; color: #666; font-weight: normal;">(<?php echo htmlspecialchars($user['nombre_real']); ?>)</span>
                    <?php endif; ?>
                </h2>
                <p style="margin: 5px 0;"><strong>Rol:</strong> <?php echo ucfirst($user['rol']); ?></p>
                <p style="margin: 5px 0;"><strong>Miembro desde:</strong> <?php echo date('d/m/Y', strtotime($user['fecha_registro'])); ?></p>
                
                <?php 
                $etiquetas = isset($user['etiquetas_so']) ? json_decode($user['etiquetas_so'], true) : [];
                if (!empty($etiquetas)): 
                ?>
                <div style="margin: 10px 0;">
                    <strong>Sistemas Favoritos:</strong><br>
                    <div style="display: flex; flex-wrap: wrap; gap: 5px; margin-top: 5px;">
                        <?php foreach ($etiquetas as $tag): ?>
                            <span style="background: #eee; border: 1px solid #999; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                🖥️ <?php echo htmlspecialchars($tag); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div style="margin-top: 10px;">
                    <?php if ($isOwnProfile): ?>
                        <a href="/perfil/editar" class="xp-button">✏️ Editar Perfil</a>
                    <?php elseif (isset($_SESSION['user_id'])): ?>
                        <?php if ($isFollowing): ?>
                            <a href="/perfil/<?php echo $user['id']; ?>/unfollow" class="xp-button">Dejar de seguir</a>
                        <?php else: ?>
                            <a href="/perfil/<?php echo $user['id']; ?>/follow" class="xp-button">Seguir</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pestañas -->
        <div class="xp-tabs" style="margin-bottom: 10px; border-bottom: 1px solid #999; padding-bottom: 5px;">
            <button onclick="openTab(event, 'tab-actividad')" class="xp-button tab-link active">Actividad</button>
            <button onclick="openTab(event, 'tab-bio')" class="xp-button tab-link">Biografía</button>
            <button onclick="openTab(event, 'tab-seguidores')" class="xp-button tab-link">Seguidores (<?php echo count($followers); ?>)</button>
            <button onclick="openTab(event, 'tab-siguiendo')" class="xp-button tab-link">Siguiendo (<?php echo count($following); ?>)</button>
        </div>

        <!-- Contenido Pestañas -->
        <div id="tab-actividad" class="tab-content" style="display: block;">
            <h3>Actividad Reciente</h3>
            <?php if (empty($activity)): ?>
                <p>No hay actividad reciente.</p>
            <?php else: ?>
                <ul class="xp-list">
                    <?php foreach ($activity as $item): ?>
                        <li class="xp-list-item activity-item" style="cursor: pointer;" onclick="location.href='<?php echo BASE_URL; ?>/foro/hilo/<?php echo $item['hilo_id']; ?>'">
                            <?php if ($item['type'] == 'thread'): ?>
                                📝 <strong>Hilo:</strong> <?php echo htmlspecialchars($item['content']); ?>
                            <?php else: ?>
                                💬 <strong>Comentario:</strong> <?php echo htmlspecialchars(substr($item['content'], 0, 100)); ?><?php echo strlen($item['content']) > 100 ? '...' : ''; ?>
                            <?php endif; ?>
                            <br>
                            <small style="color: #666;">📅 <?php echo date('d/m/Y H:i', strtotime($item['date'])); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div id="tab-bio" class="tab-content" style="display: none;">
            <h3>Biografía</h3>
            <?php if ($user['biografia']): ?>
                <div style="padding: 10px; background: white; border: 2px inset #fff;">
                    <?php echo nl2br(htmlspecialchars($user['biografia'])); ?>
                </div>
            <?php else: ?>
                <p>Este usuario no ha escrito una biografía aún.</p>
            <?php endif; ?>
        </div>

        <div id="tab-seguidores" class="tab-content" style="display: none;">
            <h3>Seguidores</h3>
            <?php if (empty($followers)): ?>
                <p>Nadie sigue a este usuario aún.</p>
            <?php else: ?>
                <ul class="xp-list">
                    <?php foreach ($followers as $f): ?>
                        <li class="xp-list-item" onclick="location.href='<?php echo BASE_URL; ?>/perfil/<?php echo $f['id']; ?>'">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <?php if (!empty($f['avatar'])): ?>
                                    <img src="<?php echo htmlspecialchars($f['avatar']); ?>" style="width: 30px; height: 30px; border-radius: 50%;">
                                <?php else: ?>
                                    <span>👤</span>
                                <?php endif; ?>
                                <strong><?php echo htmlspecialchars($f['username']); ?></strong>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div id="tab-siguiendo" class="tab-content" style="display: none;">
            <h3>Siguiendo</h3>
            <?php if (empty($following)): ?>
                <p>Este usuario no sigue a nadie aún.</p>
            <?php else: ?>
                <ul class="xp-list">
                    <?php foreach ($following as $f): ?>
                        <li class="xp-list-item" onclick="location.href='<?php echo BASE_URL; ?>/perfil/<?php echo $f['id']; ?>'">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <?php if (!empty($f['avatar'])): ?>
                                    <img src="<?php echo htmlspecialchars($f['avatar']); ?>" style="width: 30px; height: 30px; border-radius: 50%;">
                                <?php else: ?>
                                    <span>👤</span>
                                <?php endif; ?>
                                <strong><?php echo htmlspecialchars($f['username']); ?></strong>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tab-link");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
            // Reset style for non-active tabs if needed, but class handling is better
            tablinks[i].style.fontWeight = "normal";
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
        evt.currentTarget.style.fontWeight = "bold";
    }
    </script>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
