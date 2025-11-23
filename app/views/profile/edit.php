<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - RetroSpace</title>
    <link rel="stylesheet" href="/css/xp.css">
</head>
<body>
<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="xp-window" style="max-width: 700px; margin: 20px auto;">
    <div class="xp-titlebar">
        <div class="xp-titlebar-text">
            ✏️ Editar Perfil
        </div>
    </div>
    <div class="xp-content">
        <form method="POST" action="/perfil/editar">
            <div style="margin-bottom: 15px;">
                <label for="nombre_real"><strong>Nombre Real (Opcional):</strong></label>
                <input type="text" name="nombre_real" id="nombre_real" class="xp-input" value="<?php echo htmlspecialchars($user['nombre_real'] ?? ''); ?>" placeholder="Tu nombre real">
            </div>

            <div style="margin-bottom: 15px;">
                <label><strong>Elige tu Avatar:</strong></label>
                <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-top: 5px; background: #fff; padding: 10px; border: 2px inset #fff;">
                    <?php 
                    require_once __DIR__ . '/../../helpers/avatar_helper.php';
                    for ($i = 1; $i <= 15; $i++): 
                        $svg = getAvatarSvg($i);
                        $isSelected = false; // Logic to check if selected could be added here if we parsed the current avatar URL
                    ?>
                        <label style="cursor: pointer; text-align: center;">
                            <input type="radio" name="avatar" value="avatar-<?php echo $i; ?>.png" style="margin-bottom: 5px;">
                            <br>
                            <img src="<?php echo $svg; ?>" style="width: 50px; height: 50px; border-radius: 50%;">
                        </label>
                    <?php endfor; ?>
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label><strong>Sistemas Operativos Favoritos (Máx 10):</strong></label>
                <div style="max-height: 300px; overflow-y: auto; background: #fff; padding: 10px; border: 2px inset #fff; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 5px;">
                    <?php 
                    $currentTags = isset($user['etiquetas_so']) ? json_decode($user['etiquetas_so'], true) : [];
                    if (!is_array($currentTags)) $currentTags = [];
                    
                    foreach ($availableTags as $tag): 
                        $checked = in_array($tag, $currentTags) ? 'checked' : '';
                    ?>
                        <label style="font-size: 12px; display: flex; align-items: center; gap: 5px; cursor: pointer;">
                            <input type="checkbox" name="etiquetas_so[]" value="<?php echo htmlspecialchars($tag); ?>" <?php echo $checked; ?> onclick="checkLimit(this)">
                            <?php echo htmlspecialchars($tag); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <small style="color: #666;">Seleccionados: <span id="tag-count">0</span>/10</small>
            </div>

            <script>
            function checkLimit(checkbox) {
                var checkboxes = document.getElementsByName('etiquetas_so[]');
                var count = 0;
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].checked) count++;
                }
                
                if (count > 10) {
                    checkbox.checked = false;
                    alert('Solo puedes seleccionar hasta 10 sistemas operativos.');
                    count--;
                }
                document.getElementById('tag-count').innerText = count;
            }
            // Init count
            document.addEventListener('DOMContentLoaded', function() {
                var checkboxes = document.getElementsByName('etiquetas_so[]');
                var count = 0;
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].checked) count++;
                }
                document.getElementById('tag-count').innerText = count;
            });
            </script>

            <label for="biografia"><strong>Biografía:</strong></label>
            <textarea name="biografia" id="biografia" class="xp-textarea" placeholder="Cuéntanos sobre ti..."><?php echo htmlspecialchars($user['biografia'] ?? ''); ?></textarea>
            
            <div style="margin-top: 15px;">
                <button type="submit" class="xp-button">Guardar cambios</button>
                <a href="/perfil" class="xp-button">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
