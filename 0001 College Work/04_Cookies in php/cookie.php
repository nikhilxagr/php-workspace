```php
<?php


// SAVE COOKIE

if (isset($_POST["save"])) {

    $name = $_POST["name"];

    // Create cookie for 1 hour
    setcookie(
        "username",
        $name,
        time() + 3600,
        "/"
    );

    // Redirect to refresh the page
    header("Location: index.php");
    exit();
}


// DELETE COOKIE

if (isset($_POST["delete"])) {

    // Set expiration time in the past
    setcookie(
        "username",
        "",
        time() - 3600,
        "/"
    );

    // Redirect to refresh the page
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Cookie Example</title>
</head>

<body>

<h1>PHP Cookie Example</h1>

<?php


// READ COOKIE

if (isset($_COOKIE["username"])) {

    echo "<h2>Welcome, " . htmlspecialchars($_COOKIE["username"]) . "!</h2>";

    echo "<p>Your name is stored in a cookie.</p>";

} else {

    echo "<p>No cookie found.</p>";

}

?>

<hr>

<!-- ---------------------------------
     FORM TO SAVE COOKIE
---------------------------------- -->

<form method="POST">

    <label>Enter your name:</label>

    <input type="text" name="name" required>

    <button type="submit" name="save">
        Save Name
    </button>

</form>

<br>

<!-- ---------------------------------
     FORM TO DELETE COOKIE
---------------------------------- -->

<form method="POST">

    <button type="submit" name="delete">
        Delete Cookie
    </button>

</form>

</body>
</html>
```
