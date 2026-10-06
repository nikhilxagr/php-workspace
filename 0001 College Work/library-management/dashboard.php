<?php
// ============================================
// Student Dashboard
// File: dashboard.php
// ============================================

// 1. Enforce authentication and role checks (Must be at the top)
require_once 'auth/auth_check.php';
require_once 'auth/role_check.php';

// Admins should be directed to the admin dashboard
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header("Location: admin/dashboard.php");
    exit();
}

require_role(['student', 'teacher']);

// 2. Include database connection
require_once 'config/db.php';

// 3. Retrieve user information from the session
$user_id   = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Student';
$user_role = $_SESSION['user_role'] ?? 'student';

// 4. Query summary counts from database using PDO
try {
    // Total books count in library catalog
    $stmt_total = $pdo->query("SELECT COUNT(*) FROM books");
    $total_books = (int)$stmt_total->fetchColumn();

    // Available books (titles that currently have copies in stock)
    $stmt_avail = $pdo->query("SELECT COUNT(*) FROM books WHERE available_quantity > 0");
    $available_books = (int)$stmt_avail->fetchColumn();

    // Books currently issued to this logged-in student/teacher
    $stmt_issued = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE user_id = :user_id AND status = 'issued'");
    $stmt_issued->execute([':user_id' => $user_id]);
    $my_issued_books = (int)$stmt_issued->fetchColumn();

} catch (PDOException $e) {
    // Fallback values if database query encounters an issue
    $total_books     = 0;
    $available_books = 0;
    $my_issued_books = 0;
}

// Page title for header
$page_title = "Dashboard - Library Management System";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="main-content">
    <div class="container">
        
        <!-- Welcome Section -->
        <section class="dashboard-welcome">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>! 👋</h1>
                    <p>Welcome to your Library Management dashboard. Here is an overview of the collection and your borrowed books.</p>
                </div>
                <div>
                    <span class="badge badge-category" style="text-transform: capitalize; font-size: 0.88rem; padding: 6px 14px;">
                        Role: <?php echo htmlspecialchars($user_role); ?>
                    </span>
                </div>
            </div>
        </section>

        <!-- Three Summary Cards -->
        <section class="dashboard-cards">
            
            <!-- Card 1: Total Books -->
            <div class="dash-card">
                <div class="dash-card-icon">📚</div>
                <div class="dash-card-info">
                    <h3><?php echo $total_books; ?></h3>
                    <p>Total Books</p>
                </div>
            </div>

            <!-- Card 2: Available Books -->
            <div class="dash-card">
                <div class="dash-card-icon">✅</div>
                <div class="dash-card-info">
                    <h3><?php echo $available_books; ?></h3>
                    <p>Available Books</p>
                </div>
            </div>

            <!-- Card 3: My Issued Books -->
            <div class="dash-card">
                <div class="dash-card-icon">📖</div>
                <div class="dash-card-info">
                    <h3><?php echo $my_issued_books; ?></h3>
                    <p>My Issued Books</p>
                </div>
            </div>

        </section>

        <!-- Quick Action Buttons Section -->
        <section class="quick-actions-section">
            <h2>Quick Actions</h2>
            <div class="action-buttons-grid">
                <a href="books.php" class="btn btn-primary">Browse Books</a>
                <a href="my-books.php" class="btn btn-outline">My Books</a>
                <a href="profile.php" class="btn btn-outline">My Profile</a>
                <a href="logout.php" class="btn btn-danger-outline">Logout</a>
            </div>
        </section>

    </div>
</main>

<?php include 'includes/footer.php'; ?>
