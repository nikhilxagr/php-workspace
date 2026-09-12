# 🧩 PHP Templates

> **Part 2 of 12 — Creating Web Applications Using Server-Side Scripting**

---

## 📌 What is a PHP Template?

A **PHP template** is a reusable file that contains **HTML, PHP, or both**, and is designed to be included in multiple pages of a web application.

Templates help us create **reusable, maintainable, and organized** web applications.

Instead of writing the same HTML code on every page, we create it once and reuse it.

---

## 🎯 Why Do We Need Templates?

Imagine a website with:

```text
Home
About
Services
Contact
Login
Dashboard
```

Every page may contain the same:

```text
Header
Navbar
Footer
Sidebar
```

Without templates, we might have to copy the same code into every file.

```text
❌ Without Templates

index.php
 ├── Header
 ├── Navbar
 ├── Content
 └── Footer

about.php
 ├── Header
 ├── Navbar
 ├── Content
 └── Footer

contact.php
 ├── Header
 ├── Navbar
 ├── Content
 └── Footer
```

This creates **duplicate code**.

With templates:

```text
✅ With Templates

templates/
├── header.php
├── navbar.php
└── footer.php

index.php
about.php
contact.php
```

Each page can reuse the same components.

---

# 🧠 Core Concept

The main idea behind PHP templates is:

```text
        Reusable Components
                │
       ┌────────┼────────┐
       ▼        ▼        ▼
    Header    Navbar   Footer
       │        │        │
       └────────┼────────┘
                ▼
          Web Pages
```

A page can combine these reusable components with its own content.

---

# 📁 Typical PHP Template Structure

A simple PHP project may look like:

```text
my-website/
│
├── index.php
├── about.php
├── contact.php
│
└── templates/
    ├── header.php
    ├── navbar.php
    └── footer.php
```

---

# 🧩 Basic Template Example

## `header.php`

```php
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Website</title>

</head>

<body>

<header>

    <h1>My Website</h1>

</header>
```

---

## `footer.php`

```php
<footer>

    <p>
        &copy; 2026 My Website
    </p>

</footer>

</body>

</html>
```

---

## `index.php`

```php
<?php

require_once "header.php";

?>

<main>

    <h2>Home Page</h2>

    <p>
        Welcome to my website.
    </p>

</main>

<?php

require_once "footer.php";

?>
```

---

# 🔄 How This Works

When the user requests:

```text
index.php
```

PHP processes:

```text
index.php
   │
   ├── require_once header.php
   │
   ├── Main Content
   │
   └── require_once footer.php
```

The final HTML sent to the browser becomes approximately:

```html
<!DOCTYPE html>

<html>

<head>
    <title>My Website</title>
</head>

<body>

<header>
    <h1>My Website</h1>
</header>

<main>

    <h2>Home Page</h2>

    <p>
        Welcome to my website.
    </p>

</main>

<footer>

    <p>
        &copy; 2026 My Website
    </p>

</footer>

</body>

</html>
```

---

# 📦 Including Templates

PHP provides several ways to include reusable files.

```php
include "header.php";
```

```php
require "header.php";
```

```php
include_once "header.php";
```

```php
require_once "header.php";
```

For more information about these statements, see the dedicated topic:

> **01 — Server-Side Scripting / File Inclusion Concepts**

---

# 1️⃣ `include`

The `include` statement inserts the contents of another PHP file.

### Syntax

```php
include "filename.php";
```

### Example

```php
<?php

include "header.php";

?>

<h2>Home Page</h2>
```

If the file cannot be found, PHP generates a warning and normally continues execution.

---

# 2️⃣ `require`

`require` also includes another file, but the file is treated as essential.

```php
<?php

require "header.php";

?>
```

If the required file cannot be loaded, PHP generates a fatal error and stops execution.

---

# 3️⃣ `include_once`

`include_once` ensures that the same file is not included more than once during a request.

```php
<?php

include_once "header.php";

?>
```

---

# 4️⃣ `require_once`

`require_once` combines the behavior of `require` with the "only once" behavior.

```php
<?php

require_once "header.php";

?>
```

This is particularly useful for:

* Configuration
* Database connections
* Classes
* Libraries
* Common application files

---

# 🏗️ Building a Reusable Website

Let's create a small PHP website using templates.

## 📁 Project Structure

```text
student-portal/
│
├── index.php
├── about.php
├── contact.php
│
└── templates/
    ├── header.php
    ├── navbar.php
    └── footer.php
```

---

# 🧱 Step 1 — Create `header.php`

```php
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Student Portal</title>

</head>

<body>
```

---

# 🧭 Step 2 — Create `navbar.php`

```php
<nav>

    <a href="index.php">Home</a>

    <a href="about.php">About</a>

    <a href="contact.php">Contact</a>

</nav>

<hr>
```

---

# 🦶 Step 3 — Create `footer.php`

```php
<hr>

<footer>

    <p>
        &copy; 2026 Student Portal
    </p>

</footer>

</body>

</html>
```

---

# 🏠 Step 4 — Create `index.php`

