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

if(!isset($_SESSION['username'])) {
  echo json_encode(array('error' => 'User not logged in'));
  exit;
}

$username = $_SESSION['username'];

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();

if($user) {
  echo json_encode($user);
} else {
  echo json_encode(array('error' => 'User not found'));
}
?>