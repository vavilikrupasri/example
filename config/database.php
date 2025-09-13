<?php
/**
 * Database configuration for Adhyatmik Product Catalog
 * Task 1: PHP + MySQL Backend
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'adhyatmik_products');
define('DB_USER', 'root');
define('DB_PASS', 'Siyara@191988');

// Create database connection
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Test database connection
try {
    $pdo = getDBConnection();
    echo "<!-- Database connection successful -->";
} catch (Exception $e) {
    echo "<!-- Database connection failed: " . $e->getMessage() . " -->";
}
?>
