# PHP Cookies — Complete Theory & Practical Guide

## 1. What is a Cookie?

A **cookie** is a small piece of data that a website asks the user's web browser to store.

Cookies are commonly used to remember information between HTTP requests, such as:

- User preferences
- Theme settings
- Language selection
- Shopping cart identifiers
- Remember-me functionality
- Non-sensitive tracking or configuration information

A cookie is stored by the **browser (client)**, not as normal application data on the PHP server.

### Basic idea

```text
PHP Server
    |
    | Set-Cookie
    v
Web Browser
    |
    | Cookie
    v
PHP Server
```

---

# 2. Why Do We Need Cookies?

HTTP is generally **stateless**.

This means the server treats separate HTTP requests independently.

For example:

```text
Request 1 → PHP
Request 2 → PHP
Request 3 → PHP
```

PHP does not automatically know that all three requests came from the same browser.

Cookies provide one way to maintain small pieces of information across requests.

Example:

```text
Request 1:
User selects Dark Mode

        ↓

Cookie:
theme=dark

        ↓

Request 2:
PHP receives theme=dark

        ↓

Website displays Dark Mode
```

---

# 3. How Cookies Work

The cookie lifecycle is mainly based on HTTP headers.

## Step 1: Browser requests a page

```text
Browser → PHP Server
```

## Step 2: PHP creates a cookie

PHP uses:

```php
setcookie("username", "John", time() + 3600);
```

## Step 3: PHP sends a response

The response contains a `Set-Cookie` header.

Conceptually:

```http
Set-Cookie: username=John
```

## Step 4: Browser stores the cookie

The browser saves the cookie according to its attributes.

## Step 5: Browser makes another request

The browser sends the cookie back:

```http
Cookie: username=John
```

## Step 6: PHP can read it

PHP makes incoming cookies available through:

```php
$_COOKIE
```

---

# 4. Creating a Cookie in PHP

The traditional form of `setcookie()` is:

```php
setcookie(name, value, expires, path, domain, secure, httponly);
```

Example:

```php
<?php

setcookie(
    "username",
    "John",
    time() + 3600,
    "/"
);

?>
```

This creates a cookie named `username` with the value `John`.

The cookie is configured to expire after one hour.

---

# 5. Understanding `setcookie()`

Consider:

```php
setcookie(
    "username",
    "John",
    time() + 3600,
    "/"
);
```

### `"username"`

The name of the cookie.

```text
username
```

### `"John"`

The cookie value.

```text
John
```

### `time() + 3600`

The expiration timestamp.

`time()` returns the current Unix timestamp.

```php
time() + 3600
```

means approximately one hour from now.

### `"/"`

The cookie path.

A path of `/` makes the cookie available throughout the website.

---

# 6. Modern `setcookie()` Syntax

PHP also supports an options-array form:

```php
setcookie("username", "John", [
    "expires" => time() + 3600,
    "path" => "/",
    "secure" => true,
    "httponly" => true,
    "samesite" => "Lax"
]);
```

This form is often easier to understand because each option is named.

---

# 7. Reading Cookies

PHP stores incoming cookies in the `$_COOKIE` superglobal array.

Example:

```php
<?php

echo $_COOKIE["username"];

?>
```

If the cookie contains:

```text
username=John
```

the output is:

```text
John
```

---

# 8. Checking Whether a Cookie Exists

Before reading a cookie, it is good practice to check whether it exists.

Use:

```php
isset()
```

Example:

```php
<?php

if (isset($_COOKIE["username"])) {
    echo "Welcome " . $_COOKIE["username"];
} else {
    echo "Cookie does not exist.";
}

?>
```

This prevents trying to access a cookie that has not been sent by the browser.

---

# 9. Deleting a Cookie

PHP does not have a special `deleteCookie()` function.

Instead, you normally expire the cookie by setting its expiration time in the past.

Example:

```php
<?php

setcookie(
    "username",
    "",
    time() - 3600,
    "/"
);

?>
```

The browser receives an expired cookie and removes it.

### Important

When deleting a cookie, use compatible cookie attributes such as the same `path` (and, where relevant, domain) that were used when setting it.

---

# 10. Cookie Expiration

Cookies can have different lifetimes.

## Session cookie

If no persistent expiration is supplied, the cookie is generally a session cookie.

Its lifetime is tied to the browser's cookie/session handling and is not guaranteed to survive browser restarts.

## Persistent cookie

A cookie with an expiration time can remain stored until it expires or is removed.

Example:

