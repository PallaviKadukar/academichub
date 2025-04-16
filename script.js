// Function to Open the Form
function openForm(formId) {
    document.getElementById(formId).classList.add("show");
}

// Function to Close the Form
function closeForm(formId) {
    document.getElementById(formId).classList.remove("show");
}

// Close the form when clicking outside the form
window.onclick = function(event) {
    let studentForm = document.getElementById("student-form");
    let facultyForm = document.getElementById("faculty-form");

    if (event.target === studentForm) {
        closeForm("student-form");
    }
    if (event.target === facultyForm) {
        closeForm("faculty-form");
    }
};

// Prevent closing when clicking inside the form
document.querySelectorAll(".form-container").forEach(form => {
    form.addEventListener("click", function(event) {
        event.stopPropagation();
    });
});

// Close the form when pressing the Escape key
document.addEventListener("keydown", function(event) {
    if (event.key === "Escape") {
        closeForm("student-form");
        closeForm("faculty-form");
    }
});
