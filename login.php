<?php

// Set session cookie parameters
session_set_cookie_params([
    'lifetime' => 86400, // Cookie lifetime in seconds
    'path' => '/',
    'domain' => '', // Leave empty to apply to current domain
    'secure' => false, // Set to true if using HTTPS
    'httponly' => true,
    'samesite' => 'None' // Required for cross-site requests
]);

session_start();
include 'config.php';

// Check if the user is already logged in
if(isset($_SESSION['username'])&& isset($_SESSION['user_id'])) {
    header("Location: sgpa_calculator.html");
    exit;
}

// If user is not logged in, process login attempt
if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['id'];

            echo "success";
            
        } else {
            echo "Invalid username or password";
            exit;
        }
    } else {
        echo "User not found";
        exit;
    }
}

mysqli_close($conn);
?>