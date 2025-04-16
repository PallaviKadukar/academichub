<?php
session_start();
header('Content-Type: application/json');

// Database connection
$host = "localhost";
$username = "root";
$password = "";  // Leave empty if there's no MySQL password in XAMPP
$dbname = "academichub";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare and execute the query
    $query = "SELECT faculty_id, faculty_name, password FROM faculty WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $db_password = $row['password'];

        // Verify hashed password
        if (password_verify($password, $db_password)) {
            // Store faculty_id and faculty_name in session
            $_SESSION['faculty_id'] = $row['faculty_id'];
            $_SESSION['faculty_name'] = $row['faculty_name'];

            echo json_encode(["status" => "success", "message" => "Login successful", "faculty_name" => $row['faculty_name']]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "This script requires a POST request"]);
}

$conn->close();
?>
