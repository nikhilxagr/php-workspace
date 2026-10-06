<?php
// ============================================
// Edit Book - Admin Panel
// File: admin/edit-book.php
// ============================================

require_once '../auth/auth_check.php';
require_once '../auth/role_check.php';

// Strict server-side admin role check
require_role(['admin']);

require_once '../config/db.php';
$base_path = '../';

$error = '';
$book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($book_id <= 0) {
    $_SESSION['error_message'] = "Invalid book selection.";
    header("Location: books.php");
    exit();
}

// 1. Fetch current book details and count of actively issued copies
try {
    $stmt = $pdo->prepare("
        SELECT 
            b.*,
            (SELECT COUNT(*) FROM issued_books ib WHERE ib.book_id = b.id AND ib.status = 'issued') AS active_issued
        FROM books b 
        WHERE b.id = :id 
        LIMIT 1
    ");
    $stmt->execute([':id' => $book_id]);
    $book = $stmt->fetch();

    if (!$book) {
        $_SESSION['error_message'] = "Book not found.";
        header("Location: books.php");
        exit();
    }

    $active_issued = (int)$book['active_issued'];

} catch (PDOException $e) {
    error_log("Edit book query error: " . $e->getMessage());
    $_SESSION['error_message'] = "Database error while fetching book details.";
    header("Location: books.php");
    exit();
}

// 2. Handle update submission
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $title        = trim($_POST['title'] ?? '');
    $author       = trim($_POST['author'] ?? '');
    $category     = trim($_POST['category'] ?? '');
    $isbn         = trim($_POST['isbn'] ?? '');
    $new_quantity = (int)($_POST['quantity'] ?? 0);

    // Validation
    if (empty($title) || empty($author) || empty($category) || empty($isbn)) {
        $error = "All fields are required.";
    } elseif ($new_quantity < 1) {
        $error = "Quantity must be at least 1 copy.";
    } elseif ($new_quantity < $active_issued) {
        // Prevent setting total quantity lower than currently borrowed copies
        $error = "Cannot reduce total quantity to {$new_quantity}. Currently {$active_issued} copy(s) are issued to students/teachers.";
    } else {
        try {
            // Check for duplicate ISBN on other books
            $stmt_isbn = $pdo->prepare("SELECT id FROM books WHERE isbn = :isbn AND id != :id LIMIT 1");
            $stmt_isbn->execute([':isbn' => $isbn, ':id' => $book_id]);

            if ($stmt_isbn->fetch()) {
                $error = "Another book is already registered with this ISBN.";
            } else {
                // Calculate correct available quantity: new_quantity minus currently active issues
                $new_available = $new_quantity - $active_issued;

                $stmt_update = $pdo->prepare("
                    UPDATE books 
                    SET title = :title,
                        author = :author,
                        category = :category,
                        isbn = :isbn,
                        quantity = :quantity,
                        available_quantity = :available_quantity
                    WHERE id = :id
                ");
                $stmt_update->execute([
                    ':title'              => $title,
                    ':author'             => $author,
                    ':category'           => $category,
                    ':isbn'               => $isbn,
                    ':quantity'           => $new_quantity,
                    ':available_quantity' => $new_available,
                    ':id'                 => $book_id
                ]);

                $_SESSION['success_message'] = "Book details for \"" . $title . "\" updated successfully!";
                header("Location: books.php");
                exit();
            }
        } catch (PDOException $e) {
            error_log("Book update error: " . $e->getMessage());
            $error = "Failed to update book due to a system error.";
        }
    }
} else {
    // Populate form with current values
    $title        = $book['title'];
    $author       = $book['author'];
    $category     = $book['category'];
    $isbn         = $book['isbn'];
    $new_quantity = $book['quantity'];
}

$page_title = "Edit Book - Admin Panel";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="main-content">
    <div class="container">
        
        <div class="page-header-flex">
            <div>
                <h1>✏️ Edit Book Details</h1>
                <p>Modify book metadata and inventory quantities.</p>
            </div>
            <div>
                <a href="books.php" class="btn btn-outline">Back to Manage Books</a>
            </div>
        </div>

        <div class="auth-wrapper" style="padding-top: 0;">
            <div class="auth-card" style="max-width: 560px;">

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <span>⚠️</span>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($active_issued > 0): ?>
                    <div class="alert alert-success" style="background-color: #fefce8; color: #854d0e; border-color: #fef08a;">
                        <span>ℹ️</span>
                        <span><strong><?php echo $active_issued; ?> copy(s)</strong> are currently borrowed. Available stock will be auto-calculated.</span>
                    </div>
                <?php endif; ?>

                <form action="edit-book.php?id=<?php echo $book_id; ?>" method="POST" autocomplete="off">
                    
                    <div class="form-group">
                        <label for="title">Book Title</label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($title); ?>" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="author">Author(s)</label>
                        <input 
                            type="text" 
                            name="author" 
                            id="author" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($author); ?>" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <input 
                            type="text" 
                            name="category" 
                            id="category" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($category); ?>" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="isbn">ISBN Number</label>
                        <input 
                            type="text" 
                            name="isbn" 
                            id="isbn" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($isbn); ?>" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="quantity">Total Inventory Copies</label>
                        <input 
                            type="number" 
                            name="quantity" 
                            id="quantity" 
                            class="form-control" 
                            min="<?php echo max(1, $active_issued); ?>" 
                            value="<?php echo (int)$new_quantity; ?>" 
                            required
                        >
                        <small style="color: var(--text-muted); font-size: 0.8rem;">
                            Minimum allowed is <?php echo max(1, $active_issued); ?> based on active issues.
                        </small>
                    </div>

                    <div style="margin-top: 24px;">
                        <button type="submit" class="btn btn-primary btn-block">
                            Save Changes
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</main>

<?php include '../includes/footer.php'; ?>
