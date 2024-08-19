<?php
session_start();
include 'config.php';

// Check if the user is logged in
if(!isset($_SESSION['user_id'])) {
    // Redirect or handle unauthorized access
    echo json_encode(array("status" => "error", "message" => "User not logged in"));
    exit;
}

// Get user ID from session
$userId = $_SESSION['user_id'];

// Check if required POST data is set
if(isset($_POST['old_name']) && isset($_POST['new_name']) && isset($_POST['institution_name'])) {
    // Sanitize and validate input
    $oldName = mysqli_real_escape_string($conn, $_POST['old_name']);
    $newName = mysqli_real_escape_string($conn, $_POST['new_name']);
    $institutionName = mysqli_real_escape_string($conn, $_POST['institution_name']);

    // Update program name in the sgpa_data table
    $sql = "UPDATE sgpa_data SET program = '$newName' WHERE program = '$oldName' AND institution = '$institutionName' AND user_id = $userId";
    if(mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "Error updating program details";
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Missing parameters"));
}

mysqli_close($conn);
?>