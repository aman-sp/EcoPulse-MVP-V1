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

        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo '<div style="font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif; max-width: 650px; margin: 4rem auto; padding: 2rem; border-radius: 12px; background: #fff; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.06); color: #1e293b;">';
            echo '<div style="display:flex; align-items:center; gap: 0.75rem; margin-bottom: 1rem;">';
            echo '<span style="font-size: 2rem;">⚠️</span>';
            echo '<h2 style="margin:0; font-size: 1.4rem; color: #b91c1c;">Database Connection Issue</h2>';
            echo '</div>';
            echo '<p style="color: #475569; font-size: 0.95rem; line-height: 1.6;">The application could not reach the database server: <code style="background:#f1f5f9; padding: 2px 6px; border-radius:4px; font-weight:600;">' . htmlspecialchars($host) . '</code>.</p>';
            echo '<div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 1rem; margin: 1.25rem 0; font-size: 0.85rem; color: #991b1b; font-family: monospace;">';
            echo htmlspecialchars($e->getMessage());
            echo '</div>';
            echo '<h4 style="margin: 1.25rem 0 0.5rem; font-size: 0.95rem;">Likely Causes & Fixes:</h4>';
            echo '<ul style="padding-left: 1.25rem; font-size: 0.9rem; color: #334155; line-height: 1.7;">';
            echo '<li><strong>Database is paused / stopped:</strong> If using Aiven or Supabase trial, check your dashboard to ensure the service status is <em>Active</em> or <em>Running</em>.</li>';
            echo '<li><strong>Wrong Hostname:</strong> In your Render dashboard &rarr; Environment variables, check that <code>DB_HOST</code> has no extra spaces or quotes.</li>';
            echo '<li><strong>IP Whitelist / Network:</strong> Ensure external connections from anywhere (<code>0.0.0.0/0</code>) are allowed in your database provider settings.</li>';
            echo '</ul>';
            echo '<div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; text-align: center;">';
            echo '<a href="/" style="color: #059669; font-weight: 600; text-decoration: none;">&larr; Back to EcoPulse Landing Page</a>';
            echo '</div>';
            echo '</div>';
            exit;
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
