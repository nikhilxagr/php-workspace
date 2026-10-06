<?php
// ==========================================================
// Database Connection Configuration Using PHP PDO
// File: config/db.php
//
// How to use in other files:
// require_once 'config/db.php';
// ==========================================================

// 1. Database credentials
// For LOCAL (XAMPP): localhost, library_db, root, ""
// For INFINITYFREE: Copy values from your InfinityFree Client Area -> MySQL Details
$host     = getenv('DB_HOST') ?: "localhost";      // e.g. sqlXXX.infinityfree.com (on InfinityFree)
$db_name  = getenv('DB_NAME') ?: "library_db";     // e.g. if0_XXXXX_library_db (on InfinityFree)
$username = getenv('DB_USER') ?: "root";           // e.g. if0_XXXXX (on InfinityFree)
$password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : ""; // Hosting Account Password
$port     = getenv('DB_PORT') ?: "3306";
$charset  = "utf8mb4";

// 2. Data Source Name (DSN)
// The DSN specifies the driver (mysql), host name, database name, port, and charset
$dsn = "mysql:host=$host;port=$port;dbname=$db_name;charset=$charset";

// 3. PDO connection options
$options = [
    // Throw exceptions when a database error occurs so we can catch it
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

    // Fetch database results as associative arrays by default (e.g., $row['name'])
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

    // Use native prepared statements for enhanced security against SQL injection
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// 4. Establish the database connection using try-catch for error handling
try {
    // Create a new reusable PDO connection object
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Provide $conn as an alias in case code references either $pdo or $conn
    $conn = $pdo;

} catch (PDOException $e) {
    // For security, never expose raw database credentials or internal errors to users
    error_log("Database connection error: " . $e->getMessage());
    die("Database connection failed. Please ensure the MySQL server is running in XAMPP.");
}
?>
