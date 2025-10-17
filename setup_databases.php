<?php
// Database configuration
$host = 'localhost';
$username = 'shaikh3d';
$password = 'salamshaikh';

// Create connection
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create databases
$databases = ['membership_db', 'location_db'];

foreach ($databases as $db) {
    if ($conn->query("CREATE DATABASE IF NOT EXISTS $db") === TRUE) {
        echo "Database '$db' created successfully<br>";
    } else {
        echo "Error creating database '$db': " . $conn->error . "<br>";
    }
}

// Select location database
$conn->select_db('location_db');

// Disable foreign key checks temporarily
$conn->query("SET FOREIGN_KEY_CHECKS = 0");

// Drop tables in correct order (child tables first)
$tables = ['towns', 'cities', 'provinces', 'countries', 'occupations'];
foreach ($tables as $table) {
    if ($conn->query("DROP TABLE IF EXISTS $table") === TRUE) {
        echo "Dropped table '$table'<br>";
    } else {
        echo "Error dropping table '$table': " . $conn->error . "<br>";
    }
}

// Create countries table
$conn->query("CREATE TABLE countries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    code VARCHAR(3)
)");
echo "Created countries table<br>";

// Create provinces table
$conn->query("CREATE TABLE provinces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    country_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE
)");
echo "Created provinces table<br>";

// Create cities table
$conn->query("CREATE TABLE cities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    province_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    FOREIGN KEY (province_id) REFERENCES provinces(id) ON DELETE CASCADE
)");
echo "Created cities table<br>";

// Create towns table
$conn->query("CREATE TABLE towns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE
)");
echo "Created towns table<br>";

// Create occupations table
$conn->query("CREATE TABLE occupations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    category VARCHAR(50)
)");
echo "Created occupations table<br>";

// Re-enable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

