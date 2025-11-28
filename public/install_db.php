<?php
// public/install_db.php
require_once __DIR__ . '/../app/core/Database.php';

echo "<h1>Instalación de Base de Datos (PostgreSQL)</h1>";

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    echo "<p>✅ Conexión exitosa a la base de datos.</p>";
    
    $sqlFile = __DIR__ . '/../sql/init_postgres.sql';
    if (!file_exists($sqlFile)) {
        die("❌ Error: No se encuentra el archivo sql/init_postgres.sql");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Drop tables if they exist to ensure clean install
    $dropSql = "
        DROP TABLE IF EXISTS seguidores CASCADE;
        DROP TABLE IF EXISTS mensajes_contacto CASCADE;
        DROP TABLE IF EXISTS proyecto_comentarios CASCADE;
        DROP TABLE IF EXISTS proyecto_actualizaciones CASCADE;
        DROP TABLE IF EXISTS foro_comentarios CASCADE;
        DROP TABLE IF EXISTS foro_hilos CASCADE;
        DROP TABLE IF EXISTS proyectos CASCADE;
        DROP TABLE IF EXISTS posts_diario CASCADE;
        DROP TABLE IF EXISTS usuarios CASCADE;
        DROP TABLE IF EXISTS estadisticas CASCADE;
    ";
    $pdo->exec($dropSql);
    echo "<p>🗑️ Tablas antiguas eliminadas.</p>";
    
    // Ejecutar múltiples consultas
    $pdo->exec($sql);
    
    echo "<p>✅ Tablas creadas correctamente.</p>";
    echo "<p>✅ Datos iniciales insertados.</p>";
    echo "<hr>";
    echo "<h3>¡Instalación Completada!</h3>";
    echo "<p style='color:red'>⚠️ POR FAVOR, BORRA ESTE ARCHIVO AHORA (public/install_db.php) PARA SEGURIDAD.</p>";
    echo "<a href='/'>Ir a la página principal</a>";

} catch (Exception $e) {
    echo "<p style='color:red'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
