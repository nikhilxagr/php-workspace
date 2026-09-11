# 🍪 PHP Cookies

> **Topic:** PHP Cookies
> **Level:** Beginner → Intermediate
> **Purpose:** Store small pieces of information in the user's browser.

---

## 📌 1. What is a Cookie?

A **cookie** is a small piece of data stored in the **user's browser** by a website.

Cookies are commonly used for:

* Remembering users
* Storing preferences
* Maintaining login/session-related information
* Tracking user activity
* Saving language/theme preferences
* Shopping carts

### Simple Flow

```text
PHP Server
    ↓
Creates Cookie
    ↓
Browser stores Cookie
    ↓
Browser sends Cookie with future requests
    ↓
PHP reads Cookie
```

---

# 🧠 2. Cookie vs Session

| Cookie                           | Session                           |
| -------------------------------- | --------------------------------- |
| Stored in browser                | Stored primarily on server        |
| Client-side storage              | Server-side data                  |
| Can persist after browser closes | Usually expires when session ends |
| Less secure for sensitive data   | Better for sensitive data         |
| Limited storage                  | Can store more data               |
| Accessible through `$_COOKIE`    | Accessible through `$_SESSION`    |

> ⚠️ Never store passwords, authentication secrets, or other sensitive information directly inside cookies.

---

# 🛠️ 3. Creating a Cookie

PHP provides the `setcookie()` function.

### Syntax

```php
setcookie(name, value, expires, path, domain, secure, httponly);
```

### Basic Example

```php
<?php

setcookie("username", "Nikhil", time() + 3600);

echo "Cookie has been created";

?>
```

Here:

```text
username → Cookie name
Nikhil   → Cookie value
3600     → Cookie lifetime in seconds
```

The cookie expires after **1 hour**.

---

# ⏰ 4. Cookie Expiration

The `expires` parameter expects a Unix timestamp.

### One Hour

```php
setcookie("username", "Nikhil", time() + 3600);
```

### One Day

```php
setcookie("username", "Nikhil", time() + 86400);
```

### One Week

```php
setcookie("username", "Nikhil", time() + (7 * 86400));
```

### One Month

```php
setcookie("username", "Nikhil", time() + (30 * 86400));
```

### Important

```php
time()
```

returns the current Unix timestamp.

---

# 📖 5. Reading a Cookie

Cookies can be accessed using the `$_COOKIE` superglobal.

### Example

```php
<?php

setcookie("username", "Nikhil", time() + 3600);

?>
```

Then, on a subsequent request:

```php
<?php

echo $_COOKIE["username"];

?>
```

Output:

```text
Nikhil
```

---

# 🔍 6. Checking if a Cookie Exists

Use `isset()`.

```php
<?php

if (isset($_COOKIE["username"])) {
    echo "Cookie exists";
} else {
    echo "Cookie does not exist";
}

?>
```

### Why use `isset()`?

Without checking:

```php
echo $_COOKIE["username"];
```

you may get an **undefined array key** warning when the cookie doesn't exist.

---

# 🧾 7. Display All Cookies

```php
<?php

print_r($_COOKIE);

?>
```

Example output:

```text
Array
(
    [username] => Nikhil
    [theme] => dark
)
```

You can also use:

```php
var_dump($_COOKIE);
```

---

# ✏️ 8. Updating a Cookie

There is no separate "update cookie" function.

Simply call `setcookie()` again using the same cookie name.

```php
<?php

setcookie("username", "Rahul", time() + 3600);

?>
```

If `username` already exists, its value is replaced.

---

# 🗑️ 9. Deleting a Cookie

To delete a cookie, set its expiration time to a time in the past.

```php
<?php

setcookie("username", "", time() - 3600);

?>
```

Another common approach:

```php
<?php

setcookie("username", "", time() - 86400);

?>
```

### Important

The cookie's **path/domain settings should match** the cookie you are trying to remove.

---

# 📂 10. Cookie Path

The `path` parameter determines where the cookie is available.

### Available throughout the website

```php
<?php

setcookie(
    "username",
    "Nikhil",
    time() + 3600,
    "/"
);

?>
```

The `/` means the cookie is available throughout the domain.

### Specific Directory

```php
setcookie(
    "username",
    "Nikhil",
    time() + 3600,
    "/admin"
);
```

The cookie will be available to requests under `/admin`.

---

# 🔐 11. Secure Cookie

The `secure` option ensures that the browser sends the cookie only over **HTTPS**.

```php
<?php

setcookie(
    "username",
    "Nikhil",
    time() + 3600,
    "/",
    "",
    true
);

?>
```

