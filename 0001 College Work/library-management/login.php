<?php
// 1. Start a PHP session
session_start();

// If the user is already logged in, redirect directly according to role
if (isset($_SESSION['user_id'])) {
    if (($_SESSION['user_role'] ?? 'student') === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}

// 2. Include database connection
require_once 'config/db.php';

// Initialize variables for form state and error handling
$error = '';
$email = '';

// Check and consume flash success message from registration if present
$success_message = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

// 3. Handle login form submission
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate inputs
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        try {
            // Find user by email and retrieve role using a prepared SQL statement
            $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            // Verify password hash (Never compare plain-text passwords)
            if ($user && password_verify($password, $user['password'])) {
                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);

                // Store user details and role in session (NEVER store password in session)
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_name']  = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role']  = $user['role'] ?? 'student';

                // Role-based redirection:
                // student -> dashboard.php
                // teacher -> dashboard.php
                // admin   -> admin/dashboard.php
                if ($_SESSION['user_role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                // User-friendly error message for invalid credentials
                $error = "Invalid email or password. Please try again.";
            }
        } catch (PDOException $e) {
            $error = "Login failed due to a system error. Please try again.";
        }
    }
}

// Page title for header
$page_title = "Login - Library Management System";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="main-content">
    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-card">
                
                <div class="auth-header">
                    <h2>Welcome Back</h2>
                    <p>Enter your credentials to access your library account</p>
                </div>

                <!-- Display success message (e.g. from registration redirect) -->
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success">
                        <span>✅</span>
                        <span><?php echo htmlspecialchars($success_message); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Display error message if authentication fails -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <span>⚠️</span>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form action="login.php" method="POST" autocomplete="off">
                    
                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-control" 
                            placeholder="e.g. student@example.com" 
                            value="<?php echo htmlspecialchars($email); ?>" 
                            required
                        >
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-control" 
                            placeholder="Enter your password" 
                            required
                        >
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-block">Log In</button>
                </form>

                <!-- Footer link to Register -->
                <div class="auth-footer">
                    Don't have an account? <a href="register.php">Create an Account</a>
                </div>

            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
