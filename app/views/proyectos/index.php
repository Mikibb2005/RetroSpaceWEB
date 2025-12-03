<?php require __DIR__ . '/../layout/header.php'; ?>

<div style="max-width: 1400px; margin: 20px auto; display: flex; gap: 20px; flex-wrap: wrap;">
    <!-- Main Projects List (Left Column) -->
    <div class="xp-window" style="flex: 1 1 680px; min-width: 320px;">
        <div class="xp-titlebar">
            <div class="xp-titlebar-text">
                ⚙️ <?php echo __('projects.title'); ?>
            </div>
        </div>
        <div class="xp-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                <h2 style="margin: 0;"><?php echo __('projects.subtitle'); ?></h2>
                <?php if ($canCreate): ?>
                    <a href="<?php echo BASE_URL; ?>/proyectos/crear" class="xp-button" style="font-weight: bold;"><?php echo __('projects.new'); ?></a>
                <?php endif; ?>
            </div>
            
            <!-- Filtro desplegable de categorías -->
            <div style="margin-bottom: 20px;">
                <label for="categoria-filter" style="font-weight: bold; margin-right: 10px;">🔍 <?php echo __('projects.filter'); ?></label>
                <select id="categoria-filter" class="xp-select" style="width: auto; min-width: 200px;" onchange="filtrarCategoria(this.value)">
                    <option value="" <?php echo (!isset($_GET['categoria'])) ? 'selected' : ''; ?>><?php echo __('projects.all'); ?></option>
                    <option value="Programacion" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'Programacion') ? 'selected' : ''; ?>>💻 <?php echo __('category.programming'); ?></option>
                    <option value="Hardware" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'Hardware') ? 'selected' : ''; ?>>🔧 <?php echo __('category.hardware'); ?></option>
                    <option value="Mods" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'Mods') ? 'selected' : ''; ?>>🎮 <?php echo __('category.mods'); ?></option>
                    <option value="GameMaker" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'GameMaker') ? 'selected' : ''; ?>>🎲 <?php echo __('category.gamemaker'); ?></option>
                </select>
            </div>
            
            <?php if (empty($proyectos)): ?>
                <p style="text-align: center; padding: 40px; color: #666;">
                    📭 <?php echo __('projects.none'); ?>
                    <?php if (isset($_GET['categoria'])): ?>
                        <br><a href="<?php echo BASE_URL; ?>/proyectos"><?php echo __('projects.view_all'); ?></a>
                    <?php endif; ?>
                </p>
            <?php else: ?>
                <ul class="xp-list">
                    <?php foreach ($proyectos as $proyecto): ?>
                        <li class="xp-list-item" style="cursor: pointer;" onclick="location.href='<?php echo BASE_URL; ?>/proyectos/<?php echo $proyecto['id']; ?>'">
                            <!-- Título como enlace azul (traducible) -->
                            <h3 style="margin: 0 0 10px 0;">
                                <a href="<?php echo BASE_URL; ?>/proyectos/<?php echo $proyecto['id']; ?>" 
                                   style="color: #0066cc; text-decoration: none;"
                                   onclick="event.stopPropagation();">
                                    <span data-translatable="title" data-original-lang="es" data-original-text="<?php echo htmlspecialchars(trim($proyecto['titulo'])); ?>">
                                        <?php echo htmlspecialchars($proyecto['titulo']); ?>
                                    </span>
                                </a>
                                <!-- Etiqueta de categoría inline (traducible) -->
                                <span style="display: inline-block; margin-left: 10px; padding: 2px 8px; background: #0066cc; color: white; border-radius: 3px; font-size: 11px; font-weight: normal;">
                                    <?php 
                                    $iconos = [
                                        'Programacion' => '💻',
                                        'Hardware' => '🔧',
                                        'Mods' => '🎮',
                                        'GameMaker' => '🎲'
                                    ];
                                    echo $iconos[$proyecto['categoria']] ?? '⚙️';
                                    ?> 
                                    <span data-translatable="category" data-original-lang="es" data-original-text="<?php echo htmlspecialchars($proyecto['categoria']); ?>">
                                        <?php echo htmlspecialchars($proyecto['categoria']); ?>
                                    </span>
                                </span>
                            </h3>
                            
                            
                            <?php
                            // Pre-calcular la descripción para evitar código PHP dentro de atributos HTML
                            $descripcion = strip_tags($proyecto['descripcion']);
                            $descLimpia = str_replace(["\r", "\n"], " ", $descripcion);
                            $descParaMostrar = (strlen($descripcion) > 200) ? substr($descripcion, 0, 200) . '...' : $descripcion;
                            $descParaAtributo = (strlen($descLimpia) > 200) ? substr($descLimpia, 0, 200) . '...' : $descLimpia;
                            ?>
                            
                            <!-- Descripción (traducible) -->
                            <p style="margin: 5px 0;" 
                               data-translatable="description" 
                               data-original-lang="es" 
                               data-original-text="<?php echo htmlspecialchars($descParaAtributo); ?>">
                                <?php echo htmlspecialchars($descParaMostrar); ?>
                            </p>
                            
                            <!-- Metadatos -->
                            <small>
                                👤 <?php echo __('projects.by'); ?> <strong><?php echo htmlspecialchars($proyecto['autor'] ?? 'Anónimo'); ?></strong> 
                                | 📅 <?php echo Lang::formatDate($proyecto['fecha_actualizacion']); ?>
                            </small>
                            
                            <!-- Preview de imagen del proyecto (al final) -->
                            <?php 
                            $archivos = isset($proyecto['archivos']) ? json_decode($proyecto['archivos'], true) : [];
                            $imagenPreview = null;
                            
                            // Buscar primera imagen en archivos o usar imagen principal
                            if (!empty($archivos)) {
                                foreach ($archivos as $archivo) {
                                    $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
                                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])) {
                                        $imagenPreview = $archivo;
                                        break;
                                    }
                                }
                            }
                            if (!$imagenPreview && !empty($proyecto['imagen'])) {
                                $imagenPreview = $proyecto['imagen'];
                            }
                            
                            if ($imagenPreview): 
                            ?>
                                <div style="text-align: center; margin: 10px 0 0 0;">
                                    <img src="<?php echo BASE_URL . htmlspecialchars($imagenPreview); ?>" 
                                         alt="Preview de <?php echo htmlspecialchars($proyecto['titulo']); ?>" 
                                         style="max-width: 100%; max-height: 200px; border: 1px solid #999; object-fit: cover;">
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Info Panel (Right Column) -->
    <div class="xp-window" style="flex: 0 1 380px; min-width: 280px; align-self: flex-start; position: sticky; top: 20px;">
        <div class="xp-titlebar">
            <div class="xp-titlebar-text">
                ℹ️ <?php echo __('projects.info.title'); ?>
            </div>
        </div>
        <div class="xp-content" style="font-size: 14px; line-height: 1.6;">
            <p style="margin-top: 0;">
                <strong><?php echo __('projects.info.description'); ?></strong>
            </p>
            
            <h3 style="margin: 15px 0 10px 0; font-size: 16px; color: #0066cc;">
                ❓ <?php echo __('projects.info.what_can_do'); ?>
            </h3>
            <ul style="margin: 5px 0 15px 0; padding-left: 20px;">
                <li style="margin: 5px 0;">📖 <?php echo __('projects.info.browse'); ?></li>
                <li style="margin: 5px 0;">➕ <?php echo __('projects.info.create'); ?></li>
                <li style="margin: 5px 0;">🔄 <?php echo __('projects.info.update'); ?></li>
                <li style="margin: 5px 0;">💬 <?php echo __('projects.info.comment'); ?></li>
            </ul>
            
            <h3 style="margin: 15px 0 10px 0; font-size: 16px; color: #0066cc;">
                📁 <?php echo __('projects.info.categories_title'); ?>
            </h3>
            <ul style="margin: 5px 0 15px 0; padding-left: 20px;">
                <li style="margin: 5px 0;">💻 <strong><?php echo __('category.programming'); ?>:</strong> <?php echo __('projects.info.cat_programming'); ?></li>
                <li style="margin: 5px 0;">🔧 <strong><?php echo __('category.hardware'); ?>:</strong> <?php echo __('projects.info.cat_hardware'); ?></li>
                <li style="margin: 5px 0;">🎮 <strong><?php echo __('category.mods'); ?>:</strong> <?php echo __('projects.info.cat_mods'); ?></li>
                <li style="margin: 5px 0;">🎲 <strong><?php echo __('category.gamemaker'); ?>:</strong> <?php echo __('projects.info.cat_gamemaker'); ?></li>
            </ul>
            
            <h3 style="margin: 15px 0 10px 0; font-size: 16px; color: #0066cc;">
                🚀 <?php echo __('projects.info.how_to_start'); ?>
            </h3>
            <ol style="margin: 5px 0 10px 0; padding-left: 20px;">
                <li style="margin: 5px 0;"><strong>1.</strong> <?php echo __('projects.info.step1'); ?></li>
                <li style="margin: 5px 0;"><strong>2.</strong> <?php echo __('projects.info.step2'); ?></li>
                <li style="margin: 5px 0;"><strong>3.</strong> <?php echo __('projects.info.step3'); ?></li>
            </ol>
            
            <?php if ($canCreate): ?>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="<?php echo BASE_URL; ?>/proyectos/crear" class="xp-button" style="font-weight: bold; display: inline-block; padding: 8px 16px;">
                        ✨ <?php echo __('projects.new'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function filtrarCategoria(categoria) {
    if (categoria) {
        window.location.href = '<?php echo BASE_URL; ?>/proyectos?categoria=' + categoria;
    } else {
        window.location.href = '<?php echo BASE_URL; ?>/proyectos';
    }
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
