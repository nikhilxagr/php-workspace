<?php
// ============================================
// Delete Book Action - Admin Panel
// File: admin/delete-book.php
// ============================================

require_once '../auth/auth_check.php';
require_once '../auth/role_check.php';

// Strict server-side admin role check
require_role(['admin']);

require_once '../config/db.php';

// 1. Strictly enforce HTTP POST method
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    $_SESSION['error_message'] = "Invalid request method. Delete must be performed via POST.";
    header("Location: books.php");
    exit();
}

// 2. Validate CSRF Protection Token
$submitted_token = $_POST['csrf_token'] ?? '';
if (empty($submitted_token) || !hash_equals($_SESSION['csrf_token'] ?? '', $submitted_token)) {
    $_SESSION['error_message'] = "Security check failed (Invalid CSRF Token). Action rejected.";
    header("Location: books.php");
    exit();
}

// 3. Validate Book ID
$book_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($book_id <= 0) {
    $_SESSION['error_message'] = "Invalid book ID.";
    header("Location: books.php");
    exit();
}

try {
    // 4. Validate book exists
    $stmt = $pdo->prepare("SELECT id, title FROM books WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $book_id]);
    $book = $stmt->fetch();

    if (!$book) {
        $_SESSION['error_message'] = "The selected book was not found.";
        header("Location: books.php");
        exit();
    }

    // 5. Check if book has ACTIVE issued copies
    $stmt_active = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE book_id = :id AND status = 'issued'");
    $stmt_active->execute([':id' => $book_id]);
    $active_copies = (int)$stmt_active->fetchColumn();

    if ($active_copies > 0) {
        $_SESSION['error_message'] = "Cannot delete \"" . $book['title'] . "\". There are {$active_copies} active copy(s) currently issued to users.";
        header("Location: books.php");
        exit();
    }

    // 6. Safe Deletion inside a Transaction
    $pdo->beginTransaction();

    // Clean up returned history records if any exist
    $stmt_clean = $pdo->prepare("DELETE FROM issued_books WHERE book_id = :id AND status = 'returned'");
    $stmt_clean->execute([':id' => $book_id]);

    // Delete the book record
    $stmt_del = $pdo->prepare("DELETE FROM books WHERE id = :id");
    $stmt_del->execute([':id' => $book_id]);

    $pdo->commit();

    $_SESSION['success_message'] = "Book \"" . $book['title'] . "\" was deleted successfully.";
    header("Location: books.php");
    exit();

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Delete book error: " . $e->getMessage());
    $_SESSION['error_message'] = "Failed to delete the book due to a database constraint or error.";
    header("Location: books.php");
    exit();
}
?>
