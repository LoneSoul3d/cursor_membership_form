<?php
session_start();

// Admin credentials (hashed); for production store in a database
$admin_users = [
    'admin' => [
        // Hash generated with password_hash('admin123', PASSWORD_DEFAULT)
        'password_hash' => '$2y$12$iE.l0yuGF/KFkoz2IrlAo.yRnLR.N1PPmcUFWssVH7fQ7CISq8GRy',
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
    // Regenerate session ID to mitigate fixation
    if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
    header('Location: index.php');
    exit();
}

function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>