Here:

```text
true → HTTPS only
```

> Use `secure=true` when your application is served over HTTPS.

---

# 🛡️ 12. HttpOnly Cookie

The `httponly` option prevents JavaScript from accessing the cookie through `document.cookie`.

```php
<?php

setcookie(
    "username",
    "Nikhil",
    time() + 3600,
    "/",
    "",
    true,
    true
);

?>
```

The final:

```php
true
```

means:

```text
HttpOnly = enabled
```

This is particularly useful for cookies containing authentication-related values.

---

# 🧩 13. Recommended Modern Syntax

PHP also supports an options array, which is easier to read.

```php
<?php

setcookie("username", "Nikhil", [
    "expires" => time() + 3600,
    "path" => "/",
    "secure" => true,
    "httponly" => true,
    "samesite" => "Lax"
]);

?>
```

This approach is generally easier to maintain than passing many positional arguments.

---

# 🌐 14. SameSite

`SameSite` controls when browsers send cookies with cross-site requests.

Common values:

### Lax

```php
"samesite" => "Lax"
```

A common default choice for many websites.

### Strict

```php
"samesite" => "Strict"
```

Provides stronger cross-site restrictions.

### None

```php
"samesite" => "None"
```

Allows cross-site cookie usage, but browsers generally require:

```php
"secure" => true
```

Example:

```php
setcookie("username", "Nikhil", [
    "expires" => time() + 3600,
    "path" => "/",
    "secure" => true,
    "httponly" => true,
    "samesite" => "Lax"
]);
```

---

# ⚠️ 15. Important Rule: setcookie() Before Output

Cookies are sent through HTTP response headers.

Therefore, `setcookie()` should be called **before HTML output or other response output**.

### ❌ Incorrect

```php
<?php

echo "Hello";

setcookie("username", "Nikhil", time() + 3600);

?>
```

### ✅ Correct

```php
<?php

setcookie("username", "Nikhil", time() + 3600);

echo "Hello";

?>
```

Otherwise, you may receive:

```text
Warning: Cannot modify header information - headers already sent
```

---

# 🔄 16. Important Cookie Behavior

When you create a cookie:

```php
setcookie("username", "Nikhil", time() + 3600);
```

the browser receives the cookie in the response.

The cookie is normally available through:

```php
$_COOKIE
```

on a **subsequent HTTP request**.

So don't assume this will immediately work in the same request:

```php
<?php

setcookie("username", "Nikhil", time() + 3600);

echo $_COOKIE["username"];

?>
```

Instead, reload/request the page again, or use the value you already know directly.

---

# 🧪 17. Complete Cookie Example

### `cookie.php`

```php
<?php

setcookie(
    "username",
    "Nikhil",
    [
        "expires" => time() + 3600,
        "path" => "/",
        "secure" => true,
        "httponly" => true,
        "samesite" => "Lax"
    ]
);

echo "Cookie created successfully.";

?>
```

### `read-cookie.php`

```php
<?php

if (isset($_COOKIE["username"])) {
    echo "Welcome, " . htmlspecialchars($_COOKIE["username"]);
} else {
    echo "Username cookie not found.";
}

?>
```

---

# 👤 18. Remember User Preference

Cookies are useful for storing non-sensitive preferences.

### Save Theme

```php
<?php

setcookie(
    "theme",
    "dark",
    time() + (30 * 86400),
    "/"
);

?>
```

### Read Theme

```php
<?php

if (isset($_COOKIE["theme"])) {
    echo "Current theme: " . $_COOKIE["theme"];
} else {
    echo "Default theme";
}

?>
```

---

# 🛒 19. Example: Remember Language

```php
<?php

setcookie(
    "language",
    "en",
    time() + (30 * 86400),
    "/"
);

?>
```

Read it:

```php
<?php

$language = $_COOKIE["language"] ?? "en";

echo "Language: " . htmlspecialchars($language);

?>
```

---

# 🧹 20. Delete Cookie Example

```php
<?php

if (isset($_COOKIE["username"])) {

    setcookie(
        "username",
        "",
        [
            "expires" => time() - 3600,
            "path" => "/"
        ]
    );

    echo "Cookie deleted.";

} else {

    echo "Cookie does not exist.";

}

?>
```

---

# 🔒 21. Security Best Practices

### ✅ 1. Don't store sensitive information

Avoid:

```text
password
credit card information
private secrets
```

in plain cookies.

---

### ✅ 2. Use HttpOnly

```php
"httponly" => true
```

This prevents normal JavaScript access.

---