```php
setcookie(
    "username",
    "John",
    time() + 86400,
    "/"
);
```

`86400` seconds is approximately one day.

---

# 11. Common Time Values

```text
60       = 1 minute
3600     = 1 hour
86400    = 1 day
604800   = 7 days
2592000  = 30 days
31536000 = approximately 1 year
```

Example:

```php
setcookie(
    "theme",
    "dark",
    time() + 31536000,
    "/"
);
```

---

# 12. Cookie Path

The `path` option determines which URL paths should receive the cookie.

Example:

```php
setcookie(
    "username",
    "John",
    time() + 3600,
    "/"
);
```

Using:

```text
/
```

means the cookie is available across the website.

A narrower path can restrict where the browser sends it.

For example:

```php
setcookie(
    "admin_setting",
    "1",
    time() + 3600,
    "/admin"
);
```

The browser can use this cookie for requests under the `/admin` path according to cookie path matching rules.

---

# 13. Cookie Domain

The `domain` attribute controls which host/domain can receive the cookie.

Example:

```php
setcookie(
    "username",
    "John",
    time() + 3600,
    "/",
    "example.com"
);
```

In normal applications, you often do not need to specify the domain explicitly. If omitted, the cookie is associated with the host that set it.

Be careful when configuring domain-wide cookies because they may be sent to more hosts than necessary.

---

# 14. Secure Cookie

The `secure` option tells the browser to send the cookie only over HTTPS.

Example:

```php
setcookie("username", "John", [
    "expires" => time() + 3600,
    "path" => "/",
    "secure" => true
]);
```

For production websites that use HTTPS, sensitive cookies should generally use the `Secure` attribute.

---

# 15. HttpOnly Cookie

The `httponly` option prevents normal JavaScript access to the cookie through APIs such as `document.cookie`.

Example:

```php
setcookie("session_id", "abc123", [
    "expires" => time() + 3600,
    "path" => "/",
    "secure" => true,
    "httponly" => true
]);
```

This can reduce the impact of some attacks involving malicious JavaScript.

### Important

`HttpOnly` does not make a cookie completely safe.

If an attacker can perform actions as the user through an existing session, `HttpOnly` alone does not solve the underlying problem.

---

# 16. SameSite Cookies

`SameSite` controls how cookies are sent in cross-site contexts.

Common values are:

```text
Strict
Lax
None
```

## SameSite=Strict

Provides the strongest cross-site restriction.

```php
setcookie("session_id", "abc123", [
    "expires" => time() + 3600,
    "path" => "/",
    "secure" => true,
    "httponly" => true,
    "samesite" => "Strict"
]);
```

## SameSite=Lax

Allows some cross-site navigation scenarios while restricting many cross-site request contexts.

Example:

```php
"samesite" => "Lax"
```

This is a common choice for many normal web applications.

## SameSite=None

Allows the cookie to be sent in cross-site contexts.

When using `SameSite=None`, browsers generally require the cookie to also have:

```text
Secure
```

Example:

```php
setcookie("example", "value", [
    "expires" => time() + 3600,
    "path" => "/",
    "secure" => true,
    "httponly" => true,
    "samesite" => "None"
]);
```

---

# 17. Complete Cookie Example

The following example allows a user to:

1. Enter their name
2. Save it in a cookie
3. Read the cookie
4. Delete the cookie

```php
<?php

// Save cookie
if (isset($_POST["save"])) {

    $name = $_POST["name"];

    setcookie(
        "username",
        $name,
        time() + 3600,
        "/"
    );

    // Redirect so the new cookie is available on the next request
    header("Location: index.php");
    exit();
}


// Delete cookie
if (isset($_POST["delete"])) {

    setcookie(
        "username",
        "",
        time() - 3600,
        "/"
    );

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

if (isset($_COOKIE["username"])) {

    echo "<h2>Welcome, "
        . htmlspecialchars($_COOKIE["username"])
        . "!</h2>";

    echo "<p>Your name is stored in a cookie.</p>";

} else {

    echo "<p>No cookie found.</p>";

}

?>

<hr>

<form method="POST">

    <label>Enter your name:</label>

    <input
        type="text"
        name="name"
        required
    >

    <button type="submit" name="save">
        Save Name
    </button>

</form>

<br>

<form method="POST">

    <button type="submit" name="delete">
        Delete Cookie
    </button>

</form>

</body>
</html>
```

---

# 18. Why Does the Example Use `header()`?

After setting the cookie:

```php
setcookie("username", $name, time() + 3600, "/");
```

