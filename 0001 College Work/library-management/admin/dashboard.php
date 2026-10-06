<?php
// ============================================
// Administrator Dashboard
// File: admin/dashboard.php
// ============================================

require_once '../auth/auth_check.php';
require_once '../auth/role_check.php';

// Enforce admin role strictly server-side
require_role(['admin']);

require_once '../config/db.php';
$base_path = '../';

$admin_name = $_SESSION['user_name'] ?? 'Administrator';

// Query counts from database
try {
    // 1. Total users
    $total_users = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

    // 2. Total students
    $total_students = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();

    // 3. Total teachers
    $total_teachers = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'teacher'")->fetchColumn();

    // 4. Total books (titles)
    $total_books = (int)$pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();

    // 5. Available books (copies ready to borrow)
    $available_books = (int)$pdo->query("SELECT COALESCE(SUM(available_quantity), 0) FROM books")->fetchColumn();

    // 6. Currently issued books
    $currently_issued = (int)$pdo->query("SELECT COUNT(*) FROM issued_books WHERE status = 'issued'")->fetchColumn();

} catch (PDOException $e) {
    error_log("Dashboard query error: " . $e->getMessage());
    $total_users = $total_students = $total_teachers = $total_books = $available_books = $currently_issued = 0;
}

$page_title = "Admin Dashboard - Library Management System";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="main-content">
    <div class="container">
        
        <!-- Welcome Banner -->
        <section class="dashboard-welcome">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h1>Administrator Dashboard 🛡️</h1>
                    <p>Welcome back, <strong><?php echo htmlspecialchars($admin_name); ?></strong>. Here is the complete overview of library operations.</p>
                </div>
                <div>
                    <span class="badge badge-danger" style="font-size: 0.88rem; padding: 6px 14px;">
                        Role: Administrator
                    </span>
                </div>
            </div>
        </section>

        <!-- 6 Summary Cards -->
        <section class="dashboard-cards" style="grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));">
            
            <div class="dash-card">
                <div class="dash-card-icon">👥</div>
                <div class="dash-card-info">
                    <h3><?php echo $total_users; ?></h3>
                    <p>Total Users</p>
                </div>
            </div>

            <div class="dash-card">
                <div class="dash-card-icon">🎓</div>
                <div class="dash-card-info">
                    <h3><?php echo $total_students; ?></h3>
                    <p>Total Students</p>
                </div>
            </div>

            <div class="dash-card">
                <div class="dash-card-icon">👨‍🏫</div>
                <div class="dash-card-info">
                    <h3><?php echo $total_teachers; ?></h3>
                    <p>Total Teachers</p>
                </div>
            </div>

            <div class="dash-card">
                <div class="dash-card-icon">📚</div>
                <div class="dash-card-info">
                    <h3><?php echo $total_books; ?></h3>
                    <p>Total Books</p>
                </div>
            </div>

            <div class="dash-card">
                <div class="dash-card-icon">✅</div>
                <div class="dash-card-info">
                    <h3><?php echo $available_books; ?></h3>
                    <p>Available Books</p>
                </div>
            </div>

            <div class="dash-card">
                <div class="dash-card-icon">📖</div>
                <div class="dash-card-info">
                    <h3><?php echo $currently_issued; ?></h3>
                    <p>Currently Issued</p>
                </div>
            </div>

        </section>

        <!-- Quick Navigation Links -->
        <section class="quick-actions-section">
            <h2>Quick Navigation</h2>
            <div class="action-buttons-grid">
                <a href="dashboard.php" class="btn btn-primary">Dashboard</a>
                <a href="books.php" class="btn btn-outline">Manage Books</a>
                <a href="add-book.php" class="btn btn-outline">Add Book</a>
                <a href="users.php" class="btn btn-outline">Manage Users</a>
                <a href="users.php?role=student" class="btn btn-outline">Students</a>
                <a href="teacher-details.php" class="btn btn-outline">Teachers</a>
                <a href="../logout.php" class="btn btn-danger-outline">Logout</a>
            </div>
        </section>

    </div>
</main>

<?php include '../includes/footer.php'; ?>
