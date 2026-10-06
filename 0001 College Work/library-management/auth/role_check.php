<?php
// ============================================
// Role-Based Authorization Helper
// File: auth/role_check.php
// ============================================

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Checks whether the logged-in user possesses one of the allowed roles.
 * Redirects to unauthorized.php if permission is denied.
 *
 * @param array $allowed_roles List of permitted roles, e.g. ['admin'] or ['student', 'teacher']
 */
function require_role(array $allowed_roles) {
    // 1. Verify user is authenticated
    if (!isset($_SESSION['user_id'])) {
        $login_path = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../login.php' : 'login.php';
        header("Location: " . $login_path);
        exit();
    }

    // 2. Read role from session
    $current_role = $_SESSION['user_role'] ?? 'student';

    // 3. Check if current role is permitted
    if (!in_array($current_role, $allowed_roles, true)) {
        // Determine relative path to unauthorized.php
        $unauthorized_path = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../unauthorized.php' : 'unauthorized.php';
        header("Location: " . $unauthorized_path);
        exit(); // Always terminate execution after redirect
    }
}
?>
