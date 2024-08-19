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

// Set CORS headers
header("Access-Control-Allow-Origin: http://localhost:7700"); // Replace with your frontend URL
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    echo "logged_in";
} else {
    echo "not_logged_in";
}
?>