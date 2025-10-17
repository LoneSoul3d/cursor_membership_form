<nav class="admin-sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li>
            <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="members.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'members.php' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Manage Members
            </a>
        </li>
        <li>
            <a href="members.php?status=pending" class="<?php echo isset($_GET['status']) && $_GET['status'] == 'pending' ? 'active' : ''; ?>">
                <i class="fas fa-clock"></i> Pending Approvals
            </a>
        </li>
        <li>
            <a href="members.php?status=approved" class="<?php echo isset($_GET['status']) && $_GET['status'] == 'approved' ? 'active' : ''; ?>">
                <i class="fas fa-check-circle"></i> Approved Members
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-chart-bar"></i> Reports
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-cog"></i> Settings
            </a>
        </li>
    </ul>
</nav>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}
</script>