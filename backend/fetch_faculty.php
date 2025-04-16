<?php
include __DIR__ . '/db_connect.php';

if (!$conn) {
    die(json_encode(["error" => "Database connection failed: " . mysqli_connect_error()]));
}

$query = "SELECT faculty_id, faculty_name, email AS faculty_email FROM faculty";
$result = mysqli_query($conn, $query);

if (!$result) {
    die(json_encode(["error" => "Query failed: " . mysqli_error($conn)]));
}

$facultyData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $facultyData[] = [
        'faculty_id' => $row['faculty_id'],
        'faculty_name' => $row['faculty_name'],
        'faculty_email' => $row['faculty_email'] ?? 'No Email'
    ];
}

header('Content-Type: application/json'); // Ensure JSON output
echo json_encode($facultyData);
?>
