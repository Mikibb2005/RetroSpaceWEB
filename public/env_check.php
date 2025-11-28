<?php
// public/debug_db.php
header('Content-Type: text/plain');

echo "Debugging Environment Variables:\n";
echo "--------------------------------\n";

$vars = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];

foreach ($vars as $var) {
    $val = getenv($var);
    echo "$var (getenv): " . ($val ? $val : "NOT SET (False)") . "\n";
    
    $valEnv = $_ENV[$var] ?? null;
    echo "$var (\$_ENV): " . ($valEnv ? $valEnv : "NOT SET (Null)") . "\n";
    
    $valServer = $_SERVER[$var] ?? null;
    echo "$var (\$_SERVER): " . ($valServer ? $valServer : "NOT SET (Null)") . "\n";
    echo "\n";
}

echo "PDO Drivers: " . implode(', ', PDO::getAvailableDrivers()) . "\n";
