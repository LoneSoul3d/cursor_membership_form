<?php
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateCNIC($cnic) {
    return preg_match('/^\d{5}-\d{7}-\d{1}$/', $cnic);
}

function validateMobile($mobile) {
    return preg_match('/^03\d{9}$/', $mobile);
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function generateMemberID($conn) {
    $year = date('Y');
    $query = "SELECT COUNT(*) as total FROM members WHERE YEAR(created_at) = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $year);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $sequence = $row['total'] + 1;
    return "APHDF-" . $year . "-" . str_pad($sequence, 5, '0', STR_PAD_LEFT);
}
?>