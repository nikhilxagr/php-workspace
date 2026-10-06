<?php
// Start session to display role if logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Access Denied - Library Management System";
include 'includes/header.php';
include 'includes/navbar.php';

$user_role = $_SESSION['user_role'] ?? 'guest';
$home_link = ($user_role === 'admin') ? 'admin/dashboard.php' : 'dashboard.php';
?>

<main class="main-content">
    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-card" style="max-width: 500px; text-align: center;">
                
                <div style="font-size: 3rem; margin-bottom: 12px;">🚫</div>
                <h1 style="font-size: 1.6rem; color: var(--error-text); margin-bottom: 8px;">Access Denied</h1>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 20px;">
                    You do not have permission to access this page.
                </p>

                <div style="background-color: var(--bg-subtle); border: 1px solid var(--border-color); border-radius: var(--radius); padding: 14px; margin-bottom: 24px; font-size: 0.9rem;">
                    Your Current Role: <span class="badge badge-category" style="text-transform: capitalize;"><?php echo htmlspecialchars($user_role); ?></span>
                </div>

                <div>
                    <a href="<?php echo htmlspecialchars($home_link); ?>" class="btn btn-primary btn-block">
                        Return to Your Dashboard
                    </a>
                </div>

            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
