<?php
// ============================================
// Authentication Check Helper
// Protects pages by ensuring user is logged in
// File: auth/auth_check.php
//
// Usage at the top of protected pages:
// require_once 'auth/auth_check.php';
// ============================================

// 1. Start PHP session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Prevent browser from caching protected pages
// (Ensures pressing back button after logout will not reveal protected pages)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// 3. Check whether $_SESSION['user_id'] exists
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect user to login page
    header("Location: login.php");
    exit(); // Stop execution immediately after redirect
}
?>
