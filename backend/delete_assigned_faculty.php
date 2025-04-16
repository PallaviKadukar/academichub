<?php
include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    if (!empty($id)) {
        $query = "DELETE FROM assigned_faculty WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Assignment deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete assignment"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid ID"]);
    }
}
?>
