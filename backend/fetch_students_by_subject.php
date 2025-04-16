<?php
include __DIR__ . '/db_connect.php';

if (isset($_GET['subject_id'])) {
    $subject_id = $_GET['subject_id'];

    $query = "SELECT s.student_id, s.name, s.semester, 
                     COALESCE(a.attendance_percentage, 0) AS attendance_percentage 
              FROM students s
              LEFT JOIN attendance a ON s.student_id = a.student_id AND a.subject_id = ?
              WHERE s.student_id IN (SELECT student_id FROM student_subjects WHERE subject_id = ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $subject_id, $subject_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    echo json_encode($students);
}
?>
