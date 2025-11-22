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
