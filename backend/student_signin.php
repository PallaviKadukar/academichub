<?php
session_start();
include __DIR__ . '/db_connect.php'; // ✅ Correct


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch student details from database
    $query = "SELECT * FROM students WHERE email='$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $student['password'])) {
            $_SESSION['student_id'] = $student['student_id'];
            $_SESSION['student_name'] = $student['name'];
            $_SESSION['student_email'] = $student['email'];
            $_SESSION['student_name'] = $row['name']; // Store name in session
            

            echo "<script>alert('✅ Login Successful! Redirecting...'); window.location.href='../student_dashboard.html';</script>";
        } else {
            echo "<script>alert('❌ Invalid Password!'); window.location.href='../studentsignin.html';</script>";
        }
    } else {
        echo "<script>alert('❌ No account found with this email!'); window.location.href='../studentsignin.html';</script>";
    }
}
?>