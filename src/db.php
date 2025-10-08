<?php
// ============================================================
// db.php
// ------------------------------------------------------------
// This file establishes a connection to the MySQL database
// using PDO (PHP Data Objects). Other PHP files include this
// file to interact with the database securely.
// ============================================================

// Database connection settings
$host = getenv('MYSQL_HOST') ?: 'localhost';         // The hostname where MySQL is running (usually localhost)
$dbname = getenv('MYSQL_DATABASE') ?: 'fuss_db';     // The name of the database (must match schema)
$username = getenv('MYSQL_USER') ?: 'root';          // Default username for local XAMPP/MAMP installations
$password = getenv('MYSQL_PASSWORD') ?: '';          // Default password (empty unless changed in phpMyAdmin)

// Attempt to connect to the database using PDO
try {
    // Create a new PDO connection string
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Configure PDO to throw exceptions if an error occurs
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, show an error message and stop script execution
    die("Database connection failed: " . $e->getMessage());
}
