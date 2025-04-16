<?php

include 'db_connect.php';

$subject_id = $_GET['subject_id'];

$sql = "SELECT s.student_id, s.name, s.email 
        FROM students s
        JOIN enrollments e ON s.student_id = e.student_id
        WHERE e.subject_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode($students);
?>
