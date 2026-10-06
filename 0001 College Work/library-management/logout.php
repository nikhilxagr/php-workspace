<?php
// ============================================
// Logout Script
// Destroys user session and redirects to login
// File: logout.php
// ============================================

// 1. Start the session
session_start();

// 2. Unset all session variables
$_SESSION = [];

// 3. Clear session cookie from the browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 4. Destroy the session on the server
session_destroy();

// 5. Redirect the user to login.php
header("Location: login.php");
exit(); // Always stop execution after redirect
?>
