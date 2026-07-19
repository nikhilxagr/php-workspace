<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditional Statements</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 600px;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
        color: #333;
    }
</style>

<body>
    <div class="container">
        <h1>Conditional Statements</h1>
        <p>This is a simple example of conditional statements in PHP.</p>

        <?php
            $age = 25; 

            if ($age >= 18) {
                echo "<p>The person is an adult.</p>";
            } 
            else if ($age < 18 && $age >= 13) {
                echo "<p>The person is a teenager.</p>";
            }
            else {
                echo "<p>The person is not an adult.</p>";
            }
        ?>
    </div>
</body>
</html>