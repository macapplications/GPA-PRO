<?php
session_start();
include 'config.php';

if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
   $userId = $_SESSION['user_id'];
    // First, delete associated records from sgpa_data table
    $delete_sgpa_sql = "DELETE FROM sgpa_data WHERE user_id = '$userId'";
    if(mysqli_query($conn, $delete_sgpa_sql)) {
        // Deleted associated records successfully
        // Now, delete user account from users table
        $delete_user_sql = "DELETE FROM users WHERE username = '$username'";
        if(mysqli_query($conn, $delete_user_sql)) {
            // Account deleted successfully
            echo "Account deleted successfully";
            // Log out user
            session_unset();
            session_destroy();
        } else {
            // Error deleting user account
            echo "Error deleting user account";
        }
    } else {
        // Error deleting associated records
        echo "Error deleting associated records";
    }
} else {
    // User not logged in
    echo "User not logged in";
}

mysqli_close($conn);
?>
