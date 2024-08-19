<?php
include 'session.php';
include 'config.php';

$fullname = $_POST['fullname'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql_check_email = "SELECT * FROM users WHERE email = '$email'";
$result_email = mysqli_query($conn, $sql_check_email);

$sql_check_username = "SELECT * FROM users WHERE username = '$username'";
$result_username = mysqli_query($conn, $sql_check_username);

if (mysqli_num_rows($result_email) > 0) {
    echo json_encode(array("status" => "error", "message" => "Email is already registered."));
} elseif (mysqli_num_rows($result_username) > 0) {
    echo json_encode(array("status" => "error", "message" => "Username is already taken."));
} else {
    $sql = "INSERT INTO users (fullname, email, username, password) VALUES ('$fullname', '$email', '$username', '$password')";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(array("status" => "success", "message" => "Registration successful!"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Registration failed."));
    }
}

mysqli_close($conn);
?>