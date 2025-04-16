document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const studentTable = document.querySelector("table"); // Get the table element
    const studentTableBody = document.getElementById("studentList"); // Get tbody

    // Search filter function
    searchInput.addEventListener("keyup", function () {
        const searchValue = searchInput.value.toLowerCase().trim();
        const rows = studentTableBody.querySelectorAll("tr"); // Get all table rows

        rows.forEach(row => {
            const nameCell = row.querySelector("td:first-child"); // First column (Student Name)
            if (nameCell) {
                const nameText = nameCell.textContent.trim().toLowerCase();
                row.style.display = nameText.includes(searchValue) ? "" : "none"; // Show/hide row
            }
        });
    });

    // Fetch students function
    function fetchStudents() {
        console.log("Table Data:", studentTableBody.innerHTML);
        const subjectId = new URLSearchParams(window.location.search).get("subject_id"); // Get subject_id from URL
        fetch(`backend/get_students.php?subject_id=${subjectId}`)
            .then(response => response.json())
            .then(data => {
                studentTableBody.innerHTML = "";
                data.forEach(student => {
                    const row = `
                        <tr>
                            <td>${student.name}</td>
                            <td>${student.roll_number}</td>
                            <td>Semester ${student.semester}</td>
                            <td>${student.attendance || 'N/A'}%</td>
                            <td>
                                <button class="btn btn-danger" onclick="removeStudent(${student.student_id})">Remove</button>
                            </td>
                        </tr>
                    `;
                    studentTableBody.innerHTML += row;
                });
            })
            .catch(error => console.error("Error fetching students:", error));
    }

    fetchStudents();
});
