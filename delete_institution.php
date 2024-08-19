<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If user is not logged in, redirect to login page
    header("Location: login.html");
    exit;
}

// Check if the institution name is provided in the POST request
if(isset($_POST['institution_name'])) {
    // Get the institution name from the POST data
    $institutionName = $_POST['institution_name'];
    
    // Get the user ID of the current user
    $userId = $_SESSION['user_id'];

    // Perform the deletion operation in the database with user ID condition
    $sql = "DELETE FROM sgpa_data WHERE institution = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $institutionName, $userId);

    if ($stmt->execute()) {
        // Deletion successful
        echo "Institution data deleted successfully.";
    } else {
        // Error occurred during deletion
        echo "Error deleting institution data: " . $conn->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    // If institution name is not provided in the POST request
    echo "Institution name not provided.";
}
?>