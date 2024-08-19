<?php
session_start();
include 'config.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decode the JSON data sent from JavaScript
    $data = json_decode(file_get_contents("php://input"), true);

    // Extract SGPA, institution, program, and semester data
    $sgpa = $data['sgpa'];
    $institution = $data['institution'];
    $program = $data['program'];
    $semester = $data['semester'];

    // You should perform validation and sanitization of data here

    // Prepare and execute the SQL query to insert SGPA data into the database
    $stmt = $conn->prepare("INSERT INTO sgpa_data (user_id, sgpa, institution, program, semester) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idsss", $userId, $sgpa, $institution, $program, $semester);
    
    // Replace $userId with the actual user ID
    $userId = $_SESSION['user_id']; // Assuming you store user ID in a session

    if ($stmt->execute()) {
        // SGPA data successfully saved
        echo "SGPA data saved successfully!";
    } else {
        // Error occurred while saving SGPA data
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle invalid request method
    echo "Invalid request method!";
}
?>