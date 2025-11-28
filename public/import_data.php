<?php
// public/import_data.php
require_once __DIR__ . '/../app/core/Database.php';

echo "<h1>Importar Datos de Backup</h1>";

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    echo "<p>✅ Conexión exitosa a la base de datos.</p>";
    
    $sqlFile = __DIR__ . '/../backup_postgres.sql';
    if (!file_exists($sqlFile)) {
        die("❌ Error: No se encuentra el archivo backup_postgres.sql");
    }
    
    $sql = file_get_contents($sqlFile);
    
    echo "<p>📥 Importando datos...</p>";
    
    // Split by semicolons and execute individually
    $statements = explode(';', $sql);
    $executed = 0;
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        
        // Skip empty statements and comments
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        try {
            $pdo->exec($statement);
            $executed++;
        } catch (PDOException $e) {
            // Ignore duplicate key errors (data might exist)
            if (strpos($e->getMessage(), 'duplicate key') === false && 
                strpos($e->getMessage(), 'Duplicate entry') === false) {
                echo "<p style='color:orange'>⚠️ Warning: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
    }
    
    echo "<p>✅ Ejecutadas {$executed} sentencias SQL.</p>";
    echo "<p>✅ Datos importados correctamente.</p>";
    echo "<hr>";
    echo "<h3>¡Importación Completada!</h3>";
    echo "<p>Usuarios, posts, proyectos y foros han sido restaurados.</p>";
    echo "<p style='color:red'>⚠️ POR FAVOR, BORRA ESTE ARCHIVO AHORA (public/import_data.php) PARA SEGURIDAD.</p>";
    echo "<a href='/'>Ir a la página principal</a>";

} catch (Exception $e) {
    echo "<p style='color:red'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
