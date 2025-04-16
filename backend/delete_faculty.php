<?php
include __DIR__ . "/db_connect.php";

if (isset($_GET['id'])) {
    $faculty_id = intval($_GET['id']); // Convert ID to integer (prevents SQL injection)

    // ✅ Step 1: Remove faculty from assigned subjects
    $delete_assignments = $conn->prepare("DELETE FROM assigned_faculty WHERE faculty_id = ?");
    $delete_assignments->bind_param("i", $faculty_id);
    $delete_assignments->execute();
    $delete_assignments->close();

    // ✅ Step 2: Delete faculty from database
    $stmt = $conn->prepare("DELETE FROM faculty WHERE faculty_id = ?");
    $stmt->bind_param("i", $faculty_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "✅ Faculty deleted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "❌ Failed to delete faculty."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "❌ Invalid request!"]);
}

$conn->close();
?>
