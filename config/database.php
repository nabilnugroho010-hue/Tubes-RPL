<?php
/**
 * Database Configuration
 * Supports both environment variables and direct configuration
 * For Railway: Use environment variables
 * For XAMPP: Use direct configuration or create .env file
 * Uses MySQLi for compatibility with existing code
 */

class DatabaseConfig {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $this->connection = $this->createConnection();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function getEnvConfig() {
        // Try to load from .env file if exists
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                list($name, $value) = explode('=', $line, 2);
                $_ENV[trim($name)] = trim($value);
            }
        }
        
        return [
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'port' => $_ENV['DB_PORT'] ?? '3306',
            'database' => $_ENV['DB_NAME'] ?? 'db_pemesanan',
            'username' => $_ENV['DB_USER'] ?? 'root',
            'password' => $_ENV['DB_PASS'] ?? ''
        ];
    }
    
    private function createConnection() {
        $config = $this->getEnvConfig();
        
        try {
            $connection = mysqli_connect(
                $config['host'],
                $config['username'],
                $config['password'],
                $config['database'],
                $config['port']
            );
            
            if (!$connection) {
                throw new Exception("Database Connection Failed: " . mysqli_connect_error());
            }
            
            // Set charset to utf8mb4
            mysqli_set_charset($connection, 'utf8mb4');
            
            return $connection;
        } catch (Exception $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Prevent cloning
    private function __clone() {}
    
    // Prevent unserialization
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

// Backward compatibility - create global $conn variable
function getDatabaseConnection() {
    return DatabaseConfig::getInstance()->getConnection();
}

// For backward compatibility with existing code
global $conn;
$conn = getDatabaseConnection();
