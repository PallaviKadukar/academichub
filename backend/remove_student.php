<?php
include 'db_connect.php';

$student_id = $_GET['student_id'];
$subject_id = $_GET['subject_id'];

$sql = "DELETE FROM enrollments WHERE student_id = ? AND subject_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $subject_id);

if ($stmt->execute()) {
    echo "Student removed successfully.";
} else {
    echo "Error removing student.";
}
?>
