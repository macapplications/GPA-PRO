<?php
session_start();
include 'config.php';

// Check if the user is logged in
if(!isset($_SESSION['user_id'])) {
    // Redirect or handle unauthorized access
    echo json_encode(array("status" => "error", "message" => "User not logged in"));
    exit;
}

// Check if required POST data is set
if(isset($_POST['old_name']) && isset($_POST['new_name'])) {
    // Sanitize and validate input
    $oldName = mysqli_real_escape_string($conn, $_POST['old_name']);
    $newName = mysqli_real_escape_string($conn, $_POST['new_name']);
    $userId = $_SESSION['user_id'];

    // Update institution name in the sgpa_data table
    $sql = "UPDATE sgpa_data SET institution = '$newName' WHERE institution = '$oldName' AND user_id = $userId";
    if(mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "Error updating institution details";
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Missing parameters"));
}

mysqli_close($conn);
?>