<?php
include '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

function sendError($message) {
    echo json_encode(['error' => $message]);
    exit;
}

function getConnection() {
    try {
        return getLocationDBConnection();
    } catch (Exception $e) {
        sendError('Database connection failed: ' . $e->getMessage());
    }
}

$type = $_GET['type'] ?? '';

try {
    switch ($type) {
        case 'countries':
            echo getCountries();
            break;
            
        case 'provinces':
            $countryId = $_GET['country_id'] ?? '';
            if (empty($countryId)) sendError('Country ID is required');
            echo getProvinces($countryId);
            break;
            
        case 'cities':
            $provinceId = $_GET['province_id'] ?? '';
            if (empty($provinceId)) sendError('Province ID is required');
            echo getCities($provinceId);
            break;
            
        case 'towns':
            $cityId = $_GET['city_id'] ?? '';
            if (empty($cityId)) sendError('City ID is required');
            echo getTowns($cityId);
            break;
            
        case 'occupations':
            echo getOccupations();
            break;
            
        case 'check_existing':
            echo checkExisting();
            break;
            
        default:
            sendError('Invalid request type');
    }
} catch (Exception $e) {
    sendError('Server error: ' . $e->getMessage());
}

function getCountries() {
    $conn = getConnection();
    $query = "SELECT id, name FROM countries ORDER BY name";
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception('Failed to fetch countries: ' . $conn->error);
    }
    
    $countries = [];
    while ($row = $result->fetch_assoc()) {
        $countries[] = $row;
    }
    
    return json_encode($countries);
}

function getProvinces($countryId) {
    $conn = getConnection();
    $query = "SELECT id, name FROM provinces WHERE country_id = ? ORDER BY name";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $countryId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $provinces = [];
    while ($row = $result->fetch_assoc()) {
        $provinces[] = $row;
    }
    
    return json_encode($provinces);
}

function getCities($provinceId) {
    $conn = getConnection();
    $query = "SELECT id, name FROM cities WHERE province_id = ? ORDER BY name";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $provinceId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cities = [];
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row;
    }
    
    return json_encode($cities);
}

function getTowns($cityId) {
    $conn = getConnection();
    $query = "SELECT id, name FROM towns WHERE city_id = ? ORDER BY name";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $cityId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $towns = [];
    while ($row = $result->fetch_assoc()) {
        $towns[] = $row;
    }
    
    return json_encode($towns);
}

function getOccupations() {
    $conn = getConnection();
    $query = "SELECT id, name FROM occupations ORDER BY name";
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception('Failed to fetch occupations: ' . $conn->error);
    }
    
    $occupations = [];
    while ($row = $result->fetch_assoc()) {
        $occupations[] = $row;
    }
    
    return json_encode($occupations);
}

function checkExisting() {
    $field = $_POST['field'] ?? '';
    $value = $_POST['value'] ?? '';
    
    if (empty($field) || empty($value)) {
        return json_encode(['exists' => false]);
    }
    
    $conn = getDBConnection();
    
    // Remove dashes from CNIC for comparison
    if ($field === 'cnic') {
        $value = str_replace('-', '', $value);
        $query = "SELECT id FROM members WHERE REPLACE(cnic, '-', '') = ?";
    } else {
        $query = "SELECT id FROM members WHERE $field = ?";
    }
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return json_encode(['exists' => $result->num_rows > 0]);
}
?>