the cookie is sent to the browser as part of the HTTP response.

The example then does:

```php
header("Location: index.php");
exit();
```

This causes a new request.

On that new request, the browser sends the cookie back to PHP, making it available through:

```php
$_COOKIE["username"]
```

This is why redirecting after a cookie-changing POST is useful.

---

# 19. Important Rule: `setcookie()` Before Output

Cookies are sent using HTTP response headers.

Therefore, `setcookie()` must normally be called before sending page output.

Correct:

```php
<?php

setcookie("username", "John", time() + 3600, "/");

?>

<!DOCTYPE html>
<html>
<body>

<h1>Hello</h1>

</body>
</html>
```

Incorrect:

```php
<!DOCTYPE html>
<html>
<body>

<h1>Hello</h1>

<?php

setcookie("username", "John", time() + 3600, "/");

?>

</body>
</html>
```

The second example can cause a "headers already sent" problem because output may already have been sent.

---

# 20. Why `$_COOKIE` Is an Array

PHP stores cookies in the `$_COOKIE` superglobal.

For example, if the browser sends:

```text
username=John
theme=dark
language=en
```

PHP can expose them approximately as:

```php
$_COOKIE["username"]
$_COOKIE["theme"]
$_COOKIE["language"]
```

You can inspect cookies during development with:

```php
print_r($_COOKIE);
```

---

# 21. Multiple Cookies

A website can have multiple cookies.

Example:

```php
<?php

setcookie("username", "John", time() + 3600, "/");

setcookie("theme", "dark", time() + 3600, "/");

setcookie("language", "en", time() + 3600, "/");

?>
```

Reading them:

```php
<?php

echo $_COOKIE["username"];
echo $_COOKIE["theme"];
echo $_COOKIE["language"];

?>
```

---

# 22. Cookies and HTML Forms

Cookies and forms solve different problems.

A form sends data to PHP:

```html
<form method="POST">
    <input type="text" name="username">
    <button type="submit">Submit</button>
</form>
```

PHP receives it through:

```php
$_POST["username"]
```

PHP can then store some of that information in a cookie:

```php
setcookie(
    "username",
    $_POST["username"],
    time() + 3600,
    "/"
);
```

---

# 23. Cookies vs Sessions

Cookies and PHP sessions are related but different.

| Feature | Cookie | PHP Session |
|---|---|---|
| Main storage | Browser | Server-side session data |
| PHP access | `$_COOKIE` | `$_SESSION` |
| Lifetime | Can be persistent | Usually session-oriented |
| Size | Small | Server-side data can be much larger |
| User can inspect cookie | Yes | Session data is generally not stored directly in the cookie |
| Common use | Preferences, identifiers | Login state, server-side user data |

A PHP session normally uses a cookie containing a session identifier. The actual session data is typically maintained server-side.

---

# 24. Cookies Should Not Store Sensitive Data

Do not put passwords or other highly sensitive secrets directly into ordinary cookies.

Bad example:

```php
setcookie(
    "password",
    "MyPassword123",
    time() + 3600,
    "/"
);
```

The browser/user can potentially inspect the cookie.

A better design is to store a random identifier or use a PHP session for authentication state.

For example:

```text
Browser cookie:
session_id = random_identifier

Server:
random_identifier → user/session data
```

---

# 25. Cookie Security

When cookies are used for authentication or other sensitive purposes, common security measures include:

```text
Secure
HttpOnly
SameSite
```

Example:

```php
setcookie("session_id", $sessionId, [
    "expires" => time() + 3600,
    "path" => "/",
    "secure" => true,
    "httponly" => true,
    "samesite" => "Lax"
]);
```

Also use HTTPS throughout the authenticated application and follow secure session-management practices.

---

# 26. Cookie Values Should Be Treated as Untrusted Input

Never assume a cookie value is trustworthy.

For example:

```php
$username = $_COOKIE["username"];
```

The value came from the browser.

A user can modify cookies using browser developer tools.

Therefore, do not use cookie values as trusted authorization information.

Bad idea:

```php
if ($_COOKIE["role"] == "admin") {
    // Give administrator access
}
```

A user could potentially change:

```text
role=user
```

to:

```text
role=admin
```

Authorization decisions should be based on trusted server-side data.

---

# 27. Output Escaping

If you display a cookie value in HTML, escape it appropriately.

Example:

```php
echo htmlspecialchars($_COOKIE["username"]);
```

Instead of:

```php
echo $_COOKIE["username"];
```

