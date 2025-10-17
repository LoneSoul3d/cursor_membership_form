<?php
// Main database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'shaikh3d');
define('DB_PASSWORD', 'salamshaikh');
define('DB_NAME', 'membership_db');
define('LOCATION_DB_NAME', 'location_db');


// Create main database connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Create location database connection
function getLocationDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, LOCATION_DB_NAME);
    
    if ($conn->connect_error) {
        die("Location database connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}
?>