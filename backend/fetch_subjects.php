<?php
// Include database connection
include __DIR__ . '/db_connect.php';

// Check database connection
if (!$conn) {
    die(json_encode(["error" => "Database connection failed: " . mysqli_connect_error()]));
}

// Fetch subjects from database
$query = "SELECT subject_id, subject_name, semester FROM subjects";
$result = mysqli_query($conn, $query);

// Check if the query executed successfully
if (!$result) {
    die(json_encode(["error" => "Query failed: " . mysqli_error($conn)]));
}

// Fetch data into an array
$subjectData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $subjectData[] = $row;
}

// Return JSON response
echo json_encode($subjectData);
?>
