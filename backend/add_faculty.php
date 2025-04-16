<?php
// Include database connection
include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form inputs
    $name = trim($_POST['faculty_name']);
    $email = trim($_POST['faculty_email']);
     $password = trim($_POST['faculty_password']);

    // Validation
    if (empty($name) || empty($email) || empty($username) || empty($password)) {
        echo "❌ Fields cannot be empty!";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "❌ Invalid email format!";
        exit();
    }

    if (strlen($password) < 6) {
        echo "❌ Password must be at least 6 characters long!";
        exit();
    }

    // Encrypt password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT email FROM faculty WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "❌ Faculty with this email already exists!";
        exit();
    }
    $checkStmt->close();

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO faculty (faculty_name, email,  password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Faculty added successfully!'); window.location.href='../admin_dashboard.html';</script>";
    } else {
        echo "<script>alert('❌ Error: " . $conn->error . "'); window.location.href='add_faculty.html';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
