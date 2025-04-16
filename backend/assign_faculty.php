<?php
include __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faculty_id = $_POST['faculty_id'] ?? null;
    $subject_id = $_POST['subject_id'] ?? null;

    if (empty($faculty_id) || empty($subject_id)) {
        echo "Please select both faculty and subject.";
        exit();
    }

    // Check if faculty is already assigned to THIS subject (not any subject)
    $check_query = "SELECT * FROM assigned_faculty WHERE faculty_id = ? AND subject_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $faculty_id, $subject_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "This faculty is already assigned to this subject!";
    } else {
        // Insert new faculty-subject assignment
        $insert_query = "INSERT INTO assigned_faculty (faculty_id, subject_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ii", $faculty_id, $subject_id);
        
        if ($stmt->execute()) {
            echo "Faculty assigned successfully!";
        } else {
            echo "Error inserting data: " . $conn->error;
        }
    }
}
?>
