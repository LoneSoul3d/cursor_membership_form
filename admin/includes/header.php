<?php
include 'config/admin_auth.php';

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
                <span>Welcome, <?php echo $_SESSION['admin_name']; ?></span>
                <a href="?logout" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <?php
    if (isset($_GET['logout'])) {
        adminLogout();
    }
    ?>