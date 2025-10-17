<?php
// Database configuration via environment variables
// Ensure you set these in your deployment environment (e.g., web server or .env loader)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USERNAME', getenv('DB_USERNAME') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'membership_db');
define('LOCATION_DB_NAME', getenv('LOCATION_DB_NAME') ?: 'location_db');

// Create main database connection
function getDBConnection() {
    $conn = @new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        // Avoid leaking detailed errors to users
        exit('Database connection failed.');
    }
    // Set charset explicitly
    $conn->set_charset('utf8mb4');
    return $conn;
}

// Create location database connection
function getLocationDBConnection() {
    $conn = @new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, LOCATION_DB_NAME);
    if ($conn->connect_error) {
        exit('Database connection failed.');
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
?>