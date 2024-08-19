<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "not_logged_in";
    exit;
}

// Fetch SGPA data from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM sgpa_data WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

// Organize SGPA data by institution
$sgpa_by_institution = array();
while ($row = mysqli_fetch_assoc($result)) {
    $institution = $row['institution'];
    $program = $row['program'];
    $semester = $row['semester'];
    $sgpa = $row['sgpa'];

    // Add program details to the institution array
    if (!isset($sgpa_by_institution[$institution])) {
        $sgpa_by_institution[$institution] = array();
    }
    if (!isset($sgpa_by_institution[$institution][$program])) {
        $sgpa_by_institution[$institution][$program] = array();
    }
    $sgpa_by_institution[$institution][$program][] = array('semester' => $semester, 'sgpa' => $sgpa);
}

mysqli_close($conn);

// Convert SGPA data to JSON format
echo json_encode($sgpa_by_institution);
?>