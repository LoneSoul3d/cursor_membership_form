<?php
$page_title = "View Member";
include 'includes/header.php';
include 'includes/sidebar.php';

include '../../config/database.php';
$conn = getDBConnection();

if (!isset($_GET['id'])) {
    header('Location: members.php');
    exit();
}

$member_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();

if (!$member) {
    header('Location: members.php');
    exit();
}
?>

<div class="admin-main">
    <div class="content-card">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 20px;">
            <h1>Member Details</h1>
            <a href="members.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Members
            </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Personal Information -->
            <div class="content-card">
                <h3><i class="fas fa-user"></i> Personal Information</h3>
                <table style="width: 100%;">
                    <tr><td><strong>Member ID:</strong></td><td><?php echo $member['member_id']; ?></td></tr>
                    <tr><td><strong>Name:</strong></td><td><?php echo $member['first_name'] . ' ' . $member['last_name']; ?></td></tr>
                    <tr><td><strong>Father Name:</strong></td><td><?php echo $member['father_name']; ?></td></tr>
                    <tr><td><strong>CNIC:</strong></td><td><?php echo $member['cnic']; ?></td></tr>
                    <tr><td><strong>Mobile:</strong></td><td><?php echo $member['mobile']; ?></td></tr>
                    <tr><td><strong>Gender:</strong></td><td><?php echo $member['gender']; ?></td></tr>
                    <tr><td><strong>Date of Birth:</strong></td><td><?php echo date('M j, Y', strtotime($member['dob'])); ?></td></tr>
                    <tr><td><strong>Marital Status:</strong></td><td><?php echo $member['marital_status']; ?></td></tr>
                </table>
            </div>

            <!-- Education & Occupation -->
            <div class="content-card">
                <h3><i class="fas fa-graduation-cap"></i> Education & Occupation</h3>
                <table style="width: 100%;">
                    <tr><td><strong>Education:</strong></td><td><?php echo $member['education']; ?></td></tr>
                    <tr><td><strong>Occupation:</strong></td><td><?php echo $member['occupation']; ?></td></tr>
                    <tr><td><strong>Membership Type:</strong></td><td><?php echo $member['membership_type']; ?></td></tr>
                    <tr><td><strong>Status:</strong></td><td>
                        <span class="status-badge status-<?php echo strtolower($member['status']); ?>">
                            <?php echo $member['status']; ?>
                        </span>
                    </td></tr>
                </table>
            </div>

            <!-- Address Information -->
            <div class="content-card">
                <h3><i class="fas fa-home"></i> Address Information</h3>
                <table style="width: 100%;">
                    <tr><td><strong>Country:</strong></td><td><?php echo $member['country']; ?></td></tr>
                    <tr><td><strong>Province:</strong></td><td><?php echo $member['province']; ?></td></tr>
                    <tr><td><strong>City:</strong></td><td><?php echo $member['city']; ?></td></tr>
                    <tr><td><strong>Town:</strong></td><td><?php echo $member['town']; ?></td></tr>
                </table>
            </div>

            <!-- References -->
            <div class="content-card">
                <h3><i class="fas fa-users"></i> References</h3>
                <table style="width: 100%;">
                    <tr><td><strong>Reference 1:</strong></td><td><?php echo $member['reference1_name']; ?></td></tr>
                    <tr><td><strong>Contact:</strong></td><td><?php echo $member['reference1_contact']; ?></td></tr>
                    <tr><td><strong>Reference 2:</strong></td><td><?php echo $member['reference2_name']; ?></td></tr>
                    <tr><td><strong>Contact:</strong></td><td><?php echo $member['reference2_contact']; ?></td></tr>
                </table>
            </div>

            <!-- Account Information -->
            <div class="content-card">
                <h3><i class="fas fa-envelope"></i> Account Information</h3>
                <table style="width: 100%;">
                    <tr><td><strong>Email:</strong></td><td><?php echo $member['email']; ?></td></tr>
                    <tr><td><strong>Registered:</strong></td><td><?php echo date('M j, Y g:i A', strtotime($member['created_at'])); ?></td></tr>
                    <tr><td><strong>Last Updated:</strong></td><td><?php echo date('M j, Y g:i A', strtotime($member['updated_at'])); ?></td></tr>
                </table>
            </div>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <a href="edit_member.php?id=<?php echo $member['id']; ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Member
            </a>
            <a href="members.php" class="btn btn-primary">
                <i class="fas fa-list"></i> Back to List
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>