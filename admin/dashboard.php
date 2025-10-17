<?php
$page_title = "Dashboard";
include 'includes/header.php';
include 'includes/sidebar.php';

include '../../config/database.php';
include '../../includes/functions.php';
$conn = getDBConnection();

// Get statistics
$total_members = $conn->query("SELECT COUNT(*) as total FROM members")->fetch_assoc()['total'];
$pending_members = $conn->query("SELECT COUNT(*) as total FROM members WHERE status = 'Pending'")->fetch_assoc()['total'];
$approved_members = $conn->query("SELECT COUNT(*) as total FROM members WHERE status = 'Approved'")->fetch_assoc()['total'];
$rejected_members = $conn->query("SELECT COUNT(*) as total FROM members WHERE status = 'Rejected'")->fetch_assoc()['total'];

// Get recent members
$recent_members = $conn->query("SELECT * FROM members ORDER BY created_at DESC LIMIT 5");
?>

<div class="admin-main">
    <div class="content-card">
        <h1>Dashboard Overview</h1>
        <p>Welcome to the membership management system</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Members</h3>
            <div class="number"><?php echo (int)$total_members; ?></div>
        </div>
        <div class="stat-card pending">
            <h3>Pending Approval</h3>
            <div class="number"><?php echo (int)$pending_members; ?></div>
        </div>
        <div class="stat-card approved">
            <h3>Approved</h3>
            <div class="number"><?php echo (int)$approved_members; ?></div>
        </div>
        <div class="stat-card rejected">
            <h3>Rejected</h3>
            <div class="number"><?php echo (int)$rejected_members; ?></div>
        </div>
    </div>

    <div class="content-card">
        <h2>Recent Registrations</h2>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>CNIC</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($member = $recent_members->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo e($member['member_id']); ?></td>
                        <td><?php echo e($member['first_name'] . ' ' . $member['last_name']); ?></td>
                        <td><?php echo e($member['cnic']); ?></td>
                        <td><?php echo e($member['mobile']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($member['status']); ?>">
                                <?php echo e($member['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($member['created_at'])); ?></td>
                        <td class="action-buttons">
                            <a href="view_member.php?id=<?php echo (int)$member['id']; ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="edit_member.php?id=<?php echo (int)$member['id']; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>