```php
<?php

require_once "templates/header.php";
require_once "templates/navbar.php";

?>

<main>

    <h1>Welcome to Student Portal</h1>

    <p>
        This is the home page.
    </p>

</main>

<?php

require_once "templates/footer.php";

?>
```

---

# ℹ️ Step 5 — Create `about.php`

```php
<?php

require_once "templates/header.php";
require_once "templates/navbar.php";

?>

<main>

    <h1>About Us</h1>

    <p>
        This website is built using PHP templates.
    </p>

</main>

<?php

require_once "templates/footer.php";

?>
```

---

# 📞 Step 6 — Create `contact.php`

```php
<?php

require_once "templates/header.php";
require_once "templates/navbar.php";

?>

<main>

    <h1>Contact Us</h1>

    <p>
        Email: support@example.com
    </p>

</main>

<?php

require_once "templates/footer.php";

?>
```

---

# ✨ Result

All three pages:

```text
index.php
about.php
contact.php
```

share:

```text
header.php
navbar.php
footer.php
```

Therefore, if we modify the navigation bar, we only need to change:

```text
templates/navbar.php
```

instead of editing every page.

---

# 🔥 Dynamic Templates

Templates become even more useful when PHP variables are passed into them.

For example:

```php
<?php

$pageTitle = "Home";

require_once "header.php";

?>
```

Inside `header.php`:

```php
<!DOCTYPE html>

<html>

<head>

    <title>
        <?= $pageTitle ?>
    </title>

</head>
```

The generated title becomes:

```text
Home
```

---

# 🎨 Dynamic Page Titles

Different pages can use the same header template.

### `index.php`

```php
<?php

$pageTitle = "Home";

require_once "templates/header.php";

?>

<h1>Welcome Home</h1>
```

### `about.php`

```php
<?php

$pageTitle = "About";

require_once "templates/header.php";

?>

<h1>About Us</h1>
```

### `contact.php`

```php
<?php

$pageTitle = "Contact";

require_once "templates/header.php";

?>

<h1>Contact Us</h1>
```

The same template can therefore generate different pages.

---

# 🧠 PHP Short Echo Syntax

Templates frequently use PHP's short echo syntax:

```php
<?= $variable ?>
```

It is equivalent to:

```php
<?php echo $variable; ?>
```

### Example

```php
<?php

$name = "Nikhil";

?>

<h1>
    Welcome, <?= $name ?>
</h1>
```

Output:

```text
Welcome, Nikhil
```

This syntax makes PHP templates cleaner and easier to read.

---

# 📦 Separating Configuration

Configuration should usually be kept separate from presentation templates.

Example:

```text
project/
│
├── config/
│   └── config.php
│
├── templates/
│   ├── header.php
│   └── footer.php
│
└── index.php
```

### `config.php`

```php
<?php

define("SITE_NAME", "Student Portal");
define("SITE_VERSION", "1.0");

?>
```

### `header.php`

```php
<?php

require_once __DIR__ . "/../config/config.php";

?>

<!DOCTYPE html>

<html>

<head>

    <title>
        <?= SITE_NAME ?>
    </title>

</head>

<body>

<header>

    <h1>
        <?= SITE_NAME ?>
    </h1>

</header>
```

---

# 📍 Understanding `__DIR__`

`__DIR__` returns the directory of the current PHP file.

Example:

```php
echo __DIR__;
```

It can be useful for creating reliable file paths.

Instead of:

```php
require_once "../config/config.php";
```

we can use:

```php
require_once __DIR__ . "/../config/config.php";
```

This makes the path relative to the current PHP file.

---

# 🧩 Template Components

A larger application can divide the interface into multiple components.

```text
templates/
│
├── layout/
│   ├── header.php
│   └── footer.php
│
├── components/
│   ├── navbar.php
│   ├── sidebar.php
│   ├── alert.php
│   └── card.php
│
└── pages/
    ├── home.php
    └── dashboard.php
```

This approach improves organization as the project grows.

---

# 🔄 Template Architecture

A simple architecture can look like:

```text
                 PHP Application
                       │
          ┌────────────┼────────────┐
          ▼            ▼            ▼
       Header       Content       Footer
          │            │            │
          └────────────┼────────────┘
                       ▼
                Generated HTML
                       │
                       ▼
                    Browser
```

---

# 🆚 Without Templates vs With Templates

## ❌ Without Templates

```text
index.php
    └── 100 lines

about.php
    └── 100 lines

contact.php
    └── 100 lines
```

If the navbar changes:

```text
Modify index.php
Modify about.php
Modify contact.php
```

This creates unnecessary maintenance.

---

## ✅ With Templates

```text
templates/
├── header.php
├── navbar.php
└── footer.php

index.php
about.php
contact.php
```

If the navbar changes:

```text
Modify navbar.php
```

All pages automatically use the updated component.

---

# ⭐ Advantages of PHP Templates

### ♻️ 1. Code Reusability

Write common components once.

### 🧹 2. Less Duplicate Code

Avoid copying the same HTML into multiple files.

### 🔧 3. Easy Maintenance

Changes can be made in one place.

### 📦 4. Better Organization

