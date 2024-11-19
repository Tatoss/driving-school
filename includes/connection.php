<?php
// Database configuration
define('DB_HOST', 'localhost');       // Database host
define('DB_USER', 'root');           // Database username
define('DB_PASS', '');               // Database password
define('DB_NAME', 'drivingschool');  // Database name

try {
    // Create a PDO connection
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Enable exceptions for errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Fetch associative arrays by default
        PDO::ATTR_EMULATE_PREPARES => false, // Disable emulation of prepared statements
    ];
    
    // Create the PDO instance
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    // Uncomment to debug the connection
    // echo "Connected successfully";

} catch (PDOException $e) {
    // If connection fails, display error
    die("Connection failed: " . $e->getMessage());
}
?>
