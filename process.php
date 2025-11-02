<?php

$db_host = 'sql112.infinityfree.com';
$db_user = 'if0_40313246';
$db_pass = 'portfolio123';  // The password you set when creating the hosting account
$db_name = 'ig_porto';
// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize and validate input data
    $fullName = htmlspecialchars(trim($_POST['fullName']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $dob = htmlspecialchars(trim($_POST['dob']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $course = htmlspecialchars(trim($_POST['course']));
    $address = htmlspecialchars(trim($_POST['address']));
    
    // Validation
    $errors = [];
    
    if (empty($fullName) || !preg_match("/^[a-zA-Z\s]{2,50}$/", $fullName)) {
        $errors[] = "Invalid name";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email";
    }
    
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Invalid phone number";
    }
    
    if (empty($dob)) {
        $errors[] = "Date of birth is required";
    }
    
    if (empty($gender)) {
        $errors[] = "Gender is required";
    }
    
    if (empty($course)) {
        $errors[] = "Course is required";
    }
    
    if (empty($address)) {
        $errors[] = "Address is required";
    }
    
    // If there are errors, show them
    if (!empty($errors)) {
        echo "<h3>Errors:</h3><ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
        echo '<a href="index.html">Go Back</a>';
        exit();
    }
    
    // Calculate age from date of birth
    $dobDate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($dobDate)->y;
    
    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO students (full_name, email, phone, dob, gender, course, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $fullName, $email, $phone, $dob, $gender, $course, $address);
    
    // Execute the statement
    if ($stmt->execute()) {
        $registration_id = $stmt->insert_id;
        $success = true;
        $message = "Registration successful! Your Registration ID is: #" . $registration_id;
    } else {
        $success = false;
        $message = "Error: " . $stmt->error;
    }
    
    // Close statement
    $stmt->close();
    
} else {
    // If accessed directly without POST, redirect to form
    header("Location: index.html");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="success-container">
            <?php if ($success): ?>
            <div class="success-header">
                <div class="success-icon">✓</div>
                <h1>Registration Successful!</h1>
                <p><?php echo $message; ?></p>
            </div>
            
            <h2 style="color: #333; margin-bottom: 20px;">Registration Details</h2>
            
            <table class="info-table">
                <tr>
                    <th>Registration ID</th>
                    <td><strong style="color: #667eea; font-size: 20px;">#<?php echo $registration_id; ?></strong></td>
                </tr>
                <tr>
                    <th>Full Name</th>
                    <td><?php echo $fullName; ?></td>
                </tr>
                <tr>
                    <th>Email Address</th>
                    <td><?php echo $email; ?></td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td><?php echo $phone; ?></td>
                </tr>
                <tr>
                    <th>Date of Birth</th>
                    <td><?php echo date('F d, Y', strtotime($dob)); ?></td>
                </tr>
                <tr>
                    <th>Age</th>
                    <td><?php echo $age; ?> years</td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td><?php echo $gender; ?></td>
                </tr>
                <tr>
                    <th>Course</th>
                    <td><?php echo $course; ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo nl2br($address); ?></td>
                </tr>
                <tr>
                    <th>Submission Time</th>
                    <td><?php echo date('Y-m-d H:i:s'); ?></td>
                </tr>
            </table>
            
            <div class="button-group">
                <a href="index.html" class="back-btn">Submit Another Registration</a>
            </div>
            <?php else: ?>
            <div class="success-header">
                <div class="success-icon" style="color: #e74c3c;">✗</div>
                <h1>Registration Failed</h1>
                <p style="color: #e74c3c;"><?php echo $message; ?></p>
            </div>
            <div style="text-align: center; margin-top: 30px;">
                <a href="index.html" class="back-btn">Try Again</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
