<?php
// Start session
session_start();

// Database Configuration
$host     = '127.0.0.1';
$port     = 3306;
$dbname   = 'studentdb';
$db_user  = 'root';
$db_pass  = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Initialize message variable
$message = '';

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("Location: index.php");
    exit();
}

// Handle Registration
if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($name && $email && $password) {
        $stmt = $pdo->prepare("SELECT email FROM tbl_register WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $message = "Email already registered!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO tbl_register (name, email, password, timestamp) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$name, $email, $hashed_password]);
            $message = "Registration successful! You can now log in.";
        }
    } else {
        $message = "All fields are required!";
    }
}

// Handle Login
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT * FROM tbl_register WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ];
            // No need to show message — we'll redirect to logged-in state
        } else {
            $message = "Invalid email or password!";
        }
    } else {
        $message = "Please enter both email and password.";
    }
}

// If user is logged in, show welcome screen
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    echo "
    <html>
    <head>
        <title>Welcome</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 100px; background-color: #f4f4f4; }
            .container { max-width: 400px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            h2 { color: #4CAF50; }
            .btn { padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 20px; }
            .btn:hover { background-color: #d32f2f; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Welcome, " . htmlspecialchars($user['name']) . "!</h2>
            <p>You have successfully logged in.</p>
            <p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>
            <a href='index.php?logout' class='btn'>Logout</a>
        </div>
    </body>
    </html>";
    exit(); // Stop further execution if logged in
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login / Register</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; background-color: #f4f4f4; }
        .container { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%; background-color: #4CAF50; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;
        }
        input[type="submit"]:hover { background-color: #45a049; }
        .form-group { margin-bottom: 15px; }
        .message { text-align: center; margin: 10px 0; color: #d9534f; }
        .toggle-form { text-align: center; margin-top: 20px; }
        .toggle-form a { color: #337ab7; text-decoration: none; }
        .toggle-form a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2 id="form-title">Register</h2>

        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Registration Form -->
        <form method="POST" id="register-form">
            <div class="form-group">
                <input type="text" name="name" placeholder="Full Name" />
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" />
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" />
            </div>
            <input type="submit" name="register" value="Register" />
        </form>

        <!-- Login Form (Hidden by default) -->
        <form method="POST" id="login-form" style="display:none;">
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" />
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" />
            </div>
            <input type="submit" name="login" value="Login" />
        </form>

        <div class="toggle-form">
            <a href="#" id="toggle-link">Already have an account? Login</a>
        </div>
    </div>

    <script>
        const registerForm = document.getElementById('register-form');
        const loginForm = document.getElementById('login-form');
        const formTitle = document.getElementById('form-title');
        const toggleLink = document.getElementById('toggle-link');

        toggleLink.addEventListener('click', function(e) {
            e.preventDefault();
            if (registerForm.style.display === 'none') {
                registerForm.style.display = 'block';
                loginForm.style.display = 'none';
                formTitle.textContent = 'Register';
                toggleLink.textContent = 'Already have an account? Login';
            } else {
                registerForm.style.display = 'none';
                loginForm.style.display = 'block';
                formTitle.textContent = 'Login';
                toggleLink.textContent = 'Need an account? Register';
            }
        });
    </script>
</body>
</html>