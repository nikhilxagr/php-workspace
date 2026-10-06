<?php
// ============================================
// Issue Book Module
// File: issue-book.php
// ============================================

// 1. Enforce authentication and role checks (Students and Teachers only)
require_once 'auth/auth_check.php';
require_once 'auth/role_check.php';

require_role(['student', 'teacher']);

// 2. Include database connection
require_once 'config/db.php';

// Always get user_id from the session - never from URL or form
$user_id = $_SESSION['user_id'];

// Retrieve book_id from POST or GET
$book_id = 0;
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
} else {
    $book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
}

// Validate book ID
if ($book_id <= 0) {
    $_SESSION['error_message'] = "Invalid book selection. Please select a valid book.";
    header("Location: books.php");
    exit();
}

// -------------------------------------------------------------
// POST Request: Process the actual issue operation with a Transaction
// -------------------------------------------------------------
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    try {
        // Begin database transaction for safe execution
        $pdo->beginTransaction();

        // 1. Fetch book and lock the row to avoid race conditions
        $stmt_book = $pdo->prepare("SELECT id, title, available_quantity FROM books WHERE id = :id FOR UPDATE");
        $stmt_book->execute([':id' => $book_id]);
        $book = $stmt_book->fetch();

        // Check if book exists
        if (!$book) {
            $pdo->rollBack();
            $_SESSION['error_message'] = "The requested book was not found in the catalog.";
            header("Location: books.php");
            exit();
        }

        // Check availability
        if ($book['available_quantity'] <= 0) {
            $pdo->rollBack();
            $_SESSION['error_message'] = "Sorry, \"" . $book['title'] . "\" is currently out of stock.";
            header("Location: books.php");
            exit();
        }

        // 2. Prevent issuing the same book multiple times if already active
        $stmt_check = $pdo->prepare("SELECT id FROM issued_books WHERE user_id = :user_id AND book_id = :book_id AND status = 'issued' LIMIT 1");
        $stmt_check->execute([
            ':user_id' => $user_id,
            ':book_id' => $book_id
        ]);

        if ($stmt_check->fetch()) {
            $pdo->rollBack();
            $_SESSION['error_message'] = "You already have an active borrowed copy of \"" . $book['title'] . "\".";
            header("Location: my-books.php");
            exit();
        }

        // 3. Insert record into issued_books table
        $issue_date = date('Y-m-d');
        $stmt_insert = $pdo->prepare("INSERT INTO issued_books (user_id, book_id, issue_date, status) VALUES (:user_id, :book_id, :issue_date, 'issued')");
        $stmt_insert->execute([
            ':user_id'    => $user_id,
            ':book_id'    => $book_id,
            ':issue_date' => $issue_date
        ]);

        // 4. Decrease available_quantity in books table by 1
        $stmt_update = $pdo->prepare("UPDATE books SET available_quantity = available_quantity - 1 WHERE id = :id AND available_quantity > 0");
        $stmt_update->execute([':id' => $book_id]);

        // Commit transaction
        $pdo->commit();

        // Set success message and redirect to my-books.php
        $_SESSION['success_message'] = "Book \"" . $book['title'] . "\" has been issued successfully!";
        header("Location: my-books.php");
        exit();

    } catch (PDOException $e) {
        // Rollback transaction on any failure
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $_SESSION['error_message'] = "An error occurred while issuing the book. Please try again.";
        header("Location: books.php");
        exit();
    }
}

// -------------------------------------------------------------
// GET Request: Display confirmation preview before issuing
// -------------------------------------------------------------
try {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $book_id]);
    $book = $stmt->fetch();

    if (!$book) {
        $_SESSION['error_message'] = "The requested book was not found.";
        header("Location: books.php");
        exit();
    }

    if ($book['available_quantity'] <= 0) {
        $_SESSION['error_message'] = "Sorry, \"" . $book['title'] . "\" is currently out of stock.";
        header("Location: books.php");
        exit();
    }

    // Check if user already borrowed this book
    $stmt_check = $pdo->prepare("SELECT id FROM issued_books WHERE user_id = :user_id AND book_id = :book_id AND status = 'issued' LIMIT 1");
    $stmt_check->execute([
        ':user_id' => $user_id,
        ':book_id' => $book_id
    ]);

    if ($stmt_check->fetch()) {
        $_SESSION['error_message'] = "You already have an active borrowed copy of \"" . $book['title'] . "\".";
        header("Location: my-books.php");
        exit();
    }

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Database query failed.";
    header("Location: books.php");
    exit();
}

$page_title = "Confirm Issue - " . htmlspecialchars($book['title']);
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="main-content">
    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-card" style="max-width: 540px;">
                
                <div class="auth-header">
                    <h2>📖 Confirm Book Issue</h2>
                    <p>Review textbook details before confirming your borrow request</p>
                </div>

                <!-- Book Information Box -->
                <div style="background-color: var(--bg-light); border: 1px solid var(--border-color); border-radius: var(--radius); padding: 20px; margin-bottom: 24px;">
                    <div style="margin-bottom: 10px;">
                        <span style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Book Title</span>
                        <h3 style="font-size: 1.15rem; color: var(--primary); margin-top: 2px;">
                            <?php echo htmlspecialchars($book['title']); ?>
                        </h3>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 10px;">
                        <div>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">Author</span>
                            <p style="font-weight: 500;"><?php echo htmlspecialchars($book['author']); ?></p>
                        </div>
                        <div>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">Category</span>
                            <p><span class="badge badge-category"><?php echo htmlspecialchars($book['category']); ?></span></p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">ISBN</span>
                            <p><code><?php echo htmlspecialchars($book['isbn']); ?></code></p>
                        </div>
                        <div>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">Available Copies</span>
                            <p><span class="badge badge-success"><?php echo (int)$book['available_quantity']; ?> in stock</span></p>
                        </div>
                    </div>

                    <div style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed var(--border-color); font-size: 0.88rem; color: var(--text-muted);">
                        <span>📅 <strong>Issue Date:</strong> <?php echo date('d M Y'); ?></span>
                    </div>
                </div>

                <!-- Confirmation Form -->
                <form action="issue-book.php" method="POST">
                    <!-- Pass book ID securely -->
                    <input type="hidden" name="book_id" value="<?php echo (int)$book['id']; ?>">
                    
                    <button type="submit" class="btn btn-primary btn-block" style="margin-bottom: 10px;">
                        Confirm & Issue Book
                    </button>
                    <a href="books.php" class="btn btn-outline btn-block">
                        Cancel
                    </a>
                </form>

            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
