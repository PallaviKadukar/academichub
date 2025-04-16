<?php
include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_name = $_POST['subject_name'];
    $semester = $_POST['semester'];

    // Debug: Print received data
    echo "Subject Name: $subject_name, Semester: $semester <br>";

    $query = "INSERT INTO subjects (subject_name, semester) VALUES ('$subject_name', '$semester')";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('✅ Subject Added Successfully!'); window.location.href='../admin_dashboard.html';</script>";
    }else {
        echo "❌ Error: " . $conn->error;
    }
}
?>
