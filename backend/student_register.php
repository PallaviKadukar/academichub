<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $year = $_POST['year'];
    $semester = $_POST['semester'];

    echo "🔹 Registering student: $name, Email: $email, Semester: $semester <br>";

    // ✅ Check if email already exists
    $checkQuery = "SELECT student_id FROM students WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('❌ Email already registered!'); window.location.href='../studentsignup.html';</script>";
        exit();
    }

    // ✅ Insert student details using prepared statement
    $insertQuery = "INSERT INTO students (name, email, password, year, semester) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sssii", $name, $email, $password, $year, $semester);

    if ($stmt->execute()) {
        $student_id = $stmt->insert_id; // Get new student ID
        echo "✅ Student Registered! ID: $student_id <br>";

        // ✅ Auto-assign subjects for the chosen semester
        $subject_query = "SELECT subject_id FROM subjects WHERE semester = ?";
        $stmtSubjects = $conn->prepare($subject_query);
        $stmtSubjects->bind_param("i", $semester);
        $stmtSubjects->execute();
        $subjects = $stmtSubjects->get_result();

        if ($subjects->num_rows > 0) {
            while ($subject = $subjects->fetch_assoc()) {
                $subject_id = $subject['subject_id'];
                $insert_subject = "INSERT INTO student_subjects (student_id, subject_id) VALUES (?, ?)";
                $stmtAssign = $conn->prepare($insert_subject);
                $stmtAssign->bind_param("ii", $student_id, $subject_id);
                if ($stmtAssign->execute()) {
                    echo "✔️ Assigned Subject ID: $subject_id to Student ID: $student_id <br>";
                } else {
                    echo "❌ Error assigning subject: " . $conn->error . "<br>";
                }
            }
        } else {
            echo "⚠️ No subjects found for Semester $semester. Please ask the admin to add subjects.";
        }

        echo "<script>alert('✅ Registration Successful! Subjects Assigned.'); window.location.href='../studentsignin.html';</script>";
    } else {
        echo "❌ Error: " . $conn->error;
    }

    // ✅ Close connections
    $stmt->close();
    $conn->close();
}
?>
