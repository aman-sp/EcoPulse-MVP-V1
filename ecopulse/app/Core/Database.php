<?php

class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $name = $_ENV['DB_NAME'] ?? 'ecopulse_db';
        $user = $_ENV['DB_USER'] ?? 'root';
        $pass = $_ENV['DB_PASS'] ?? '';
        $port = $_ENV['DB_PORT'] ?? '3306';
        
        $connection = strtolower($_ENV['DB_CONNECTION'] ?? '');
        $driver = ($connection === 'pgsql' || $port == '5432' || $port == '24272' || $port == '6543') ? 'pgsql' : 'mysql';
        $sslMode = $_ENV['DB_SSLMODE'] ?? 'require';
        
        if ($driver === 'pgsql') {
            $dsn = "pgsql:host={$host};port={$port};dbname={$name};sslmode={$sslMode}";
        } else {
            $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
        }

        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
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
