<?php
session_start();
if (!isset($_SESSION["faculty_id"])) {
    header("Location: facultysignin.html"); // Redirect if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Faculty Dashboard</span>
            <a href="backend/logout.php" class="btn btn-light">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h3>Welcome, <?php echo $_SESSION["faculty_name"]; ?>!</h3>
        
        <h5>Assigned Subjects</h5>
        <table class="table table-bordered mt-3">
            <thead class="table-primary">
                <tr>
                    <th>Subject</th>
                    <th>Semester</th>
                </tr>
            </thead>
            <tbody id="subjectList">
                <!-- Assigned subjects will be loaded here dynamically -->
            </tbody>
        </table>

        <script>
            function loadSubjects() {
                fetch('backend/fetch_assigned_subjects.php')
                    .then(response => response.json())
                    .then(data => {
                        let subjectRows = "";
                        data.forEach(sub => {
                            subjectRows += `<tr>
                                <td>${sub.subject_name}</td>
                                <td>Semester ${sub.semester}</td>
                            </tr>`;
                        });

                        document.getElementById("subjectList").innerHTML = subjectRows || "<tr><td colspan='2'>No subjects assigned.</td></tr>";
                    })
                    .catch(error => console.error("Error loading subjects:", error));
            }

            document.addEventListener("DOMContentLoaded", loadSubjects);
        </script>
    </div>
</body>
</html>
