function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

document.addEventListener("DOMContentLoaded", function () {
    const student_id = getQueryParam("student_id");
    const subject_id = getQueryParam("subject_id");

    if (!student_id || !subject_id) {
        alert("❌ Missing student or subject ID.");
        return;
    }

    document.getElementById("studentName").innerText = "Loading...";
    document.getElementById("studentNameCell").innerText = "Loading...";

    // Fetch student details
    fetch(`/AcademicHub/backend/get_student_details.php?student_id=${student_id}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert("❌ Error: " + data.error);
                return;
            }
            document.getElementById("studentName").innerText = data.name;
            document.getElementById("studentNameCell").innerText = data.name;

            // Fetch existing marks
            return fetch(`/AcademicHub/backend/get_marks.php?student_id=${student_id}&subject_id=${subject_id}`);
        })
        .then(response => response.json())
        .then(marks => {
            console.log("📢 Marks Fetched:", marks);  // ✅ Debug log

            document.getElementById("internals").value = marks.internals ?? '';  
            document.getElementById("externals").value = marks.externals ?? '';  
            document.getElementById("practicals").value = marks.practicals ?? '';  
        })
        .catch(error => console.error("❌ Error fetching marks:", error));

    // Attach event listener to button AFTER ensuring student_id and subject_id exist
    document.getElementById("saveMarks").addEventListener("click", function () {
        saveMarks(student_id, subject_id);
    });
});

function saveMarks(student_id, subject_id) {
    const internals = document.getElementById("internals").value.trim();
    const externals = document.getElementById("externals").value.trim();
    const practicals = document.getElementById("practicals").value.trim();

    // Convert empty fields to NULL explicitly
    const data = new URLSearchParams();
    data.append("student_id", student_id);
    data.append("subject_id", subject_id);
    data.append("internals", internals !== "" ? internals : "NULL");
    data.append("externals", externals !== "" ? externals : "NULL");
    data.append("practicals", practicals !== "" ? practicals : "NULL");

    fetch("/AcademicHub/backend/save_marks.php", { 
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: data.toString()
    })
    .then(response => response.text()) // Debug raw response
    .then(text => {
        console.log("📢 Raw Response:", text);  // ✅ Debug log
        return JSON.parse(text);
    })
    .then(data => {
        if (data.status === "success") {
            alert("✅ Marks saved successfully!");
            window.location.href = `manage_students.html?subject_id=${subject_id}`;
        } else {
            alert("❌ Error saving marks: " + data.message);
        }
    })
    .catch(error => console.error("❌ JSON Parse Error:", error));
}