Why?

Because cookie values are user-controlled input and should not be blindly inserted into HTML.

---

# 28. Cookie Size

Cookies are designed for **small amounts of data**.

They are not a replacement for a database.

Do not try to store large application objects, large HTML documents, or large datasets in cookies.

A common design is:

```text
Cookie:
user_id / random token / small preference

Database:
large application data
```

---

# 29. Cookies Are Sent With Requests

Cookies can be sent to the server with matching requests.

Therefore, unnecessary or oversized cookies can increase HTTP request overhead.

For this reason, keep cookies:

- Small
- Necessary
- Appropriately scoped

---

# 30. Cookie Path and Domain Matter

Suppose a cookie is configured for:

```text
Path=/admin
```

It is not intended for every URL on the site.

Likewise, domain configuration determines which hosts can receive the cookie.

Correctly limiting scope reduces unnecessary exposure.

---

# 31. Cookie Example With Security Options

A more security-conscious cookie can look like:

```php
<?php

setcookie("user_token", "random-value", [
    "expires" => time() + 3600,
    "path" => "/",
    "secure" => true,
    "httponly" => true,
    "samesite" => "Lax"
]);

?>
```

For an HTTPS production website, this is a much better pattern than a bare cookie when the cookie carries sensitive authentication-related state.

---

# 32. Cookie vs Local Storage

Cookies are not the same as browser `localStorage`.

| Feature | Cookie | localStorage |
|---|---|---|
| Automatically sent with matching HTTP requests | Yes | No |
| Accessible from JavaScript by default | Yes, unless HttpOnly | Yes |
| Common use | Server-related state, preferences, identifiers | Client-side application data |
| Sent to server automatically | Yes | No |
| Can use HttpOnly | Yes | No |

For authentication, cookie-based sessions are often preferable to putting authentication tokens in JavaScript-accessible storage, because `HttpOnly` cookies can prevent ordinary JavaScript from reading the credential.

---

# 33. Common Mistakes

## Mistake 1: Calling `setcookie()` after output

Wrong:

```php
echo "Hello";

setcookie("name", "John", time() + 3600);
```

Potential result:

```text
Cannot modify header information - headers already sent
```

Set the cookie before output.

---

## Mistake 2: Assuming the cookie is immediately in `$_COOKIE`

```php
setcookie("name", "John", time() + 3600);

echo $_COOKIE["name"];
```

Do not rely on this to read the newly set browser cookie during the same request.

The browser normally sends the cookie back on a subsequent request.

---

## Mistake 3: Trusting cookie data

Wrong:

```php
if ($_COOKIE["is_admin"] == "true") {
    // allow admin access
}
```

Cookie data is controlled by the client.

---

## Mistake 4: Storing passwords

Never use a cookie as a place to store a user's plain-text password.

---

## Mistake 5: Forgetting `HttpOnly` and `Secure` for sensitive cookies

For sensitive authentication cookies, appropriate security attributes should be considered.

---

# 34. Complete CRUD-Style Cookie Example

Cookies do not technically have database-style CRUD operations, but we can think of the common operations as:

```text
Create → setcookie()
Read   → $_COOKIE
Update → setcookie() again
Delete → expire the cookie
```

Example:

### Create

```php
setcookie(
    "theme",
    "dark",
    time() + 3600,
    "/"
);
```

### Read

```php
echo $_COOKIE["theme"];
```

### Update

```php
setcookie(
    "theme",
    "light",
    time() + 3600,
    "/"
);
```

### Delete

```php
setcookie(
    "theme",
    "",
    time() - 3600,
    "/"
);
```

---

# 35. Practical Example: Remember User's Theme

```php
<?php

if (isset($_POST["theme"])) {

    $theme = $_POST["theme"];

    setcookie(
        "theme",
        $theme,
        time() + 86400 * 30,
        "/"
    );

    header("Location: index.php");
    exit();
}

$theme = $_COOKIE["theme"] ?? "light";

?>

<!DOCTYPE html>
<html>
<head>
    <title>Theme Cookie</title>
</head>

<body>

<h1>Current Theme: <?php echo htmlspecialchars($theme); ?></h1>

<form method="POST">

    <button name="theme" value="light">
        Light
    </button>

    <button name="theme" value="dark">
        Dark
    </button>

</form>

</body>
</html>
```

This stores the user's selected theme for approximately 30 days.

---

# 36. Null Coalescing Operator

A convenient way to read an optional cookie is:

```php
$theme = $_COOKIE["theme"] ?? "light";
```

