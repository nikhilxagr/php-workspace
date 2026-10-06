<?php
// ============================================
// Student Profile Management
// File: profile.php
// ============================================

// 1. Enforce authentication and role checks (All authenticated roles)
require_once 'auth/auth_check.php';
require_once 'auth/role_check.php';

require_role(['student', 'teacher', 'admin']);

// 2. Include database connection
require_once 'config/db.php';

// Retrieve logged-in student's ID strictly from session
$user_id = $_SESSION['user_id'];

$error   = '';
$success = '';

// 3. Handle Profile Update (Update Name)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $new_name = trim($_POST['name'] ?? '');

    // Validate name input
    if (empty($new_name)) {
        $error = "Name cannot be empty. Please enter your name.";
    } elseif (strlen($new_name) < 2) {
        $error = "Name must be at least 2 characters long.";
    } elseif (strlen($new_name) > 100) {
        $error = "Name must not exceed 100 characters.";
    } else {
        try {
            // Update user's name in database using prepared statement
            // User ID is taken strictly from the session to prevent unauthorized modifications
            $update_stmt = $pdo->prepare("UPDATE users SET name = :name WHERE id = :id");
            $update_stmt->execute([
                ':name' => $new_name,
                ':id'   => $user_id
            ]);

            // Update session variable so navbar and dashboard update immediately
            $_SESSION['user_name'] = $new_name;

            $success = "Your profile name has been updated successfully!";

        } catch (PDOException $e) {
            $error = "Failed to update profile. Please try again.";
        }
    }
}

// 4. Retrieve current user details from users table using prepared statement
try {
    $stmt = $pdo->prepare("SELECT id, name, email, role, created_at FROM users WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        // If user record no longer exists, force logout
        header("Location: logout.php");
        exit();
    }

    // Also get count of active issued books for this student
    $stmt_issued = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE user_id = :id AND status = 'issued'");
    $stmt_issued->execute([':id' => $user_id]);
    $active_issued_count = (int)$stmt_issued->fetchColumn();

} catch (PDOException $e) {
    // For security, never expose raw database exceptions to users
    error_log("Profile query error: " . $e->getMessage());
    $error = "Unable to load profile data. Please try again later.";
    $user = [
        'id'         => $user_id,
        'name'       => $_SESSION['user_name'] ?? 'Student',
        'email'      => 'Unavailable',
        'created_at' => date('Y-m-d')
    ];
    $active_issued_count = 0;
}

$page_title = "My Profile - Library Management System";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="main-content">
    <div class="container">
        
        <!-- Page Header -->
        <div class="page-header-flex">
            <div>
                <h1>👤 Student Profile</h1>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 4px;">
                    View your library membership details and manage your account information.
                </p>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-outline">Back to Dashboard</a>
            </div>
        </div>

        <!-- Success Notification -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <span>✅</span>
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
        <?php endif; ?>

        <!-- Error Notification -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <span>⚠️</span>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
            
            <!-- Card 1: Account Information (Read-Only Details) -->
            <div class="feature-card" style="box-shadow: var(--shadow-sm);">
                <div class="feature-icon">🎓</div>
                <h2 style="font-size: 1.25rem; margin-bottom: 16px; color: var(--text-color);">Membership Details</h2>

                <div style="margin-bottom: 14px;">
                    <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">User ID</span>
                    <p style="font-size: 1.05rem; font-weight: 700; color: var(--primary); margin-top: 2px;">
                        #LIB-<?php echo str_pad($user['id'], 4, '0', STR_PAD_LEFT); ?>
                    </p>
                    <small style="color: var(--text-muted); font-size: 0.8rem;">(System generated - cannot be modified)</small>
                </div>

                <div style="margin-bottom: 14px;">
                    <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Account Role</span>
                    <p style="margin-top: 2px;">
                        <span class="badge badge-category" style="text-transform: capitalize; font-size: 0.85rem; padding: 4px 12px;">
                            <?php echo htmlspecialchars($user['role'] ?? 'student'); ?>
                        </span>
                    </p>
                </div>

                <div style="margin-bottom: 14px;">
                    <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Email Address</span>
                    <p style="font-size: 1rem; color: var(--text-color); margin-top: 2px;">
                        <?php echo htmlspecialchars($user['email']); ?>
                    </p>
                </div>

                <div style="margin-bottom: 14px;">
                    <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Account Created</span>
                    <p style="font-size: 0.95rem; color: var(--text-color); margin-top: 2px;">
                        📅 <?php echo date('d F Y, h:i A', strtotime($user['created_at'])); ?>
                    </p>
                </div>

                <div style="padding-top: 12px; border-top: 1px solid var(--border-color);">
                    <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Currently Borrowed Books</span>
                    <p style="margin-top: 4px;">
                        <span class="badge badge-success"><?php echo $active_issued_count; ?> active books</span>
                        <a href="my-books.php" style="margin-left: 10px; font-size: 0.88rem; color: var(--primary); font-weight: 600;">View books &rarr;</a>
                    </p>
                </div>
            </div>

            <!-- Card 2: Edit Profile (Update Name) -->
            <div class="feature-card" style="box-shadow: var(--shadow-sm);">
                <div class="feature-icon">✏️</div>
                <h2 style="font-size: 1.25rem; margin-bottom: 6px; color: var(--text-color);">Update Profile</h2>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px;">
                    You can update your display name below.
                </p>

                <!-- Update Name Form -->
                <form action="profile.php" method="POST" autocomplete="off">
                    
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($user['name']); ?>" 
                            placeholder="Enter your full name" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email_disabled">Email Address (Registered)</label>
                        <input 
                            type="email" 
                            id="email_disabled" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($user['email']); ?>" 
                            disabled 
                            style="background-color: var(--bg-light); cursor: not-allowed;"
                        >
                        <small style="color: var(--text-muted); font-size: 0.8rem;">
                            Email address is permanently linked to your student account.
                        </small>
                    </div>

                    <div style="margin-top: 24px;">
                        <button type="submit" class="btn btn-primary" style="padding: 10px 22px;">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</main>

<?php include 'includes/footer.php'; ?>
