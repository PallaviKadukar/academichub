<?php
session_start();
require 'db_connect.php';

$student_id = $_GET['student_id'] ?? null;
$subject_id = $_GET['subject_id'] ?? null;

if ($student_id && $subject_id) {
    // 📌 Faculty Request (enter_marks.html) - Fetch marks for a specific student & subject
    $query = $conn->prepare("
        SELECT internals, externals, practicals 
        FROM marks 
        WHERE student_id = ? AND subject_id = ?
    ");
    $query->bind_param("ii", $student_id, $subject_id);
    $query->execute();
    $result = $query->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "internals" => is_numeric($row["internals"]) ? (int)$row["internals"] : null,
            "externals" => is_numeric($row["externals"]) ? (int)$row["externals"] : null,
            "practicals" => is_numeric($row["practicals"]) ? (int)$row["practicals"] : null
        ]);
    } else {
        echo json_encode(["internals" => null, "externals" => null, "practicals" => null]);
    }
} else {
    // 📌 Student Dashboard Request (student_dashboard.html)
    if (!isset($_SESSION['student_id'])) {
        echo json_encode(["error" => "Student not logged in"]);
        exit;
    }

    $student_id = $_SESSION['student_id'];

    // Fetch student's semester
    $query = $conn->prepare("SELECT semester FROM students WHERE student_id = ?");
    $query->bind_param("i", $student_id);
    $query->execute();
    $result = $query->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        echo json_encode(["error" => "Student details not found"]);
        exit;
    }

    $semester = $student['semester'];

    // Fetch marks & attendance for student's semester
    $query = $conn->prepare("
        SELECT subjects.subject_name, 
               marks.internals, 
               marks.externals, 
               marks.practicals, 
               COALESCE(attendance.attendance_percentage, 0) AS attendance_percentage
        FROM subjects
        LEFT JOIN marks ON marks.subject_id = subjects.subject_id AND marks.student_id = ?
        LEFT JOIN attendance ON attendance.subject_id = subjects.subject_id AND attendance.student_id = ?
        WHERE subjects.semester = ?
    ");
    $query->bind_param("iii", $student_id, $student_id, $semester);
    $query->execute();
    $result = $query->get_result();

    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $subjects[] = [
            "subject_name" => $row["subject_name"],
            "internal_marks" => is_numeric($row["internals"]) ? (int)$row["internals"] : "Not Entered",
            "external_marks" => is_numeric($row["externals"]) ? (int)$row["externals"] : "Not Entered",
            "practical_marks" => is_numeric($row["practicals"]) ? (int)$row["practicals"] : "Not Entered",
            "attendance_percentage" => $row["attendance_percentage"] // ✅ Now attendance is included
        ];
    }

    echo json_encode(["subjects" => $subjects]);
}

$conn->close();
?>
