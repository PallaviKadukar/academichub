<?php
include 'db_connect.php';

$query = "SELECT af.id, f.faculty_name, s.subject_name 
          FROM assigned_faculty af
          JOIN faculty f ON af.faculty_id = f.faculty_id
          JOIN subjects s ON af.subject_id = s.subject_id";

$result = $conn->query($query);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'], // This is needed for deletion!
        'faculty_name' => $row['faculty_name'],
        'subject_name' => $row['subject_name']
    ];
}

echo json_encode($data);
?>
