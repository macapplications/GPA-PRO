<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
    echo "User not logged in";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request method";
    exit;
}

$newFullName = $_POST['fullname'];

$username = $_SESSION['username'];

$sql = "UPDATE users SET fullname = ? WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $newFullName, $username);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Error updating full name: " . $conn->error;
}

$stmt->close();
$conn->close();
?>