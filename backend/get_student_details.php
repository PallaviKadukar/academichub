<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

include __DIR__ . '/db_connect.php';

$student_id = null;

// 1️⃣ Student Dashboard: Use SESSION
if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];
}

// 2️⃣ Enter Marks Page: Use GET parameter (if available)
if (isset($_GET['student_id']) && empty($student_id)) {
    $student_id = intval($_GET['student_id']);
}

// 3️⃣ If still no student_id, return an error
if (!$student_id) {
    echo json_encode(["error" => "Student ID missing"]);
    exit();
}

// 4️⃣ Fetch student details
$query = "SELECT name FROM students WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(["name" => $row["name"]]); // ✅ Works for both pages
} else {
    echo json_encode(["error" => "Student not found"]);
}

$stmt->close();
$conn->close();
?>
