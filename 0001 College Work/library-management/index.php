<?php
// Set page title
$page_title = "Welcome - Library Management System";

// Include header & navigation bar
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="main-content">
    <div class="container">
        
        <!-- Hero Section -->
        <section class="hero">
            <span class="hero-tag">College Library Portal</span>
            <h1>Manage & Discover Library Books Easily</h1>
            <p>Welcome to our simple Library Management System. Browse available textbooks, view issuance details, and keep track of your reading list.</p>
            <div class="hero-actions">
                <a href="books.php" class="btn btn-primary">Browse Books</a>
                <a href="login.php" class="btn btn-outline">Student Login</a>
            </div>
        </section>

        <!-- Features Section -->
        <section>
            <div class="section-title">
                <h2>System Features</h2>
                <p>Everything you need to manage your library resources efficiently</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <h3>Book Catalog</h3>
                    <p>Search and browse through textbooks across various BCA subjects, authors, and categories.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Issue & Return</h3>
                    <p>Simple tracking for issued books, return schedules, and book availability status.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">👤</div>
                    <h3>Student Portal</h3>
                    <p>View your borrowed books, check return deadlines, and manage your account details.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3>Due Date Tracking</h3>
                    <p>Keep track of return dates clearly so you never have to worry about overdue library books.</p>
                </div>
            </div>
        </section>

        <!-- Quick Stats Overview -->
        <section class="stats-bar">
            <div class="stat-item">
                <h3>1,500+</h3>
                <p>Available Books</p>
            </div>
            <div class="stat-item">
                <h3>500+</h3>
                <p>Registered Students</p>
            </div>
            <div class="stat-item">
                <h3>12+</h3>
                <p>Departments</p>
            </div>
            <div class="stat-item">
                <h3>24/7</h3>
                <p>Catalog Access</p>
            </div>
        </section>

    </div>
</main>

<?php
// Include footer
include 'includes/footer.php';
?>
