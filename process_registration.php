<?php
include 'config/database.php';
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize all input data
    $firstName = sanitizeInput($_POST['firstName']);
    $lastName = sanitizeInput($_POST['lastName']);
    $fatherName = sanitizeInput($_POST['fatherName']);
    $cnic = sanitizeInput($_POST['cnic']);
    $mobile = sanitizeInput($_POST['mobile']);
    $gender = sanitizeInput($_POST['gender']);
    $dob = sanitizeInput($_POST['dob']);
    $maritalStatus = sanitizeInput($_POST['maritalStatus']);
    $education = sanitizeInput($_POST['education']);
    $occupation = sanitizeInput($_POST['occupation']);
    $country = sanitizeInput($_POST['country']);
    $province = sanitizeInput($_POST['province']);
    $city = sanitizeInput($_POST['city']);
    $town = sanitizeInput($_POST['town']);
    $email = sanitizeInput($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $membershipType = sanitizeInput($_POST['membershipType']);
    $reference1Name = sanitizeInput($_POST['reference1Name']);
    $reference1Contact = sanitizeInput($_POST['reference1Contact']);
    $reference2Name = sanitizeInput($_POST['reference2Name']);
    $reference2Contact = sanitizeInput($_POST['reference2Contact']);

    // Validate inputs
    $errors = [];

    if (!validateCNIC($cnic)) {
        $errors[] = "Invalid CNIC format";
    }

    if (!validateMobile($mobile)) {
        $errors[] = "Invalid mobile number format";
    }

    if (!validateEmail($email)) {
        $errors[] = "Invalid email format";
    }

    // Check if email, CNIC or mobile already exists
    $conn = getDBConnection();
    
    // Check CNIC (remove dashes for comparison)
    $cnicClean = str_replace('-', '', $cnic);
    $checkQuery = "SELECT id FROM members WHERE email = ? OR REPLACE(cnic, '-', '') = ? OR mobile = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("sss", $email, $cnicClean, $mobile);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Find which field caused the duplicate
        $checkIndividualQuery = "SELECT 
            (SELECT id FROM members WHERE email = ?) as email_exists,
            (SELECT id FROM members WHERE REPLACE(cnic, '-', '') = ?) as cnic_exists,
            (SELECT id FROM members WHERE mobile = ?) as mobile_exists";
        
        $stmt2 = $conn->prepare($checkIndividualQuery);
        $stmt2->bind_param("sss", $email, $cnicClean, $mobile);
        $stmt2->execute();
        $individualResult = $stmt2->get_result();
        $individualRow = $individualResult->fetch_assoc();
        
        if ($individualRow['email_exists']) {
            $errors[] = "Email already registered";
        }
        if ($individualRow['cnic_exists']) {
            $errors[] = "CNIC already registered";
        }
        if ($individualRow['mobile_exists']) {
            $errors[] = "Mobile number already registered";
        }
    }

    if (empty($errors)) {
        // Get location names from location database
        $locationConn = getLocationDBConnection();
        
        // Get location names
        $countryName = getLocationName($locationConn, 'countries', $country);
        $provinceName = getLocationName($locationConn, 'provinces', $province);
        $cityName = getLocationName($locationConn, 'cities', $city);
        $townName = getLocationName($locationConn, 'towns', $town);
        
        // Generate member ID
        $memberID = generateMemberID($conn);
        
        // FIXED: Added 'status' column to match your table structure
        $query = "INSERT INTO members (
            member_id, first_name, last_name, father_name, cnic, mobile, gender, 
            dob, marital_status, education, occupation, country, province, city, 
            town, email, password, membership_type, reference1_name, reference1_contact, 
            reference2_name, reference2_contact, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssssssssssssssssss", 
            $memberID, $firstName, $lastName, $fatherName, $cnic, $mobile, $gender,
            $dob, $maritalStatus, $education, $occupation, $countryName, $provinceName, $cityName,
            $townName, $email, $password, $membershipType, $reference1Name, $reference1Contact,
            $reference2Name, $reference2Contact
        );

        if ($stmt->execute()) {
            header('Location: register.php?success=1');
            exit();
        } else {
            header('Location: register.php?error=1&message=' . urlencode('Database error: ' . $stmt->error));
            exit();
        }
    } else {
        // Redirect back with error messages
        $errorMessage = implode(', ', $errors);
        header('Location: register.php?error=1&message=' . urlencode($errorMessage));
        exit();
    }
} else {
    header('Location: register.php');
    exit();
}

function getLocationName($conn, $table, $id) {
    $query = "SELECT name FROM $table WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return $row['name'];
    }
    
    return '';
}
?>