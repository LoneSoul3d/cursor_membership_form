<?php
include 'config/admin_auth.php';
include '../includes/functions.php';

if (isAdminLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $csrf = $_POST['csrf_token'] ?? '';

    // Simple session-based rate limit
    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0);
    $_SESSION['last_login_attempt'] = ($_SESSION['last_login_attempt'] ?? 0);
    $now = time();
    if ($_SESSION['login_attempts'] >= 5 && ($now - $_SESSION['last_login_attempt']) < 300) {
        $error = "Too many attempts. Try again later.";
    } else if (!verifyCsrfToken($csrf)) {
        $error = "Invalid session. Please try again.";
    } else if (isset($admin_users[$username]) && password_verify($password, $admin_users[$username]['password_hash'])) {
        // Regenerate session ID on login
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_name'] = $admin_users[$username]['name'];
        $_SESSION['admin_role'] = $admin_users[$username]['role'];
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_login_attempt'] = $now;
        
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Invalid username or password!";
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        $_SESSION['last_login_attempt'] = $now;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - All Pakistan Ahle Hadees Federation</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1><i class="fas fa-users-cog"></i> Admin Panel</h1>
                <p>All Pakistan Ahle Hadees Federation</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo e($error); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>