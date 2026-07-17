<?php
    $name = "Nihkil Agrahari";
    $today = date("d M Y");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Portfolio Using Php</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="img.jpeg" alt="Profile" width="36" height="36" class="rounded-circle me-2">
                    <?php echo $name; ?>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center">
                        <span class="navbar-text me-3">
                            <?php echo $today; ?>
                        </span>
                        <span class="navbar-text">
                            Welcome to my Portfolio!
                        </span>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mt-5">
        <h1>About Me</h1>

        <h4>Hello, I'm <?php echo $name; ?> 👋</h4>

        <p>
            Hello! My name is <?php echo $name; ?>.
            I am a web developer and Cyber Security Analyst with a passion for creating dynamic and secure websites.
            I have experience in PHP, HTML, CSS, and JavaScript.
        </p>

        <p>Today's Date: <strong><?php echo $today; ?></strong></p>

        <p>Feel free to explore my website and learn more about me and my work!</p>
    </main>

    <section class="container mt-5">
        <div class="card" style="width: 18rem;">
            <img src="img.jpeg" class="card-img-top" alt="Profile Image">

            <div class="card-body">
                <p class="card-text">
                    Thanks for visiting my website, <?php echo $name; ?>!
                </p>
            </div>
        </div>
    </section>

        <!-- Bootstrap JS (bundle includes Popper) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    </body>

    </html>