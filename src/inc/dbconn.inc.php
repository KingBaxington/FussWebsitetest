<?php
// Load database connection info from environment variables
define("DB_HOST", getenv('MYSQL_HOST'));
define("DB_NAME", getenv('MYSQL_DATABASE'));
define("DB_USER", getenv('MYSQL_USER'));
define("DB_PASS", getenv('MYSQL_PASSWORD'));

try {
    // Create a new PDO instance (Don't ask me how this works)
    $conn = new PDO( "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch rows as associative arrays
            PDO::ATTR_EMULATE_PREPARES => false // use native prepared statements
        ]
    );
} catch (PDOException $e) {
    // Handle connection errors gracefully
    echo "<h3>Database connection failed:</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>