// Insert sample data
// Countries
$conn->query("INSERT INTO countries (name, code) VALUES 
    ('Pakistan', 'PK'),
    ('India', 'IN'),
    ('United States', 'US')");
echo "Inserted countries data<br>";

// Get Pakistan ID
$result = $conn->query("SELECT id FROM countries WHERE name = 'Pakistan'");
$pakistan = $result->fetch_assoc();
$pakistan_id = $pakistan['id'];

// Pakistan Provinces
$conn->query("INSERT INTO provinces (country_id, name) VALUES 
    ($pakistan_id, 'Sindh'),
    ($pakistan_id, 'Punjab'),
    ($pakistan_id, 'Khyber Pakhtunkhwa'),
    ($pakistan_id, 'Balochistan'),
    ($pakistan_id, 'Islamabad Capital Territory'),
    ($pakistan_id, 'Gilgit-Baltistan'),
    ($pakistan_id, 'Azad Jammu & Kashmir')");
echo "Inserted provinces data<br>";

// Get Sindh ID
$result = $conn->query("SELECT id FROM provinces WHERE name = 'Sindh'");
$sindh = $result->fetch_assoc();
$sindh_id = $sindh['id'];

// Get Punjab ID
$result = $conn->query("SELECT id FROM provinces WHERE name = 'Punjab'");
$punjab = $result->fetch_assoc();
$punjab_id = $punjab['id'];

// Sindh Cities
$conn->query("INSERT INTO cities (province_id, name) VALUES 
    ($sindh_id, 'Karachi'),
    ($sindh_id, 'Hyderabad'),
    ($sindh_id, 'Sukkur'),
    ($sindh_id, 'Larkana'),
    ($sindh_id, 'Mirpur Khas'),
    ($sindh_id, 'Nawabshah'),
    ($sindh_id, 'Jacobabad')");
echo "Inserted Sindh cities data<br>";

// Punjab Cities
$conn->query("INSERT INTO cities (province_id, name) VALUES 
    ($punjab_id, 'Lahore'),
    ($punjab_id, 'Faisalabad'),
    ($punjab_id, 'Rawalpindi'),
    ($punjab_id, 'Multan'),
    ($punjab_id, 'Gujranwala'),
    ($punjab_id, 'Sargodha'),
    ($punjab_id, 'Bahawalpur')");
echo "Inserted Punjab cities data<br>";

// Get Karachi ID
$result = $conn->query("SELECT id FROM cities WHERE name = 'Karachi'");
$karachi = $result->fetch_assoc();
$karachi_id = $karachi['id'];

// Get Lahore ID
$result = $conn->query("SELECT id FROM cities WHERE name = 'Lahore'");
$lahore = $result->fetch_assoc();
$lahore_id = $lahore['id'];

// Karachi Towns
$conn->query("INSERT INTO towns (city_id, name) VALUES 
    ($karachi_id, 'Gulshan-e-Iqbal'),
    ($karachi_id, 'Clifton'),
    ($karachi_id, 'North Nazimabad'),
    ($karachi_id, 'Gulistan-e-Johar'),
    ($karachi_id, 'Malir'),
    ($karachi_id, 'Korangi'),
    ($karachi_id, 'Saddar'),
    ($karachi_id, 'Defence'),
    ($karachi_id, 'Bahadurabad'),
    ($karachi_id, 'PECHS'),
    ($karachi_id, 'Lyari'),
    ($karachi_id, 'Orangi Town'),
    ($karachi_id, 'Landhi'),
    ($karachi_id, 'Surjani Town'),
    ($karachi_id, 'Gulshan-e-Maymar')");
echo "Inserted Karachi towns data<br>";

// Lahore Towns
$conn->query("INSERT INTO towns (city_id, name) VALUES 
    ($lahore_id, 'Gulberg'),
    ($lahore_id, 'Defence'),
    ($lahore_id, 'Model Town'),
    ($lahore_id, 'Cantt'),
    ($lahore_id, 'Iqbal Town'),
    ($lahore_id, 'Sammanabad'),
    ($lahore_id, 'Garden Town'),
    ($lahore_id, 'Johar Town'),
    ($lahore_id, 'Faisal Town'),
    ($lahore_id, 'Wapda Town')");
echo "Inserted Lahore towns data<br>";

// Insert occupations
$conn->query("INSERT INTO occupations (name, category) VALUES 
    ('Accountant', 'Professional'),
    ('Actor', 'Entertainment'),
    ('Architect', 'Professional'),
    ('Artist', 'Creative'),
    ('Banker', 'Finance'),
    ('Business Owner', 'Business'),
    ('Chef', 'Hospitality'),
    ('Civil Engineer', 'Engineering'),
    ('Civil Servant', 'Government'),
    ('Computer Programmer', 'IT'),
    ('Doctor', 'Medical'),
    ('Driver', 'Transportation'),
    ('Electrician', 'Technical'),
    ('Engineer', 'Engineering'),
    ('Farmer', 'Agriculture'),
    ('Graphic Designer', 'Creative'),
    ('Housewife', 'Homemaker'),
    ('Journalist', 'Media'),
    ('Lawyer', 'Professional'),
    ('Lecturer', 'Education'),
    ('Manager', 'Management'),
    ('Marketing Executive', 'Marketing'),
    ('Mechanic', 'Technical'),
    ('Nurse', 'Medical'),
    ('Pharmacist', 'Medical'),
    ('Pilot', 'Aviation'),
    ('Police Officer', 'Government'),
    ('Professor', 'Education'),
    ('Receptionist', 'Administrative'),
    ('Sales Executive', 'Sales'),
    ('School Teacher', 'Education'),
    ('Security Guard', 'Security'),
    ('Software Developer', 'IT'),
    ('Student', 'Education'),
    ('Tailor', 'Creative'),
    ('Taxi Driver', 'Transportation'),
    ('Technician', 'Technical'),
    ('Waiter', 'Hospitality'),
    ('Web Developer', 'IT'),
    ('Other', 'Other')");
echo "Inserted occupations data<br>";

// Create members table in membership_db
$conn->select_db('membership_db');

$conn->query("DROP TABLE IF EXISTS members");
$conn->query("CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    father_name VARCHAR(100) NOT NULL,
    cnic VARCHAR(15) UNIQUE NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    dob DATE NOT NULL,
    marital_status ENUM('Single', 'Married', 'Divorced', 'Widowed') NOT NULL,
    education ENUM('Primary', 'Matric', 'Intermediate', 'Bachelor', 'Master', 'PhD', 'Other') NOT NULL,
    occupation VARCHAR(100) NOT NULL,
    country VARCHAR(50) NOT NULL,
    province VARCHAR(50) NOT NULL,
    city VARCHAR(50) NOT NULL,
    town VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    membership_type ENUM('Regular', 'Student', 'Senior', 'Life') NOT NULL,
    reference1_name VARCHAR(100) NOT NULL,
    reference1_contact VARCHAR(15) NOT NULL,
    reference2_name VARCHAR(100) NOT NULL,
    reference2_contact VARCHAR(15) NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");
echo "Members table created successfully!<br>";

// Add indexes for better performance
$conn->query("CREATE INDEX idx_cnic ON members(cnic)");
$conn->query("CREATE INDEX idx_email ON members(email)");
$conn->query("CREATE INDEX idx_mobile ON members(mobile)");
$conn->query("CREATE INDEX idx_status ON members(status)");
echo "Indexes created successfully!<br>";

echo "<br><strong>Database setup completed successfully!</strong><br>";
echo "You can now access the registration form at: register.php";

$conn->close();
?>