This means:

```text
If theme cookie exists:
    use its value

Otherwise:
    use "light"
```

It is equivalent in concept to:

```php
if (isset($_COOKIE["theme"])) {
    $theme = $_COOKIE["theme"];
} else {
    $theme = "light";
}
```

---

# 37. Cookies and Login Systems

A common login architecture is:

```text
User logs in
      |
      v
PHP verifies username/password
      |
      v
PHP creates a server-side session
      |
      v
Browser receives a session cookie
      |
      v
Browser sends session cookie on later requests
      |
      v
PHP identifies the session
```

The cookie generally contains an identifier rather than the user's password.

PHP's session system can manage much of this automatically.

Basic session example:

```php
<?php

session_start();

$_SESSION["username"] = "John";

echo $_SESSION["username"];

?>
```

This is different from manually storing:

```php
setcookie("username", "John", ...);
```

---

# 38. Cookie Consent

Many websites use cookies for analytics, advertising, or other purposes that may be subject to privacy laws and regulations.

Whether consent is required depends on:

- The type of cookie
- What data is collected
- The purpose
- The user's location
- Applicable privacy laws
- How the website operates

Technical cookie implementation and legal compliance are separate concerns.

---

# 39. Important Terms

### Client

The user's browser or device.

### Server

The computer/application running PHP.

### HTTP

The protocol used for communication between browser and web server.

### Request

A message sent by the browser to the server.

### Response

A message sent by the server to the browser.

### Header

Metadata associated with an HTTP request or response.

Cookies are communicated through HTTP headers.

### `Set-Cookie`

A response header used by the server to instruct the browser to store a cookie.

### `Cookie`

A request header used by the browser to send matching cookies back to the server.

---

# 40. Quick Revision

## Create

```php
setcookie(
    "username",
    "John",
    time() + 3600,
    "/"
);
```

## Read

```php
echo $_COOKIE["username"];
```

## Check

```php
if (isset($_COOKIE["username"])) {
    echo $_COOKIE["username"];
}
```

## Delete

```php
setcookie(
    "username",
    "",
    time() - 3600,
    "/"
);
```

## Secure cookie

```php
setcookie("session_id", $id, [
    "expires" => time() + 3600,
    "path" => "/",
    "secure" => true,
    "httponly" => true,
    "samesite" => "Lax"
]);
```

---

# 41. Interview Questions

## Q1. What is a cookie?

A cookie is a small piece of data stored by the browser and sent with matching HTTP requests.

## Q2. How do you create a cookie in PHP?

Using:

```php
setcookie();
```

## Q3. How do you read a cookie?

Using:

```php
$_COOKIE["cookie_name"];
```

## Q4. How do you check if a cookie exists?

Using:

```php
isset($_COOKIE["cookie_name"]);
```

## Q5. How do you delete a cookie?

Set its expiration time in the past:

```php
setcookie("name", "", time() - 3600, "/");
```

## Q6. Where is a normal cookie stored?

Primarily in the user's web browser.

## Q7. Can a user modify a cookie?

Yes. Therefore, cookie values must be treated as untrusted input.

## Q8. Can cookies store passwords safely?

No. Do not store plain-text passwords in cookies.

## Q9. What does `HttpOnly` do?

It prevents normal client-side JavaScript from reading the cookie.

## Q10. What does `Secure` do?

It tells the browser to send the cookie only over HTTPS.

## Q11. What does `SameSite` do?

It controls cookie sending behavior in cross-site contexts.

## Q12. Can PHP read a cookie immediately after calling `setcookie()`?

The newly set browser cookie is normally available to PHP on a subsequent request, after the browser has received and stored it.

---

# 42. Final Summary

The most important PHP cookie concepts are:

```text
Cookie
  ↓
Small piece of browser-stored data
  ↓
Created with setcookie()
  ↓
Sent back by browser in later matching requests
  ↓
Read with $_COOKIE
```

The four operations to remember are:

```php
// CREATE
setcookie("name", "John", time() + 3600, "/");

// READ
echo $_COOKIE["name"];

// CHECK
isset($_COOKIE["name"]);

// DELETE
setcookie("name", "", time() - 3600, "/");
```

For sensitive cookies, commonly consider:

```text
Secure
HttpOnly
SameSite
HTTPS
```

And always remember:

> **Cookie data comes from the client, so never automatically trust it.**

Cookies are useful for small pieces of state such as preferences and identifiers, while server-side sessions and databases are generally better places for sensitive or larger application data.
