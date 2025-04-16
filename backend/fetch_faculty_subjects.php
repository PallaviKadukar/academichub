<?php
session_start();
include 'db_connect.php';

// Check if faculty_id is set in the session
if (!isset($_SESSION['faculty_id'])) {
    echo json_encode(["error" => "Faculty not logged in"]);
    exit();
}

$faculty_id = $_SESSION['faculty_id'];

// Fetch assigned subjects for the logged-in faculty member
$query = "
    SELECT s.subject_id, s.subject_name, s.semester 
    FROM assigned_faculty af
    JOIN subjects s ON af.subject_id = s.subject_id
    WHERE af.faculty_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();

$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}

// Return JSON response
echo json_encode($subjects);

$stmt->close();
$conn->close();
?>
