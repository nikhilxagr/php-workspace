<?php
// ============================================
// Teacher Management & Details - Admin Panel
// File: admin/teacher-details.php
// ============================================

require_once '../auth/auth_check.php';
require_once '../auth/role_check.php';

// Strict server-side admin role check
require_role(['admin']);

require_once '../config/db.php';
$base_path = '../';

// Flash messages
$success_message = $_SESSION['success_message'] ?? '';
$error_message   = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Search parameter
$search = trim($_GET['search'] ?? '');

try {
    // Query only users whose role is 'teacher'
    // Along with aggregate metrics for currently issued, returned, and total history
    $sql = "
        SELECT 
            u.id,
            u.name,
            u.email,
            u.created_at,
            COUNT(CASE WHEN ib.status = 'issued' THEN 1 END) AS currently_issued,
            COUNT(CASE WHEN ib.status = 'returned' THEN 1 END) AS returned_books,
            COUNT(ib.id) AS total_history
        FROM users u
        LEFT JOIN issued_books ib ON u.id = ib.user_id
        WHERE u.role = 'teacher'
    ";

    $params = [];
    if (!empty($search)) {
        $sql .= " AND (u.name LIKE :search OR u.email LIKE :search)";
        $params[':search'] = "%" . $search . "%";
    }

    $sql .= " GROUP BY u.id, u.name, u.email, u.created_at ORDER BY u.id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $teachers = $stmt->fetchAll();

    // Summary counts
    $total_teachers = count($teachers);
    $active_teacher_loans = (int)$pdo->query("
        SELECT COUNT(*) 
        FROM issued_books ib 
        JOIN users u ON ib.user_id = u.id 
        WHERE u.role = 'teacher' AND ib.status = 'issued'
    ")->fetchColumn();

} catch (PDOException $e) {
    error_log("Teacher details query error: " . $e->getMessage());
    $teachers = [];
    $total_teachers = 0;
    $active_teacher_loans = 0;
    $error_message = "Failed to load teacher records.";
}

$page_title = "Teacher Management - Admin Panel";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="main-content">
    <div class="container">

        <!-- Page Header -->
        <div class="page-header-flex">
            <div>
                <h1>👨‍🏫 Faculty & Teacher Management</h1>
                <p>Manage teacher accounts, library privileges, and monitor faculty borrowing activity.</p>
            </div>
            <div>
                <a href="add-teacher.php" class="btn btn-primary">➕ Add New Teacher</a>
            </div>
        </div>

        <!-- Feedback Alerts -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Quick Summary Stats -->
        <div class="stat-grid" style="margin-bottom: 24px;">
            <div class="stat-card">
                <span class="stat-label">Total Faculty Accounts</span>
                <span class="stat-value"><?php echo $total_teachers; ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Active Faculty Loans</span>
                <span class="stat-value" style="color: var(--primary);"><?php echo $active_teacher_loans; ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Privilege Level</span>
                <span class="stat-value" style="font-size: 1.35rem; color: #92400e;">Standard Faculty</span>
            </div>
        </div>

        <!-- Search Bar -->
        <form action="teacher-details.php" method="GET" class="search-filter-box">
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Search teacher by name or email..." 
                value="<?php echo htmlspecialchars($search); ?>"
            >
            <button type="submit" class="btn btn-primary" style="padding: 9px 18px;">Search</button>
            <?php if (!empty($search)): ?>
                <a href="teacher-details.php" class="btn btn-outline" style="padding: 9px 18px;">Reset</a>
            <?php endif; ?>
        </form>

        <!-- Teachers Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th>Teacher Name</th>
                        <th>Email Address</th>
                        <th>Registered Date</th>
                        <th style="text-align: center;">Currently Issued</th>
                        <th style="text-align: center;">Returned Books</th>
                        <th style="text-align: center;">Total History</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($teachers)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 36px; color: var(--text-muted);">
                                <?php if (!empty($search)): ?>
                                    No teachers found matching "<strong><?php echo htmlspecialchars($search); ?></strong>".
                                <?php else: ?>
                                    No teacher accounts registered in the system yet.
                                    <div style="margin-top: 12px;">
                                        <a href="add-teacher.php" class="btn btn-primary btn-sm">➕ Create First Teacher Account</a>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($teachers as $t): ?>
                            <tr>
                                <td>
                                    <span style="color: var(--text-muted); font-size: 0.85rem;">
                                        #<?php echo (int)$t['id']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--text-dark);">
                                        <?php echo htmlspecialchars($t['name']); ?>
                                    </div>
                                    <span class="badge" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 0.72rem; padding: 2px 6px;">
                                        Faculty
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($t['email']); ?></td>
                                <td><?php echo date('d M Y', strtotime($t['created_at'])); ?></td>
                                <td style="text-align: center;">
                                    <?php if ($t['currently_issued'] > 0): ?>
                                        <span class="badge badge-warning"><?php echo (int)$t['currently_issued']; ?> active</span>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted);">0</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <span style="color: var(--success-text); font-weight: 500;">
                                        <?php echo (int)$t['returned_books']; ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <strong><?php echo (int)$t['total_history']; ?></strong>
                                </td>
                                <td style="text-align: center;">
                                    <a href="user-details.php?id=<?php echo (int)$t['id']; ?>" class="btn btn-outline btn-sm">
                                        View History
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<?php include '../includes/footer.php'; ?>
