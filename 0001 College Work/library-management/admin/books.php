<?php
// ============================================
// Manage Books - Admin Panel
// File: admin/books.php
// ============================================

require_once '../auth/auth_check.php';
require_once '../auth/role_check.php';

// Enforce admin authorization
require_role(['admin']);

require_once '../config/db.php';
$base_path = '../';

// Generate CSRF token for safe delete actions
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Flash messages
$success_message = $_SESSION['success_message'] ?? '';
$error_message   = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Search query
$search = trim($_GET['search'] ?? '');

try {
    // Query books with active issued copies count to indicate deletion eligibility
    $sql = "
        SELECT 
            b.id,
            b.title,
            b.author,
            b.category,
            b.isbn,
            b.quantity,
            b.available_quantity,
            (SELECT COUNT(*) FROM issued_books ib WHERE ib.book_id = b.id AND ib.status = 'issued') AS active_issued_count
        FROM books b
        WHERE 1=1
    ";
    $params = [];

    if (!empty($search)) {
        $sql .= " AND (b.title LIKE :search OR b.author LIKE :search OR b.isbn LIKE :search)";
        $params[':search'] = "%" . $search . "%";
    }

    $sql .= " ORDER BY b.title ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $books = $stmt->fetchAll();

} catch (PDOException $e) {
    error_log("Books fetch error: " . $e->getMessage());
    $books = [];
    $error_message = "Failed to load book catalog.";
}

$page_title = "Manage Books - Admin Panel";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="main-content">
    <div class="container">
        
        <div class="page-header-flex">
            <div>
                <h1>📚 Manage Library Books</h1>
                <p>Add, edit, search, and manage textbooks in the library catalog.</p>
            </div>
            <div>
                <a href="add-book.php" class="btn btn-primary">➕ Add New Book</a>
            </div>
        </div>

        <!-- Success Alert -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <span>✅</span>
                <span><?php echo htmlspecialchars($success_message); ?></span>
            </div>
        <?php endif; ?>

        <!-- Error Alert -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <span>⚠️</span>
                <span><?php echo htmlspecialchars($error_message); ?></span>
            </div>
        <?php endif; ?>

        <!-- Search Form -->
        <form action="books.php" method="GET" class="search-filter-box">
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Search by Title, Author, or ISBN..." 
                value="<?php echo htmlspecialchars($search); ?>"
            >
            <button type="submit" class="btn btn-primary" style="padding: 9px 18px;">Search</button>
            <?php if (!empty($search)): ?>
                <a href="books.php" class="btn btn-outline" style="padding: 9px 18px;">Reset</a>
            <?php endif; ?>
        </form>

        <!-- Books Management Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>ISBN</th>
                        <th style="text-align: center;">Total</th>
                        <th style="text-align: center;">Available</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($books)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 32px; color: var(--text-muted);">
                                No books found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($books as $b): ?>
                            <tr>
                                <td><span style="color: var(--text-muted); font-size: 0.85rem;">#<?php echo $b['id']; ?></span></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($b['title']); ?></strong>
                                    <?php if ($b['active_issued_count'] > 0): ?>
                                        <div style="font-size: 0.78rem; color: #b45309; margin-top: 2px;">
                                            ⚠️ <?php echo $b['active_issued_count']; ?> copy(s) currently issued
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($b['author']); ?></td>
                                <td>
                                    <span class="badge badge-category">
                                        <?php echo htmlspecialchars($b['category'] ?? 'General'); ?>
                                    </span>
                                </td>
                                <td><code><?php echo htmlspecialchars($b['isbn']); ?></code></td>
                                <td style="text-align: center; font-weight: 600;"><?php echo (int)$b['quantity']; ?></td>
                                <td style="text-align: center;">
                                    <?php if ($b['available_quantity'] > 0): ?>
                                        <span class="badge badge-success"><?php echo (int)$b['available_quantity']; ?> in stock</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">0 left</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: inline-flex; gap: 6px; align-items: center;">
                                        <!-- Edit Action -->
                                        <a href="edit-book.php?id=<?php echo (int)$b['id']; ?>" class="btn btn-outline btn-sm">
                                            Edit
                                        </a>

                                        <!-- Delete Action with CSRF Protection & Confirmation -->
                                        <form action="delete-book.php" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete \'<?php echo addslashes($b['title']); ?>\'?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="id" value="<?php echo (int)$b['id']; ?>">
                                            <button 
                                                type="submit" 
                                                class="btn btn-danger-outline btn-sm"
                                                <?php echo ($b['active_issued_count'] > 0) ? 'title="Book cannot be deleted while copies are issued"' : ''; ?>
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    </div>
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
