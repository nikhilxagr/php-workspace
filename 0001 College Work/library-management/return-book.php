<?php
// ============================================
// Return Book Module
// File: return-book.php
// ============================================

// 1. Enforce authentication and role checks (Students and Teachers only)
require_once 'auth/auth_check.php';
require_once 'auth/role_check.php';

require_role(['student', 'teacher']);

// 2. Include database connection
require_once 'config/db.php';

// Always get user_id strictly from session - NEVER trust user_id from the request
$user_id = $_SESSION['user_id'];

// Enforce HTTP POST method for state-modifying return action (prevents GET CSRF/prefetch)
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header("Location: my-books.php");
    exit();
}

// Retrieve and validate issue_id from POST
$issue_id = isset($_POST['issue_id']) ? (int)$_POST['issue_id'] : 0;

// Validate issue_id
if ($issue_id <= 0) {
    $_SESSION['error_message'] = "Invalid book return request.";
    header("Location: my-books.php");
    exit();
}

try {
    // 3. Begin MySQL Transaction for safe return processing
    $pdo->beginTransaction();

    // 4. Verify the record belongs to the logged-in user and lock the row
    $stmt_check = $pdo->prepare("
        SELECT ib.id, ib.book_id, ib.status, b.title 
        FROM issued_books ib
        JOIN books b ON ib.book_id = b.id
        WHERE ib.id = :issue_id AND ib.user_id = :user_id 
        FOR UPDATE
    ");
    $stmt_check->execute([
        ':issue_id' => $issue_id,
        ':user_id'  => $user_id
    ]);
    $issued_record = $stmt_check->fetch();

    // Verify record exists and belongs to this student
    if (!$issued_record) {
        $pdo->rollBack();
        $_SESSION['error_message'] = "Issued record not found or does not belong to your account.";
        header("Location: my-books.php");
        exit();
    }

    // Verify status is currently 'issued' (Prevents a book from being returned twice)
    if ($issued_record['status'] !== 'issued') {
        $pdo->rollBack();
        $_SESSION['error_message'] = "This book has already been returned.";
        header("Location: my-books.php");
        exit();
    }

    $book_id     = (int)$issued_record['book_id'];
    $book_title  = $issued_record['title'];
    $return_date = date('Y-m-d');

    // 5. Update status to 'returned' and set return_date to current date
    $stmt_update_issue = $pdo->prepare("
        UPDATE issued_books 
        SET status = 'returned', return_date = :return_date 
        WHERE id = :issue_id AND user_id = :user_id AND status = 'issued'
    ");
    $stmt_update_issue->execute([
        ':return_date' => $return_date,
        ':issue_id'    => $issue_id,
        ':user_id'     => $user_id
    ]);

    // 6. Increase books.available_quantity by 1
    $stmt_update_book = $pdo->prepare("
        UPDATE books 
        SET available_quantity = available_quantity + 1 
        WHERE id = :book_id
    ");
    $stmt_update_book->execute([':book_id' => $book_id]);

    // Commit the transaction
    $pdo->commit();

    // 7. Redirect to my-books.php with success message
    $_SESSION['success_message'] = "Book \"" . $book_title . "\" has been returned successfully!";
    header("Location: my-books.php");
    exit();

} catch (PDOException $e) {
    // Rollback transaction if any query fails
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['error_message'] = "An error occurred while returning the book. Please try again.";
    header("Location: my-books.php");
    exit();
}
?>