### ✅ 3. Use Secure

```php
"secure" => true
```

This ensures the cookie is transmitted only over HTTPS.

---

### ✅ 4. Use SameSite

```php
"samesite" => "Lax"
```

or:

```php
"samesite" => "Strict"
```

depending on your application's requirements.

---

### ✅ 5. Validate Cookie Data

Never blindly trust:

```php
$_COOKIE
```

Cookie values come from the client and can be modified.

Example:

```php
$username = $_COOKIE["username"] ?? "";

$username = htmlspecialchars($username);

echo $username;
```

For application logic, validate the value according to the expected format rather than relying only on output escaping.

---

# 📋 22. Common Cookie Functions / Syntax

| Code                 | Purpose                       |
| -------------------- | ----------------------------- |
| `setcookie()`        | Create/update a cookie        |
| `$_COOKIE`           | Read cookies                  |
| `isset()`            | Check whether cookie exists   |
| `time()`             | Generate expiration timestamp |
| `htmlspecialchars()` | Safely escape output          |

---

# 🧠 23. Quick Revision

```text
Cookie
  ↓
Small data stored by browser
  ↓
Created using setcookie()
  ↓
Read using $_COOKIE
  ↓
Updated using setcookie()
  ↓
Deleted using expired timestamp
```

### Create

```php
setcookie("username", "Nikhil", time() + 3600);
```

### Read

```php
echo $_COOKIE["username"];
```

### Check

```php
isset($_COOKIE["username"]);
```

### Delete

```php
setcookie("username", "", time() - 3600);
```

### Secure Cookie

```php
setcookie("username", "Nikhil", [
    "expires" => time() + 3600,
    "path" => "/",
    "secure" => true,
    "httponly" => true,
    "samesite" => "Lax"
]);
```

---

# 🎯 24. Practice Programs

Try implementing these without looking at the solution:

### Beginner

1. Create a cookie containing a username.
2. Read and display the username.
3. Check whether a cookie exists.
4. Delete a cookie.
5. Update an existing cookie.
6. Create a cookie that expires after 10 minutes.

### Intermediate

7. Store a user's preferred language.
8. Store a dark/light theme preference.
9. Create a "Remember Me" checkbox using cookies.
10. Count how many times a user visits a page.
11. Store the user's last visited date.
12. Create a cookie-based welcome message.
13. Create a page that displays all available cookies.
14. Build a cookie-based theme switcher.
15. Create a simple cookie-based shopping cart.

### Advanced

16. Create a secure authentication cookie design.
17. Implement cookie expiration.
18. Implement cookie deletion with matching path settings.
19. Experiment with `HttpOnly`, `Secure`, and `SameSite`.
20. Build a complete cookie preference system.

---

# 🔥 25. Interview Questions

### Q1. What is a cookie?

A cookie is a small piece of data stored by a website in the user's browser.

### Q2. How do you create a cookie in PHP?

```php
setcookie("name", "value", time() + 3600);
```

### Q3. How do you read a cookie?

```php
$_COOKIE["name"];
```

### Q4. How do you delete a cookie?

Set its expiration time to the past:

```php
setcookie("name", "", time() - 3600);
```

### Q5. Where are cookies stored?

Cookies are stored by the user's browser/client.

### Q6. Can users modify cookies?

**Yes.** Cookie data should always be treated as untrusted client input.

### Q7. What does HttpOnly do?

It prevents JavaScript from accessing the cookie through normal client-side APIs such as `document.cookie`.

### Q8. What does Secure do?

It tells the browser to send the cookie only over HTTPS.

### Q9. What does SameSite do?

It controls whether a cookie is sent with cross-site requests.

### Q10. Why must `setcookie()` usually be called before output?

Because PHP sends cookies as HTTP response headers, and headers must be sent before the response body.

---

# 🚀 Final Cheat Sheet

```php
// CREATE
setcookie("user", "Nikhil", time() + 3600);

// READ
echo $_COOKIE["user"];

// CHECK
if (isset($_COOKIE["user"])) {
    echo "Cookie exists";
}

// UPDATE
setcookie("user", "Rahul", time() + 3600);

// DELETE
setcookie("user", "", time() - 3600);

// MODERN SECURE COOKIE
setcookie("user", "Nikhil", [
    "expires" => time() + 3600,
    "path" => "/",
    "secure" => true,
    "httponly" => true,
    "samesite" => "Lax"
]);
```

> **Remember:** Cookies are **client-controlled data**. Use them for preferences and other appropriate state, but don't trust them for authorization or store sensitive secrets in plaintext.
