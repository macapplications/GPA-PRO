<?php
session_start();
include 'config.php';

// Check if the user is logged in and passwords are provided
if(isset($_SESSION['username']) && isset($_POST['currentPassword']) && isset($_POST['newPassword'])) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    // Retrieve current password from the database
    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $storedPassword = $row['password'];

    // Verify if the current password matches the stored password
    if(password_verify($currentPassword, $storedPassword)) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $updateSql = "UPDATE users SET password = ? WHERE username = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ss", $hashedPassword, $_SESSION['username']);
        if ($updateStmt->execute()) {
            echo "success";
        } else {
            echo "Error updating password: " . $updateStmt->error;
        }
        $updateStmt->close();
    } else {
        echo "Current password is incorrect";
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo "Passwords or session not set";
}
?>