Separate:

```text
Layout
Components
Configuration
Application Logic
```

### ⚡ 5. Faster Development

New pages can be created quickly using existing components.

### 🎨 6. Consistent UI

All pages can use the same header, navbar, footer, and layout.

---

# ⚠️ Common Mistakes

## ❌ Repeating HTML

Avoid copying the same header/footer into every page.

```text
index.php → header code
about.php → header code
contact.php → header code
```

Prefer:

```php
require_once "templates/header.php";
```

---

## ❌ Incorrect Relative Paths

This can cause problems:

```php
require_once "config.php";
```

when the file is located elsewhere.

A safer approach can be:

```php
require_once __DIR__ . "/config.php";
```

or an appropriate absolute/project-relative path.

---

## ❌ Mixing Too Much Logic With Presentation

Avoid putting large amounts of business logic directly inside HTML templates.

Instead:

```text
Application Logic
       ↓
Prepare Data
       ↓
Template
       ↓
Display Data
```

---

# 💡 Best Practices

### ✅ Keep templates focused

Templates should primarily handle presentation.

### ✅ Use `require_once` for essential shared files

```php
require_once "config.php";
```

### ✅ Organize templates into directories

```text
templates/
components/
layouts/
```

### ✅ Use meaningful file names

Good:

```text
header.php
footer.php
navbar.php
sidebar.php
```

Avoid:

```text
file1.php
abc.php
test2.php
```

### ✅ Use `__DIR__` when appropriate

```php
require_once __DIR__ . "/templates/header.php";
```

### ✅ Keep configuration separate

```text
config/
    config.php
```

---

# 📝 Important Concepts

| Concept        | Purpose                     |
| -------------- | --------------------------- |
| Template       | Reusable PHP/HTML structure |
| `include`      | Include another file        |
| `require`      | Include an essential file   |
| `include_once` | Include a file once         |
| `require_once` | Require a file once         |
| `<?= ?>`       | Short echo syntax           |
| `__DIR__`      | Current directory           |
| Component      | Reusable UI section         |
| Layout         | Overall page structure      |

---

# 🎯 Exam Questions

## Short Answer

1. What is a PHP template?
2. Why are templates used in PHP?
3. What is code reusability?
4. What is the purpose of `include`?
5. What is the purpose of `require`?
6. What is the difference between `include` and `require`?
7. What is `require_once`?
8. What is the purpose of `__DIR__`?
9. What is the short echo syntax in PHP?
10. What are the advantages of PHP templates?

---

## Long Answer

### Q1. Explain PHP templates with a suitable example.

A good answer should include:

```text
Definition
     ↓
Need for Templates
     ↓
Project Structure
     ↓
include / require
     ↓
Example
     ↓
Advantages
```

---

### Q2. Explain how reusable templates reduce code duplication.

Mention:

* Header
* Navbar
* Footer
* Components
* `require_once`
* Maintenance
* Reusability

---

# 🧪 Practice Programs

### 🟢 Beginner

### 01 — Basic Header

Create:

```text
header.php
index.php
```

Include `header.php` inside `index.php`.

---

### 02 — Footer Template

Create a reusable:

```text
footer.php
```

and include it on two different pages.

---

### 🟡 Intermediate

### 03 — Complete Website Layout

Create:

```text
header.php
navbar.php
footer.php
index.php
about.php
contact.php
```

Use reusable templates to build all pages.

---

### 04 — Dynamic Page Title

Create a common header and allow each page to provide its own:

```php
$pageTitle
```

---

### 🔴 Advanced

### 05 — Student Portal Template

Build:

```text
student-portal/
│
├── index.php
├── dashboard.php
├── profile.php
│
├── config/
│   └── config.php
│
└── templates/
    ├── header.php
    ├── navbar.php
    ├── sidebar.php
    └── footer.php
```

Use `require_once` and dynamic PHP data.

---

# ⚡ Quick Revision

```text
PHP TEMPLATES
      │
      ▼
Reusable PHP / HTML Files
      │
      ├── Header
      ├── Navbar
      ├── Sidebar
      ├── Footer
      └── Components
      │
      ▼
include / require
      │
      ▼
Individual Pages
      │
      ▼
Generated HTML
      │
      ▼
Browser
```

---

# 🧠 One-Minute Summary

> **PHP templates allow developers to create reusable HTML/PHP components that can be included across multiple pages.**

### Remember:

```php
include "file.php";
```

```php
require "file.php";
```

```php
include_once "file.php";
```

```php
require_once "file.php";
```

```php
<?= $variable ?>
```

```php
__DIR__
```

---

## 🏁 Final Takeaway

PHP templates are an important technique for creating **clean, reusable, maintainable, and scalable web applications**.

Instead of repeating common code across multiple pages:

```text
❌ Copy → Paste → Modify → Repeat
```

we use:

```text
✅ Create Once → Reuse Everywhere
```

This is the fundamental idea behind **template-based web development**.

---

<div align="center">

### 🧩 CREATE ONCE • REUSE EVERYWHERE • MAINTAIN EASILY

**Next Topic → PHP Constants**

</div>
