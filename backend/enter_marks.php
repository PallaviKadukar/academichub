<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['faculty_id'])) {
    echo json_encode(["error" => "Faculty not logged in"]);
    exit;
}

$faculty_id = $_SESSION['faculty_id'];

// Fetch students and marks for subjects assigned to this faculty
$query = $conn->prepare("
    SELECT students.student_id, students.name, subjects.subject_name, marks.internals, marks.externals, marks.practicals
    FROM students
    JOIN marks ON students.student_id = marks.student_id
    JOIN subjects ON marks.subject_id = subjects.subject_id
    WHERE subjects.faculty_id = ?
");
$query->bind_param("i", $faculty_id);
$query->execute();
$result = $query->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = [
        "student_id" => $row["student_id"],
        "name" => $row["name"],
        "subject_name" => $row["subject_name"],
        "internal_marks" => $row["internals"] ?? "Not Entered",
        "external_marks" => $row["externals"] ?? "Not Entered",
        "practical_marks" => $row["practicals"] ?? "Not Entered"
    ];
}

if (empty($students)) {
    echo json_encode(["error" => "No students or marks found for this faculty"]);
} else {
    echo json_encode(["students" => $students]);
}
?>
