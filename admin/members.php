<?php
$page_title = "Manage Members";
include 'includes/header.php';
include 'includes/sidebar.php';

include '../../config/database.php';
$conn = getDBConnection();

// Handle status filter
$status_filter = $_GET['status'] ?? '';
$where_clause = '';
if ($status_filter && in_array($status_filter, ['Pending', 'Approved', 'Rejected'])) {
    $where_clause = "WHERE status = '$status_filter'";
    $page_title = ucfirst($status_filter) . " Members";
}

// Handle status update
if (isset($_POST['update_status'])) {
    $member_id = $_POST['member_id'];
    $new_status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE members SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $member_id);
    
    if ($stmt->execute()) {
        $success = "Member status updated successfully!";
    } else {
        $error = "Error updating member status!";
    }
}

// Get members
$members = $conn->query("SELECT * FROM members $where_clause ORDER BY created_at DESC");
?>

<div class="admin-main">
    <div class="content-card">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 20px;">
            <h1><?php echo $page_title; ?></h1>
            <div style="display: flex; gap: 10px;">
                <a href="members.php" class="btn btn-primary <?php echo !$status_filter ? 'active' : ''; ?>">All</a>
                <a href="members.php?status=pending" class="btn btn-warning <?php echo $status_filter == 'pending' ? 'active' : ''; ?>">Pending</a>
                <a href="members.php?status=approved" class="btn btn-success <?php echo $status_filter == 'approved' ? 'active' : ''; ?>">Approved</a>
                <a href="members.php?status=rejected" class="btn btn-danger <?php echo $status_filter == 'rejected' ? 'active' : ''; ?>">Rejected</a>
            </div>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Father Name</th>
                        <th>CNIC</th>
                        <th>Mobile</th>
                        <th>Education</th>
                        <th>Membership Type</th>
                        <th>Status</th>
                        <th>Registration Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($member = $members->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $member['member_id']; ?></td>
                        <td><?php echo $member['first_name'] . ' ' . $member['last_name']; ?></td>
                        <td><?php echo $member['father_name']; ?></td>
                        <td><?php echo $member['cnic']; ?></td>
                        <td><?php echo $member['mobile']; ?></td>
                        <td><?php echo $member['education']; ?></td>
                        <td><?php echo $member['membership_type']; ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                                <select name="status" onchange="this.form.submit()" style="padding: 4px 8px; border-radius: 3px; border: 1px solid #ddd;">
                                    <option value="Pending" <?php echo $member['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Approved" <?php echo $member['status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="Rejected" <?php echo $member['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($member['created_at'])); ?></td>
                        <td class="action-buttons">
                            <a href="view_member.php?id=<?php echo $member['id']; ?>" class="btn btn-info btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="edit_member.php?id=<?php echo $member['id']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?delete=<?php echo $member['id']; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirmAction('Are you sure you want to delete this member?')">
                                <i class="fas fa-trash"></i>
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