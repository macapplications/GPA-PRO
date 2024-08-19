<?php
header("Access-Control-Allow-Origin: https://gpapro.web.app");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Your other configuration code...

// Handle preflight requests (if you're using complex requests with custom headers)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}



// Your existing PHP code here

$servername = "sql12.freesqldatabase.com";
$username = "sql12726414";
$password = "CAkPUsZeqS";
$database = "sql12726414";


// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

define('EMAIL', 'johnwickfromjohnwick007@gmail.com');
define('PASSWORD', 'nhwajjzybzaacbzi');


?>