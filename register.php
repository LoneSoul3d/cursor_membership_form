<?php
include 'config/database.php';
include 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Registration - All Pakistan Ahle Hadees Federation</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>All Pakistan Ahle Hadees Federation</h1>
            <p>Membership Registration Form</p>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                Registration successful! Your member ID will be generated after verification.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <?php
                if (isset($_GET['message'])) {
                    echo htmlspecialchars(urldecode($_GET['message']));
                } else {
                    echo "There was an error processing your registration. Please try again.";
                }
                ?>
            </div>
        <?php endif; ?>

        <form class="registration-form" id="registrationForm" action="process_registration.php" method="POST">
            
            <!-- Personal Information Section -->
            <div class="form-section">
                <div class="section-title">Personal Information</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="firstName">First Name <span class="required">*</span></label>
                        <input type="text" id="firstName" name="firstName" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="lastName">Last Name <span class="required">*</span></label>
                        <input type="text" id="lastName" name="lastName" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="fatherName">Father Name <span class="required">*</span></label>
                        <input type="text" id="fatherName" name="fatherName" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="cnic">CNIC <span class="required">*</span></label>
                        <input type="text" id="cnic" name="cnic" placeholder="XXXXX-XXXXXXX-X" required maxlength="15">
                    </div>
                    
                    <div class="form-group">
                        <label for="mobile">Mobile <span class="required">*</span></label>
                        <input type="text" id="mobile" name="mobile" placeholder="03XXXXXXXXX" required maxlength="11">
                    </div>
                    
                    <div class="form-group">
                        <label for="gender">Gender <span class="required">*</span></label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="dob">Date of Birth <span class="required">*</span></label>
                        <input type="date" id="dob" name="dob" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="maritalStatus">Marital Status <span class="required">*</span></label>
                        <select id="maritalStatus" name="maritalStatus" required>
                            <option value="">Select Status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Widowed">Widowed</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Education & Occupation Section -->
            <div class="form-section">
                <div class="section-title">Education & Occupation</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="education">Education <span class="required">*</span></label>
                        <select id="education" name="education" required>
                            <option value="">Select Education</option>
                            <option value="Primary">Primary</option>
                            <option value="Matric">Matric</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Bachelor">Bachelor</option>
                            <option value="Master">Master</option>
                            <option value="PhD">PhD</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="occupation">Occupation <span class="required">*</span></label>
                        <select id="occupation" name="occupation" required>
                            <option value="">Loading occupations...</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Address Information Section -->
            <div class="form-section">
                <div class="section-title">Address Information</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="country">Country <span class="required">*</span></label>
                        <select id="country" name="country" required>
                            <option value="">Loading countries...</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="province">Province <span class="required">*</span></label>
                        <select id="province" name="province" required>
                            <option value="">Select Province</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="city">City <span class="required">*</span></label>
                        <select id="city" name="city" required>
                            <option value="">Select City</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="town">Town <span class="required">*</span></label>
                        <select id="town" name="town" required>
                            <option value="">Select Town</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Account Information Section -->
            <div class="form-section">
                <div class="section-title">Account Information</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password <span class="required">*</span></label>
                        <input type="password" id="password" name="password" required minlength="6">
                    </div>
                    
                    <div class="form-group">
                        
                          <label for="membershipType">Membership Type <span class="required">*</span></label>
                        <select id="membershipType" name="membershipType" required>
                            <option value="">Select Type</option>
                            <option value="Basic">Basic (Free)</option>
                            <option value="Privilege">Privilege (Rs. 1,000/Month)</option>
                            <option value="Elite">Elite (Rs. 5,000/Month)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- References Section -->
            <div class="form-section">
                <div class="section-title">References</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="reference1Name">Reference 1 Name <span class="required">*</span></label>
                        <input type="text" id="reference1Name" name="reference1Name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="reference1Contact">Reference 1 Contact <span class="required">*</span></label>
                        <input type="text" id="reference1Contact" name="reference1Contact" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="reference2Name">Reference 2 Name <span class="required">*</span></label>
                        <input type="text" id="reference2Name" name="reference2Name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="reference2Contact">Reference 2 Contact <span class="required">*</span></label>
                        <input type="text" id="reference2Contact" name="reference2Contact" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit">Submit Registration</button>
        </form>
    </div>

    <script src="js/script.js"></script>
</body>
</html>