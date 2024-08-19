$(document).ready(function(){
    // Check if the user is logged in
    $.ajax({
        type: "GET",
        url: "check_login.php",
        success: function(response){
            if(response === 'not_logged_in'){
                window.location.href = "login.html"; // Redirect to login page
            }
            else {
                // Your condition to make the body visible
                var condition = true; // Change this to your specific condition

                // Get the body element
                var body = document.querySelector('body');

                // Check the condition
                if (condition) {
                    body.style.display = "block"; // Or any other display style you want
                } else {
                    body.style.display = "none";
                }
            }
        }
    });
});

// Add event listener to the "Save" button in the change grade system popup
const saveButton = document.querySelector('#popup-box2 .saveBtn');
saveButton.addEventListener('click', function() {
  // Save grades and grade points to the SGPA calculator form
  saveGrades();

  // Close the change grade system popup after saving grades
  closePopUpBox2();
});

// Function to save grades and grade points to the SGPA calculator form
function saveGrades() {
    // Get the grade and grade point inputs from the "Change Grade System" popup box
    const newGradeInputs = document.querySelectorAll('#popup-box2 input[name="grade[]"]');
    const newPointInputs = document.querySelectorAll('#popup-box2 input[name="point[]"]');

    // Remove existing options from the SGPA grade select input
    const sgpaGradeSelect = document.getElementById('sgpaGradeSelect');
    const existingOptions = sgpaGradeSelect.querySelectorAll('option:not(:first-child)');
    existingOptions.forEach(option => option.remove());

    // Loop through each grade input and update the grade select options
    newGradeInputs.forEach((gradeInput, index) => {
        const grade = gradeInput.value;
        const point = newPointInputs[index].value;

        if (grade && point) {
            // Create a new option element for the grade select input in the SGPA Calculator form
            const newOption = document.createElement('option');
            newOption.value = point;
            newOption.textContent = grade;
            sgpaGradeSelect.appendChild(newOption);
        }
    });

    // Update existing rows with the new grades
    updateExistingRows();
}

function updateExistingRows() {
    // Get all existing course rows
    const courseRows = document.querySelectorAll('.courseRow');

    // Get the updated grade options from the SGPA grade select input
    const sgpaGradeSelect = document.getElementById('sgpaGradeSelect');
    const gradeOptions = sgpaGradeSelect.querySelectorAll('option');

    // Iterate over each course row
    courseRows.forEach(courseRow => {
        // Get the grade select input element in the current row
        const gradeSelect = courseRow.querySelector('select[name="grade[]"]');

        // Remove existing options from the grade select input
        const existingOptions = gradeSelect.querySelectorAll('option');
        existingOptions.forEach(option => option.remove());

        // Add the updated grade options to the grade select input in the current row
        gradeOptions.forEach(option => {
            const newOption = document.createElement('option');
            newOption.value = option.value;
            newOption.textContent = option.textContent;
            gradeSelect.appendChild(newOption);
        });
    });
}

// Function to add a new grade row
function addGradeRow() {
    // Create a new grade row element
    const newGradeRow = document.createElement('div');
    newGradeRow.classList.add('gradeRow');

    // Create input elements for Grade point and grade
    const gradeInput = document.createElement('input');
    gradeInput.type = 'text';
    gradeInput.name = 'grade[]';
    gradeInput.placeholder = 'Grade';
    gradeInput.required = true;

    const pointInput = document.createElement('input');
    pointInput.type = 'text';
    pointInput.name = 'point[]';
    pointInput.placeholder = 'Grade Point';
    pointInput.required = true;

    // Create delete button
    const deleteButton = document.createElement('i');
    deleteButton.classList.add('bx', 'bx-trash', 'delete-grade');
    deleteButton.title = 'Delete';
    deleteButton.addEventListener('click', function() {
        const gradeRow = deleteButton.closest('.gradeRow');
        gradeRow.remove(); // Remove the grade row containing the delete button
    });

    // Append inputs and delete button to the new grade row
    newGradeRow.appendChild(gradeInput);
    newGradeRow.appendChild(pointInput);
    newGradeRow.appendChild(deleteButton);

    // Append the new grade row before the buttons container
    const buttonsContainer = document.querySelector('.popup__content .buttons-container');
    buttonsContainer.parentNode.insertBefore(newGradeRow, buttonsContainer);
}

// Event listener for the "Add Grade" button
const addGradeButton = document.querySelector('.add-g');
addGradeButton.addEventListener('click', addGradeRow);

// Event listener for delete buttons (including the one in the first row)
document.querySelectorAll('.delete-grade').forEach(button => {
    button.addEventListener('click', function() {
        const gradeRow = button.closest('.gradeRow');
        gradeRow.remove(); // Remove the grade row containing the delete button
    });
});