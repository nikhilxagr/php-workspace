<?php
// ============================================
// Navigation Bar
// File: includes/navbar.php
//
// Role-based navigation for Students, Teachers & Admins
// ============================================

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Path prefix for subdirectories (e.g., admin/)
$p = $base_path ?? '';

// Current page filename for active link highlighting
$current_page = basename($_SERVER['PHP_SELF']);

// Check whether the user is authenticated and get their role
$is_logged_in = isset($_SESSION['user_id']);
$user_role    = $_SESSION['user_role'] ?? 'student';

// Brand link destination
$brand_link = $is_logged_in ? (($user_role === 'admin') ? $p . 'admin/dashboard.php' : $p . 'dashboard.php') : $p . 'index.php';
?>

<nav class="navbar">
    <div class="container">
        <!-- Brand Title -->
        <a href="<?php echo $brand_link; ?>" class="nav-brand">
            <span>📚 Library Management System</span>
        </a>

        <!-- Navigation Links -->
        <ul class="nav-links">
            <?php if ($is_logged_in): ?>
                
                <?php if ($user_role === 'admin'): ?>
                    <!-- ADMIN NAVIGATION -->
                    <li>
                        <a href="<?php echo $p; ?>admin/dashboard.php" class="<?php echo ($current_page === 'dashboard.php' && !empty($base_path)) ? 'active' : ''; ?>">
                            Admin Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $p; ?>admin/books.php" class="<?php echo ($current_page === 'books.php' && !empty($base_path)) ? 'active' : ''; ?>">
                            Manage Books
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $p; ?>admin/add-book.php" class="<?php echo ($current_page === 'add-book.php') ? 'active' : ''; ?>">
                            Add Book
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $p; ?>admin/users.php" class="<?php echo ($current_page === 'users.php' || $current_page === 'user-details.php') ? 'active' : ''; ?>">
                            Manage Users
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $p; ?>admin/teacher-details.php" class="<?php echo ($current_page === 'teacher-details.php' || $current_page === 'add-teacher.php') ? 'active' : ''; ?>">
                            Teachers
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $p; ?>profile.php" class="<?php echo ($current_page === 'profile.php') ? 'active' : ''; ?>">
                            Profile
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $p; ?>logout.php" class="btn btn-outline" style="padding: 6px 14px; font-size: 0.88rem;">
                            Logout
                        </a>
                    </li>

                <?php else: ?>
                    <!-- STUDENT & TEACHER NAVIGATION -->
                    <li>
                        <a href="<?php echo $p; ?>dashboard.php" class="<?php echo ($current_page === 'dashboard.php' && empty($base_path)) ? 'active' : ''; ?>">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $p; ?>books.php" class="<?php echo ($current_page === 'books.php' && empty($base_path)) ? 'active' : ''; ?>">
                            Books
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $p; ?>my-books.php" class="<?php echo ($current_page === 'my-books.php') ? 'active' : ''; ?>">
                            My Books
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $p; ?>profile.php" class="<?php echo ($current_page === 'profile.php') ? 'active' : ''; ?>">
                            Profile
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $p; ?>logout.php" class="btn btn-outline" style="padding: 6px 14px; font-size: 0.88rem;">
                            Logout
                        </a>
                    </li>
                <?php endif; ?>

            <?php else: ?>
                <!-- LOGGED-OUT NAVIGATION -->
                <li>
                    <a href="<?php echo $p; ?>login.php" class="<?php echo ($current_page === 'login.php') ? 'active' : ''; ?>">
                        Login
                    </a>
                </li>
                <li>
                    <a href="<?php echo $p; ?>register.php" class="btn btn-primary" style="padding: 6px 14px; font-size: 0.88rem;">
                        Register
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
