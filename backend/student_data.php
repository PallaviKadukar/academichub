<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['student_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

include 'db_connect.php';

$student_id = $_SESSION['student_id'];
$query = "SELECT fullname, email FROM students WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Fetching subjects, marks, and attendance
$query = "SELECT subject_name, internal_marks, external_marks, practical_marks, attendance 
          FROM subjects 
          LEFT JOIN marks ON subjects.subject_id = marks.subject_id AND marks.student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$subject_result = $stmt->get_result();
$subjects = $subject_result->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "fullname" => $student['fullname'],
    "email" => $student['email'],
    "subjects" => $subjects
]);
?>
