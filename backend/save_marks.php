<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/db_connect.php';

$student_id = $_POST['student_id'] ?? null;
$subject_id = $_POST['subject_id'] ?? null;
$internals = isset($_POST['internals']) && $_POST['internals'] !== "" ? $_POST['internals'] : null;
$externals = isset($_POST['externals']) && $_POST['externals'] !== "" ? $_POST['externals'] : null;
$practicals = isset($_POST['practicals']) && $_POST['practicals'] !== "" ? $_POST['practicals'] : null;

if (!$student_id || !$subject_id) {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
    exit;
}

try {
    // Log received data for debugging
    file_put_contents("log.txt", print_r($_POST, true), FILE_APPEND);

    // Check if the record exists
    $query = $conn->prepare("SELECT * FROM marks WHERE student_id = ? AND subject_id = ?");
    $query->bind_param("ii", $student_id, $subject_id);
    $query->execute();
    $result = $query->get_result();
    $exists = $result->fetch_assoc();

    if ($exists) {
        // Update only the fields that were provided
        $update_query = "UPDATE marks SET ";
        $update_values = [];
        $types = "";

        if ($internals !== null) { $update_query .= "internals = ?, "; $update_values[] = $internals; $types .= "i"; }
        if ($externals !== null) { $update_query .= "externals = ?, "; $update_values[] = $externals; $types .= "i"; }
        if ($practicals !== null) { 
            $update_query .= "practicals = ?, "; 
            $update_values[] = $practicals; 
            $types .= "i"; 
        } else { 
            $update_query .= "practicals = NULL, ";  // ✅ Ensure NULL values are updated
        }

        // Remove last comma and add WHERE clause
        $update_query = rtrim($update_query, ", ") . " WHERE student_id = ? AND subject_id = ?";
        $update_values[] = $student_id;
        $update_values[] = $subject_id;
        $types .= "ii";

        $stmt = $conn->prepare($update_query);
        if (!empty($update_values)) {
            $stmt->bind_param($types, ...$update_values);
        }
        $stmt->execute();
    } else {
        // Insert new record
        $stmt = $conn->prepare("INSERT INTO marks (student_id, subject_id, internals, externals, practicals) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiii", $student_id, $subject_id, $internals, $externals, $practicals);
        $stmt->execute();
    }

    echo json_encode(["status" => "success"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
