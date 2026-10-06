<?php
// Start session for flash messages
session_start();

// Include database connection
require_once 'config/db.php';

// Initialize variables for form state and error handling
$error = '';
$name = '';
$email = '';

// Check if form was submitted via POST
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    // 1. Retrieve and trim form input
    $name             = trim($_POST['name'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // 2. Validate input fields
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required. Please fill in the complete form.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match. Please verify and retype.";
    } else {
        // 3. Check if email already exists using a prepared statement
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);

            if ($stmt->fetch()) {
                $error = "An account with this email already exists. Please log in.";
            } else {
                // 4. Hash the password securely (NEVER store plain-text passwords)
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // 5. Insert new user into database with prepared statement (Always assign 'student' role)
                $insert_stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'student')");
                $insert_stmt->execute([
                    ':name'     => $name,
                    ':email'    => $email,
                    ':password' => $hashed_password
                ]);

                // 6. Set success message and redirect to login page
                $_SESSION['success_message'] = "Registration successful! You can now log in.";
                header("Location: login.php");
                exit();
            }
        } catch (PDOException $e) {
            // Handle any database execution errors gracefully
            $error = "Registration failed due to a system error. Please try again.";
        }
    }
}

// Page title for header
$page_title = "Register - Library Management System";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="main-content">
    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-card">
                
                <div class="auth-header">
                    <h2>Create an Account</h2>
                    <p>Register as a student to borrow library books</p>
                </div>

                <!-- Display error message if validation fails -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <span>⚠️</span>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Registration Form -->
                <form action="register.php" method="POST" autocomplete="off">
                    
                    <!-- Full Name Field -->
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="form-control" 
                            placeholder="e.g. Rahul Sharma" 
                            value="<?php echo htmlspecialchars($name); ?>" 
                            required
                        >
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-control" 
                            placeholder="e.g. rahul@example.com" 
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
                            placeholder="Minimum 6 characters" 
                            required
                        >
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input 
                            type="password" 
                            name="confirm_password" 
                            id="confirm_password" 
                            class="form-control" 
                            placeholder="Re-enter your password" 
                            required
                        >
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-block">Register Account</button>
                </form>

                <!-- Footer link to Login -->
                <div class="auth-footer">
                    Already have an account? <a href="login.php">Log In here</a>
                </div>

            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
