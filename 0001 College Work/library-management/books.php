<?php
// ============================================
// Browse Books Catalog
// File: books.php
// ============================================

// 1. Enforce authentication and role checks
require_once 'auth/auth_check.php';
require_once 'auth/role_check.php';

// Admins manage catalog in the admin panel
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header("Location: admin/books.php");
    exit();
}

require_role(['student', 'teacher']);

// 2. Include database connection
require_once 'config/db.php';

// Retrieve search keyword and category filter (if any)
$search   = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

// Flash messages (for return from issue-book.php)
$success_message = $_SESSION['success_message'] ?? '';
$error_message   = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

try {
    // 3. Fetch unique categories for dropdown filter
    $cat_stmt = $pdo->query("SELECT DISTINCT category FROM books WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
    $categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);

    // 4. Build prepared query for books
    $sql = "SELECT id, title, author, category, isbn, quantity, available_quantity FROM books WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $sql .= " AND (title LIKE :search OR author LIKE :search OR isbn LIKE :search)";
        $params[':search'] = "%" . $search . "%";
    }

    if (!empty($category)) {
        $sql .= " AND category = :category";
        $params[':category'] = $category;
    }

    $sql .= " ORDER BY title ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $books = $stmt->fetchAll();

} catch (PDOException $e) {
    $books = [];
    $categories = [];
    $error_message = "Unable to retrieve book catalog. Please try again.";
}

// Page title for header
$page_title = "Browse Books - Library Management System";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="main-content">
    <div class="container">
        
        <!-- Page Header -->
        <div class="page-header-flex">
            <div>
                <h1>📚 Library Books Catalog</h1>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 4px;">
                    Browse available textbooks and borrow books for your coursework.
                </p>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-outline">Back to Dashboard</a>
            </div>
        </div>

        <!-- Notification Alerts -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <span>✅</span>
                <span><?php echo htmlspecialchars($success_message); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <span>⚠️</span>
                <span><?php echo htmlspecialchars($error_message); ?></span>
            </div>
        <?php endif; ?>

        <!-- Search & Filter Form -->
        <form method="GET" action="books.php" class="search-filter-box">
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Search by title, author, or ISBN..." 
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <select name="category" class="form-control" style="max-width: 200px;">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($category === $cat) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">Search</button>

            <?php if (!empty($search) || !empty($category)): ?>
                <a href="books.php" class="btn btn-outline" style="padding: 10px 18px;">Reset</a>
            <?php endif; ?>
        </form>

        <!-- Books Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>ISBN</th>
                        <th style="text-align: center;">Total</th>
                        <th style="text-align: center;">Available</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($books)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                No books found matching your search criteria.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($book['title']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td>
                                    <span class="badge badge-category">
                                        <?php echo htmlspecialchars($book['category'] ?? 'General'); ?>
                                    </span>
                                </td>
                                <td>
                                    <code style="font-size: 0.88rem; color: #475569;"><?php echo htmlspecialchars($book['isbn']); ?></code>
                                </td>
                                <td style="text-align: center;">
                                    <?php echo (int)$book['quantity']; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($book['available_quantity'] > 0): ?>
                                        <span class="badge badge-success">
                                            <?php echo (int)$book['available_quantity']; ?> in stock
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">0 left</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($book['available_quantity'] > 0): ?>
                                        <a href="issue-book.php?id=<?php echo urlencode($book['id']); ?>" class="btn btn-primary btn-sm">
                                            Issue Book
                                        </a>
                                    <?php else: ?>
                                        <span class="badge badge-danger" style="padding: 6px 12px;">Not Available</span>
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
