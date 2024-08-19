<?pbhp
session_start();

// Check if user is logged in and retrieve username from session
if(isset($_SESSION['username'])) {
    echo $_SESSION['username'];
} else {
    echo "User not logged in";
}
?>