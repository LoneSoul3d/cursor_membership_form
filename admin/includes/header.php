<?php
include 'config/admin_auth.php';
include '../../includes/functions.php';

if (!isAdminLoggedIn()) {
    redirectToLogin();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Admin Panel'; ?> - All Pakistan Ahle Hadees Federation</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header class="admin-header">
        <div class="header-content">
            <div>
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h2>Admin Panel</h2>
            </div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <span>Welcome, <?php echo e($_SESSION['admin_name']); ?></span>
                <form method="POST" style="display:inline; margin:0;" onsubmit="return confirmAction('Logout now?');">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                    <input type="hidden" name="logout" value="1">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
        if (verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            adminLogout();
        }
    }
    ?>