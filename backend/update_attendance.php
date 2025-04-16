<?php
include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"] ?? null;
    $subject_id = $_POST["subject_id"] ?? null;
    $attendance = $_POST["attendance"] ?? null;

    if ($student_id && $subject_id && $attendance !== null) {
        $total_classes = 100; // Assume a fixed number of total classes for now
        $classes_attended = round(($attendance / 100) * $total_classes);

        // Check if record exists
        $check_query = "SELECT * FROM attendance WHERE student_id = ? AND subject_id = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("ii", $student_id, $subject_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing record
            $update_query = "UPDATE attendance SET classes_attended = ?, total_classes = ?, attendance_percentage = ? WHERE student_id = ? AND subject_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("iiiii", $classes_attended, $total_classes, $attendance, $student_id, $subject_id);
        } else {
            // Insert new record
            $insert_query = "INSERT INTO attendance (student_id, subject_id, total_classes, classes_attended, attendance_percentage) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("iiiii", $student_id, $subject_id, $total_classes, $classes_attended, $attendance);
        }

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Attendance updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input data"]);
    }
}
?>
