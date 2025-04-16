<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo json_encode([
    "session_status" => session_status(),
    "session_student_id" => $_SESSION['student_id'] ?? "Not Set"
]);
?>
