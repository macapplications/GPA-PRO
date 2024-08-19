<?php
include 'session.php';
require 'config.php';

// Check if the token is provided
if (!isset($_POST['token'])) {
    echo "Token not provided.";
    exit;
}

// Retrieve token from the POST data
$token = $_POST['token'];

// Ensure $token is properly escaped to prevent SQL injection
$escaped_token = mysqli_real_escape_string($conn, $token);

// Check if token exists in the database
$sql_check_token = "SELECT COUNT(*) AS count FROM password_reset_requests WHERE token = '$escaped_token'";
$result = mysqli_query($conn, $sql_check_token);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'];
    if ($count > 0) {
        // Token exists
        if (isset($_POST['update_password']) && $_POST['update_password'] === 'true') {
            // Password update requested
            $password = $_POST['password'];
            // Update password in the users table
            $sql_get_email = "SELECT email FROM password_reset_requests WHERE token = '$escaped_token'";
            $email_result = mysqli_query($conn, $sql_get_email);
            if ($email_result && mysqli_num_rows($email_result) > 0) {
                $email_row = mysqli_fetch_assoc($email_result);
                $email = $email_row['email'];
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql_update_password = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
                if (mysqli_query($conn, $sql_update_password)) {
                    // Password updated successfully
                    // Delete the token from password_reset_requests
                    $sql_delete_token = "DELETE FROM password_reset_requests WHERE token = '$escaped_token'";
                    if (mysqli_query($conn, $sql_delete_token)) {
                        // Token deleted successfully
                        echo "success";
                    } else {
                        // Error deleting token
                        echo "Error deleting token: " . mysqli_error($conn);
                    }
                } else {
                    // Error updating password
                    echo "Error updating password: " . mysqli_error($conn);
                }
            } else {
                // Token found but no matching email
                echo "Token found but no matching email.";
            }
        } else {
            // Just checking token existence
            echo "exists";
        }
    } else {
        // Token doesn't exist
        echo "not_exists";
    }
} else {
    // Error checking token
    echo "Error checking token.";
}

mysqli_close($conn);
?>