<?php
include 'session.php';

include 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$email = $_POST['email'];

// Check if email is registered
$sql_check_email = "SELECT * FROM users WHERE email = '$email'";
$result_check_email = mysqli_query($conn, $sql_check_email);

if(mysqli_num_rows($result_check_email) == 0){
    echo "not_registered"; // Email is not registered
    exit();
}

// Check if token already exists for the email
$sql_check_token = "SELECT token FROM password_reset_requests WHERE email = '$email'";
$result_check_token = mysqli_query($conn, $sql_check_token);

if(mysqli_num_rows($result_check_token) > 0){
    $row = mysqli_fetch_assoc($result_check_token);
    $token = $row['token'];
} else {
    // Generate new token
    $token = bin2hex(random_bytes(32));
    $sql_insert_token = "INSERT INTO password_reset_requests (email, token) VALUES ('$email', '$token')";
    if(!mysqli_query($conn, $sql_insert_token)){
        echo "Error: " . mysqli_error($conn);
        exit();
    }
}

// Send password reset email

$is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
$protocol = $is_https ? 'https://' : 'http://';
$reset_link = $protocol . $_SERVER['HTTP_HOST'] . '/reset_password.html?token=' . $token;
$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = EMAIL;
    $mail->Password   = PASSWORD;
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;
    
    
    $mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
    
    

    //Recipients
    $mail->setFrom(EMAIL, 'John Wick');
    $mail->addAddress($email);

// Fetch username associated with the email
$sql_get_username = "SELECT username FROM users WHERE email = '$email'";
$result_get_username = mysqli_query($conn, $sql_get_username);

if(mysqli_num_rows($result_get_username) > 0){
    $row_username = mysqli_fetch_assoc($result_get_username);
    $username = $row_username['username'];
} else {
    // If username not found, use a default value
    $username = 'User';
}

//Content
$mail->isHTML(true);
$mail->Subject = 'Password Reset Request';
$mail->Body    = "
    <html>
    <head>
        <style>
            /* Button styles */
            .reset-button {
                display: inline-block;
                padding: 12px 24px;
                background-color: #007bff;
                color: #ffffff;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
            }
            /* Main content styles */
            .email-content {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #000000; /* Set text color to black */
            }
        </style>
    </head>
    <body class='email-content'>
        <p>Hi $username,</p>
        <p>We have received a request to reset the password associated with your email address.</p>
        <p>If you did not request this, please ignore this email.</p>
        <p>To reset your password, please click the button below:</p>
        <a href='$reset_link' class='reset-button' target='_blank'>Reset Password</a>
        <p>If you're having trouble clicking the password reset button, you can copy and paste the following URL into your web browser:</p>
        <p><a href='$reset_link'>$reset_link</a></p>
        <p>This link will expire in 1 hour for security reasons.</p>
        <p>Thank you,<br>John Wick</p>
    </body>
    </html>
";

    $mail->send();
    echo "success";
} catch (Exception $e) {
    echo "Error: " . $mail->ErrorInfo;
}

mysqli_close($conn);
?>