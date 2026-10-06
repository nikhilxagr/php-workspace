<?php
// ============================================
// Create Teacher Account - Admin Panel
// File: admin/add-teacher.php
// ============================================

require_once '../auth/auth_check.php';
require_once '../auth/role_check.php';

// Strict server-side admin role check
require_role(['admin']);

require_once '../config/db.php';
$base_path = '../';

// Initialize CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error_message = '';
$name = '';
$email = '';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF verification
    $submitted_token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $submitted_token)) {
        $error_message = "Invalid security token. Please try submitting again.";
    } else {
        $name             = trim($_POST['name'] ?? '');
        $email            = trim($_POST['email'] ?? '');
        $password         = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            $error_message = "All fields are required.";
        } elseif (strlen($name) < 2) {
            $error_message = "Teacher name must be at least 2 characters long.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Please enter a valid email address.";
        } elseif (strlen($password) < 6) {
            $error_message = "Password must be at least 6 characters long.";
        } elseif ($password !== $confirm_password) {
            $error_message = "Passwords do not match.";
        } else {
            try {
                // Check for duplicate email
                $stmt_check = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
                $stmt_check->execute([':email' => $email]);

                if ($stmt_check->fetch()) {
                    $error_message = "An account with email '" . htmlspecialchars($email) . "' already exists.";
                } else {
                    // Hash password securely
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Insert teacher account - role is hardcoded to 'teacher' on the server
                    $stmt_insert = $pdo->prepare("
                        INSERT INTO users (name, email, password, role) 
                        VALUES (:name, :email, :password, 'teacher')
                    ");

                    $stmt_insert->execute([
                        ':name'     => $name,
                        ':email'    => $email,
                        ':password' => $hashed_password
                    ]);

                    // Regenerate CSRF token after successful state change
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                    $_SESSION['success_message'] = "Teacher account for '" . htmlspecialchars($name) . "' has been created successfully.";
                    header("Location: teacher-details.php");
                    exit();
                }
            } catch (PDOException $e) {
                error_log("Add teacher error: " . $e->getMessage());
                $error_message = "A database error occurred while creating the account. Please try again.";
            }
        }
    }
}

$page_title = "Add Teacher Account - Admin Panel";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="main-content">
    <div class="container" style="max-width: 620px;">

        <!-- Header -->
        <div class="page-header-flex">
            <div>
                <h1>➕ Register Teacher Account</h1>
                <p>Create a verified faculty account with instructor library privileges.</p>
            </div>
            <div>
                <a href="teacher-details.php" class="btn btn-outline">← Back to Teachers</a>
            </div>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Teacher Registration Form -->
        <div class="card">
            <form action="add-teacher.php" method="POST" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                <div class="form-group">
                    <label for="name">Teacher Full Name <span style="color: var(--error-text);">*</span></label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-control" 
                        placeholder="e.g. Prof. Alan Turing" 
                        value="<?php echo htmlspecialchars($name); ?>" 
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="email">Teacher Email Address <span style="color: var(--error-text);">*</span></label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        placeholder="e.g. turing@college.edu" 
                        value="<?php echo htmlspecialchars($email); ?>" 
                        required
                    >
                    <small style="color: var(--text-muted); font-size: 0.82rem; margin-top: 4px; display: block;">
                        Used by the teacher to log in. Must be unique.
                    </small>
                </div>

                <div class="form-group">
                    <label for="password">Initial Password <span style="color: var(--error-text);">*</span></label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="Minimum 6 characters" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password <span style="color: var(--error-text);">*</span></label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        class="form-control" 
                        placeholder="Re-enter password" 
                        required
                    >
                </div>

                <div style="background-color: var(--bg-subtle); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 12px 14px; margin-bottom: 20px;">
                    <span style="font-size: 0.86rem; color: var(--text-muted);">
                        🔒 <strong>Role Assignment:</strong> This account will be created with <strong>teacher</strong> role privileges. Normal student registration cannot grant faculty rights.
                    </span>
                </div>

                <div style="display: flex; gap: 12px; align-items: center;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                        Create Teacher Account
                    </button>
                    <a href="teacher-details.php" class="btn btn-outline" style="padding: 10px 18px;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</main>

<?php include '../includes/footer.php'; ?>
