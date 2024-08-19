<?php
session_start();
include 'config.php'; // Assuming config.php contains database connection code

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If user is not logged in, redirect to login page
    header("Location: login.html");
    exit;
}

// Check if the item type, name, and institution name are provided in the POST request
if(isset($_POST['item_type']) && isset($_POST['item_name']) && isset($_POST['institution_name'])) {
    // Get the item type, name, and institution name from the POST data
    $itemType = $_POST['item_type'];
    $itemName = $_POST['item_name'];
    $institutionName = $_POST['institution_name'];
    
    // Get the user ID of the current user
    $userId = $_SESSION['user_id'];

    // Prepare and execute the SQL statement to delete the item data
    $sql = "DELETE FROM sgpa_data WHERE ";
    if ($itemType === 'institution') {
        $sql .= "institution = ? AND ";
    } elseif ($itemType === 'program') {
        // Include institution name in the WHERE clause for programs
        $sql .= "program = ? AND institution = ? AND ";
    } else {
        // For semester, extract the program name and semester number from the item name
        // Format: "Semester {semesterNumber} of {programName}"
        preg_match('/Semester (\d+) of program (.+)/', $itemName, $matches);
        $semesterNumber = $matches[1];
        $programName = $matches[2];
        $sql .= "program = ? AND semester = ? AND institution = ? AND ";
    }
    $sql .= "user_id = ?";
    
    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters based on the item type
    if ($itemType === 'institution') {
        $stmt->bind_param("si", $itemName, $userId);
    } elseif ($itemType === 'program') {
        $stmt->bind_param("ssi", $itemName, $institutionName, $userId);
    } else {
        $stmt->bind_param("sssi", $programName, $semesterNumber, $institutionName, $userId);
    }

    // Execute the SQL statement
    if ($stmt->execute()) {
        // Deletion successful
        echo "Item data deleted successfully.";
    } else {
        // Error occurred during deletion
        echo "Error deleting item data: " . $conn->error;
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // If item type, name, or institution name is not provided in the POST request
    echo "Item type, name, or institution name not provided.";
}

// Close the database connection
$conn->close();
?>