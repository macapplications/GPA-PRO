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
?>