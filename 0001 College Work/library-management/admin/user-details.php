<?php
// ============================================
// User Details & Activity - Admin Panel
// File: admin/user-details.php
// ============================================

require_once '../auth/auth_check.php';
require_once '../auth/role_check.php';

// Strict server-side admin role check
require_role(['admin']);

require_once '../config/db.php';
$base_path = '../';

// Validate user ID from GET parameter
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id <= 0) {
    header("Location: users.php");
    exit();
}

$user = null;
$activity = [];
$total_history = 0;
$active_count = 0;
$returned_count = 0;
$error_message = '';

try {
    // 1. Fetch user profile (Explicitly excluding password)
    $stmt_user = $pdo->prepare("
        SELECT id, name, email, role, created_at 
        FROM users 
        WHERE id = :id 
        LIMIT 1
    ");
    $stmt_user->execute([':id' => $user_id]);
    $user = $stmt_user->fetch();

    if (!$user) {
        $error_message = "User not found or has been removed.";
    } else {
        // 2. Fetch full library borrowing history for this user
        $stmt_history = $pdo->prepare("
            SELECT 
                ib.id AS issue_id,
                ib.book_id,
                ib.issue_date,
                ib.return_date,
                ib.status,
                b.title,
                b.author,
                b.isbn,
                b.category
            FROM issued_books ib
            JOIN books b ON ib.book_id = b.id
            WHERE ib.user_id = :user_id
            ORDER BY ib.id DESC
        ");
        $stmt_history->execute([':user_id' => $user_id]);
        $activity = $stmt_history->fetchAll();

        // 3. Compute stats
        $total_history = count($activity);
        foreach ($activity as $item) {
            if ($item['status'] === 'issued') {
                $active_count++;
            } elseif ($item['status'] === 'returned') {
                $returned_count++;
            }
        }
    }

} catch (PDOException $e) {
    error_log("User details query error: " . $e->getMessage());
    $error_message = "An error occurred while retrieving user details.";
}

$page_title = $user ? "User: " . htmlspecialchars($user['name']) . " - Admin Panel" : "User Details - Admin Panel";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="main-content">
    <div class="container">

        <!-- Page Header & Back Navigation -->
        <div class="page-header-flex">
            <div>
                <h1>👤 User Profile & Library Activity</h1>
                <p>Detailed borrowing history and account credentials status.</p>
            </div>
            <div style="display: flex; gap: 8px;">
                <?php if ($user && $user['role'] === 'teacher'): ?>
                    <a href="teacher-details.php" class="btn btn-outline">← Back to Teachers</a>
                <?php endif; ?>
                <a href="users.php" class="btn btn-outline">← Back to Users</a>
            </div>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
            <div style="margin-top: 16px;">
                <a href="users.php" class="btn btn-primary">Return to User Directory</a>
            </div>
        <?php else: ?>

            <!-- User Information Card -->
            <div class="card" style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <h2 style="font-size: 1.4rem; margin: 0;"><?php echo htmlspecialchars($user['name']); ?></h2>
                            <?php if ($user['role'] === 'admin'): ?>
                                <span class="badge badge-danger">Admin</span>
                            <?php elseif ($user['role'] === 'teacher'): ?>
                                <span class="badge badge-category" style="background-color: #fef3c7; color: #92400e; border-color: #fde68a;">Teacher</span>
                            <?php else: ?>
                                <span class="badge badge-category">Student</span>
                            <?php endif; ?>
                        </div>
                        <p style="color: var(--text-muted); margin: 0; font-size: 0.95rem;">
                            User ID: <strong>#<?php echo (int)$user['id']; ?></strong> &nbsp;|&nbsp; 
                            Email: <strong><?php echo htmlspecialchars($user['email']); ?></strong>
                        </p>
                    </div>

                    <div style="text-align: right;">
                        <span style="font-size: 0.85rem; color: var(--text-muted); display: block;">Registered On</span>
                        <strong><?php echo date('d M Y, h:i A', strtotime($user['created_at'])); ?></strong>
                    </div>
                </div>
            </div>

            <!-- Library Activity Overview Statistics -->
            <div class="stat-grid" style="margin-bottom: 28px;">
                <div class="stat-card">
                    <span class="stat-label">Currently Issued</span>
                    <span class="stat-value" style="color: var(--primary);"><?php echo $active_count; ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Successfully Returned</span>
                    <span class="stat-value" style="color: var(--success-text);"><?php echo $returned_count; ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Lifetime Issues</span>
                    <span class="stat-value"><?php echo $total_history; ?></span>
                </div>
            </div>

            <!-- Library Activity History Table -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h3 style="font-size: 1.15rem; margin: 0;">📖 Borrowing & Return History</h3>
                    <span style="font-size: 0.88rem; color: var(--text-muted);">
                        Total records: <?php echo $total_history; ?>
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>ISBN</th>
                                <th>Issue Date</th>
                                <th>Return Date</th>
                                <th style="text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($activity)): ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 32px; color: var(--text-muted);">
                                        This user has no recorded borrowing activity.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($activity as $idx => $act): ?>
                                    <tr>
                                        <td>
                                            <span style="color: var(--text-muted); font-size: 0.85rem;">
                                                <?php echo $idx + 1; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($act['title']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($act['author']); ?></td>
                                        <td>
                                            <span class="badge badge-category">
                                                <?php echo htmlspecialchars($act['category'] ?? 'General'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-family: monospace; font-size: 0.88rem; color: var(--text-muted);">
                                                <?php echo htmlspecialchars($act['isbn']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo date('d M Y', strtotime($act['issue_date'])); ?>
                                        </td>
                                        <td>
                                            <?php if ($act['return_date']): ?>
                                                <span style="color: var(--success-text);">
                                                    <?php echo date('d M Y', strtotime($act['return_date'])); ?>
                                                </span>
                                            <?php else: ?>
                                                <span style="color: var(--text-muted); font-style: italic;">
                                                    Not returned
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if ($act['status'] === 'issued'): ?>
                                                <span class="badge badge-warning">Currently Issued</span>
                                            <?php elseif ($act['status'] === 'returned'): ?>
                                                <span class="badge badge-success">Returned</span>
                                            <?php else: ?>
                                                <span class="badge"><?php echo htmlspecialchars(ucfirst($act['status'])); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php include '../includes/footer.php'; ?>
