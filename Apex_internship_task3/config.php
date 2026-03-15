<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Database Singleton
 */
class DB {
    private static $pdo = null;

    public static function connect() {
        if (self::$pdo === null) {
            $host = 'localhost';
            $db   = 'blog';
            $user = 'root';
            $pass = '';
            $charset = 'utf8mb4';

            // DSN with charset included for security
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // Real prepared statements, not emulated
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                 self::$pdo = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                 // In production, log this and show a generic error
                 die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

// Global hook for easy migration
$pdo = DB::connect();

// Include security utilities
require_once __DIR__ . '/includes/Validator.php';
require_once __DIR__ . '/includes/Auth.php';

// Security Headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:;");
?>