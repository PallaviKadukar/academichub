<?php
// Include database connection
include __DIR__ . '/db_connect.php';

// Check if the connection is working
if (!$conn) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . mysqli_connect_error()]));
}

// Check if 'id' parameter is received
if (isset($_GET['id'])) {
    $subject_id = $_GET['id'];

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Step 1: Delete from faculty_subjects (to remove dependency)
        $deleteFacultySubjects = "DELETE FROM faculty_subjects WHERE subject_id = ?";
        $stmt1 = mysqli_prepare($conn, $deleteFacultySubjects);
        mysqli_stmt_bind_param($stmt1, "i", $subject_id);
        mysqli_stmt_execute($stmt1);

        // Step 2: Delete from subjects
        $deleteSubject = "DELETE FROM subjects WHERE subject_id = ?";
        $stmt2 = mysqli_prepare($conn, $deleteSubject);
        mysqli_stmt_bind_param($stmt2, "i", $subject_id);
        mysqli_stmt_execute($stmt2);

        // Commit transaction if successful
        mysqli_commit($conn);
        echo json_encode(["status" => "success", "message" => "Subject deleted successfully"]);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(["status" => "error", "message" => "SQL Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
