<?php
// ============================================
// Add New Book - Admin Panel
// File: admin/add-book.php
// ============================================

require_once '../auth/auth_check.php';
require_once '../auth/role_check.php';

// Strict server-side admin role check
require_role(['admin']);

require_once '../config/db.php';
$base_path = '../';

$error    = '';
$title    = '';
$author   = '';
$category = '';
$isbn     = '';
$quantity = 1;

// Handle form submission
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $title    = trim($_POST['title'] ?? '');
    $author   = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $isbn     = trim($_POST['isbn'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 0);

    // 1. Validation
    if (empty($title) || empty($author) || empty($category) || empty($isbn)) {
        $error = "All fields are required. Please fill out the entire form.";
    } elseif ($quantity < 1) {
        $error = "Quantity must be at least 1 copy.";
    } else {
        try {
            // 2. Prevent duplicate ISBN values
            $stmt_check = $pdo->prepare("SELECT id FROM books WHERE isbn = :isbn LIMIT 1");
            $stmt_check->execute([':isbn' => $isbn]);

            if ($stmt_check->fetch()) {
                $error = "A book with this ISBN already exists in the catalog.";
            } else {
                // 3. Insert new book setting available_quantity = quantity
                $stmt_insert = $pdo->prepare("
                    INSERT INTO books (title, author, category, isbn, quantity, available_quantity) 
                    VALUES (:title, :author, :category, :isbn, :quantity, :available_quantity)
                ");
                $stmt_insert->execute([
                    ':title'              => $title,
                    ':author'             => $author,
                    ':category'           => $category,
                    ':isbn'               => $isbn,
                    ':quantity'           => $quantity,
                    ':available_quantity' => $quantity
                ]);

                // 4. Set success message and redirect to admin/books.php
                $_SESSION['success_message'] = "Book \"" . $title . "\" has been added successfully!";
                header("Location: books.php");
                exit();
            }
        } catch (PDOException $e) {
            error_log("Add book error: " . $e->getMessage());
            $error = "Failed to add book due to a database error.";
        }
    }
}

$page_title = "Add Book - Admin Panel";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="main-content">
    <div class="container">
        
        <div class="page-header-flex">
            <div>
                <h1>➕ Add New Book to Library</h1>
                <p>Register a new textbook title into the library inventory.</p>
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

                <form action="add-book.php" method="POST" autocomplete="off">
                    
                    <div class="form-group">
                        <label for="title">Book Title</label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            class="form-control" 
                            placeholder="e.g. Data Structures and Algorithms" 
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
                            placeholder="e.g. Robert Lafore" 
                            value="<?php echo htmlspecialchars($author); ?>" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="category">Category / Subject</label>
                        <input 
                            type="text" 
                            name="category" 
                            id="category" 
                            class="form-control" 
                            placeholder="e.g. Computer Science, Mathematics" 
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
                            placeholder="e.g. 978-0131103627" 
                            value="<?php echo htmlspecialchars($isbn); ?>" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="quantity">Initial Quantity (Physical Copies)</label>
                        <input 
                            type="number" 
                            name="quantity" 
                            id="quantity" 
                            class="form-control" 
                            min="1" 
                            value="<?php echo (int)$quantity; ?>" 
                            required
                        >
                        <small style="color: var(--text-muted); font-size: 0.8rem;">
                            Available quantity will initially match this total.
                        </small>
                    </div>

                    <div style="margin-top: 24px;">
                        <button type="submit" class="btn btn-primary btn-block">
                            Save & Add Book
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</main>

<?php include '../includes/footer.php'; ?>
