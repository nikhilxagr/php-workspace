<?php
// ============================================
// Student Borrowed Books & Return History
// File: my-books.php
// ============================================

// 1. Enforce authentication and role checks (Students and Teachers only)
require_once 'auth/auth_check.php';
require_once 'auth/role_check.php';

require_role(['student', 'teacher']);

// 2. Include database connection
require_once 'config/db.php';

// Retrieve logged-in student's ID strictly from session
$user_id = $_SESSION['user_id'];

// Flash messages (from book issuance or returns)
$success_message = $_SESSION['success_message'] ?? '';
$error_message   = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// 3. Retrieve user's issued books using a JOIN query
try {
    $stmt = $pdo->prepare("
        SELECT 
            ib.id AS issue_id,
            ib.book_id,
            ib.issue_date,
            ib.return_date,
            ib.status,
            b.title,
            b.author,
            b.category,
            b.isbn
        FROM issued_books ib
        JOIN books b ON ib.book_id = b.id
        WHERE ib.user_id = :user_id
        ORDER BY 
            CASE WHEN ib.status = 'issued' THEN 1 ELSE 2 END,
            ib.issue_date DESC,
            ib.id DESC
    ");
    $stmt->execute([':user_id' => $user_id]);
    $my_books = $stmt->fetchAll();

} catch (PDOException $e) {
    $my_books = [];
    $error_message = "Unable to load your borrowed books. Please try again.";
}

// Page title for header
$page_title = "My Books - Library Management System";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="main-content">
    <div class="container">
        
        <!-- Page Header -->
        <div class="page-header-flex">
            <div>
                <h1>📑 My Issued Books</h1>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 4px;">
                    View your currently borrowed textbooks and return history.
                </p>
            </div>
            <div>
                <a href="books.php" class="btn btn-primary">Browse More Books</a>
            </div>
        </div>

        <!-- Success Notification -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <span>✅</span>
                <span><?php echo htmlspecialchars($success_message); ?></span>
            </div>
        <?php endif; ?>

        <!-- Error Notification -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <span>⚠️</span>
                <span><?php echo htmlspecialchars($error_message); ?></span>
            </div>
        <?php endif; ?>

        <!-- Issued Books Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Issue Date</th>
                        <th>Return Date</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($my_books)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 36px 20px; color: var(--text-muted);">
                                You haven't issued any books yet. 
                                <div style="margin-top: 10px;">
                                    <a href="books.php" class="btn btn-outline btn-sm">Explore Book Catalog</a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($my_books as $row): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">
                                        ISBN: <code><?php echo htmlspecialchars($row['isbn']); ?></code>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($row['author']); ?></td>
                                <td><?php echo date('d M Y', strtotime($row['issue_date'])); ?></td>
                                <td>
                                    <?php if (!empty($row['return_date'])): ?>
                                        <?php echo date('d M Y', strtotime($row['return_date'])); ?>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted);">—</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($row['status'] === 'issued'): ?>
                                        <span class="badge badge-success">Issued</span>
                                    <?php else: ?>
                                        <span class="badge" style="background-color: #f1f5f9; color: #475569;">Returned</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($row['status'] === 'issued'): ?>
                                        <!-- Return Book Button Form -->
                                        <form action="return-book.php" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to return this book?');">
                                            <input type="hidden" name="issue_id" value="<?php echo (int)$row['issue_id']; ?>">
                                            <button type="submit" class="btn btn-outline btn-sm">
                                                Return Book
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 0.88rem;">Completed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<?php include 'includes/footer.php'; ?>
