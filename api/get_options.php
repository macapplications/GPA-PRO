<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect user to login page if not logged in
    header("Location: login.html");
    exit;
}

// Fetch options for institution and program for the logged-in user
$userId = $_SESSION['user_id'];
$institutionOptions = array();
$programOptions = array();

// Prepare and execute SQL query to fetch options for institution
$stmt = $conn->prepare("SELECT DISTINCT institution FROM sgpa_data WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $institutionOptions[] = $row['institution'];
}

// Prepare and execute SQL query to fetch options for program
$stmt = $conn->prepare("SELECT DISTINCT program FROM sgpa_data WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $programOptions[] = $row['program'];
}

// Close the prepared statement and database connection
$stmt->close();
$conn->close();

// Send JSON response containing options for institution and program
$response = array(
    'institutions' => $institutionOptions,
    'programs' => $programOptions
);
echo json_encode($response);
?>