<?php
session_start();
include 'config.php';

// Check if the user is logged in and email is provided
if(isset($_SESSION['username']) && isset($_POST['email'])) {
    $newEmail = $_POST['email'];

    // Prepare and execute the SQL statement to update the email
    $sql = "UPDATE users SET email = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $newEmail, $_SESSION['username']);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error updating email: " . $stmt->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo "Email or session not set";
}
?>