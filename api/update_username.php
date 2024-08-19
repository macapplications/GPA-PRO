<?php
session_start();
include 'config.php';

// Check if the user is logged in and username is provided
if(isset($_SESSION['username']) && isset($_POST['username'])) {
    $newUsername = $_POST['username'];

    // Prepare and execute the SQL statement to update the username
    $sql = "UPDATE users SET username = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $newUsername, $_SESSION['username']);

    if ($stmt->execute()) {
        $_SESSION['username'] = $newUsername; // Update session with new username
        echo "success";
    } else {
        echo "Error updating username: " . $stmt->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo "Username or session not set";
}
?>