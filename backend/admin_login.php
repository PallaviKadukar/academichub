<?php
session_start();
include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE email='$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
         // Verify password (if stored as hash)
         if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['name'];

            header("Location: ../admin_dashboard.html");
        } else {
            echo "<script>alert('❌ Incorrect Password!'); window.location.href='../admin_login.html';</script>";
        }
    } else {
        echo "<script>alert('❌ Admin Not Found!'); window.location.href='../admin_login.html';</script>";
    }
}
?>
