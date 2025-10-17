<?php
session_start();

// Admin credentials (in production, store these in database with proper hashing)
$admin_users = [
    'admin' => [
        'password' => 'admin123', // Change this in production
        'name' => 'System Administrator',
        'role' => 'superadmin'
    ]
];

function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function redirectToLogin() {
    header('Location: index.php');
    exit();
}

function adminLogout() {
    session_destroy();
    header('Location: index.php');
    exit();
}
?>