<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Helper to get env var from multiple sources
        $getEnv = function($key, $default) {
            $val = getenv($key);
            if ($val !== false) return $val;
            if (isset($_ENV[$key])) return $_ENV[$key];
            if (isset($_SERVER[$key])) return $_SERVER[$key];
            return $default;
        };

        $host = $getEnv('DB_HOST', '127.0.0.1');
        $dbname = $getEnv('DB_NAME', 'mikisito_db');
        $user = $getEnv('DB_USER', 'miki');
        $pass = $getEnv('DB_PASS', 'password');
        $port = $getEnv('DB_PORT', '5432');

        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            $this->pdo = new PDO($dsn, $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>
