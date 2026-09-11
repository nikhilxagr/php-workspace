# 🖥️ Server-Side Scripting

> **Part 1 of 12 — Creating Web Applications Using Server-Side Scripting**

---

## 📌 What is Server-Side Scripting?

**Server-side scripting** is a web development technique in which the program code is executed on the **web server** instead of directly in the user's browser.

The server processes the request, executes the server-side code, generates a response, and sends the result back to the browser.

### Simple Definition

> **Server-side scripting means executing application logic on the server before sending the final response to the client.**

---

## 🌐 Basic Web Request Flow

When a user visits a dynamic website, the process generally looks like this:

```text
┌─────────────────┐
│   User / Client │
│    Browser      │
└────────┬────────┘
         │
         │  HTTP Request
         ▼
┌─────────────────┐
│   Web Server    │
└────────┬────────┘
         │
         │ Execute
         │ Server-Side Code
         ▼
┌─────────────────┐
│ PHP / Server    │
│ Application     │
└────────┬────────┘
         │
         │ Generate Response
         ▼
┌─────────────────┐
│   HTML / JSON   │
│    Response     │
└────────┬────────┘
         │
         │ HTTP Response
         ▼
┌─────────────────┐
│     Browser     │
│     Display     │
└─────────────────┘
```

---

# 🧠 How Server-Side Scripting Works

Consider a user visiting:

```text
https://example.com/profile.php
```

The following process takes place:

### 1️⃣ Client Sends Request

The browser sends an HTTP request to the server.

```text
Browser → Server
```

---

### 2️⃣ Server Receives Request

The web server receives the request and identifies the requested resource.

```text
Request:
GET /profile.php
```

---

### 3️⃣ Server Executes PHP

If the requested file contains PHP code, the PHP runtime processes the code.

```php
<?php

$name = "Nikhil";

echo "<h1>Welcome, $name!</h1>";

?>
```

The PHP code runs **on the server**.

---

### 4️⃣ Server Generates Response

The PHP code generates output such as:

```html
<h1>Welcome, Nikhil!</h1>
```

---

### 5️⃣ Response is Sent to Browser

The server sends the generated response back to the browser.

```text
Server → Browser
```

---

### 6️⃣ Browser Displays the Result

The browser renders the HTML:

```text
┌─────────────────────────────┐
│                             │
│       Welcome, Nikhil!      │
│                             │
└─────────────────────────────┘
```

The browser **does not receive the original PHP source code**.

---

# 🐘 PHP as a Server-Side Language

**PHP** is a server-side scripting language primarily used for developing dynamic web applications.

PHP code is written inside:

```php
<?php

// PHP code

?>
```

### Example

```php
<?php

$name = "Nikhil";
$age = 21;

echo "Name: $name";
echo "<br>";
echo "Age: $age";

?>
```

The PHP code executes on the server.

The browser receives the generated output.

---

# 🔥 Static vs Dynamic Websites

Understanding server-side scripting becomes easier by comparing **static** and **dynamic** websites.

## 📄 Static Website

A static website usually sends pre-written HTML directly to the browser.

```text
HTML File
   ↓
Web Server
   ↓
Browser
```

Example:

```html
<h1>Welcome to My Website</h1>
```

The content generally remains the same unless the HTML file is manually changed or another mechanism modifies it.

---

## ⚡ Dynamic Website

A dynamic website generates content based on:

* User input
* Database information
* Authentication
* Time and date
* User account
* Application logic
* API data
* Other server-side conditions

```text
User Request
     ↓
Web Server
     ↓
PHP Application
     ↓
Database / Logic
     ↓
Generated Response
     ↓
Browser
```

---

# 💻 Example of Dynamic Content

```php
<?php

$name = "Nikhil";

echo "<h1>Hello, $name!</h1>";

?>
```

If the value changes:

```php
$name = "Rahul";
```

The generated output becomes:

```text
Hello, Rahul!
```

The same PHP program can therefore generate different responses depending on the data.

---

# 🗄️ Server-Side Scripting with a Database

One of the biggest advantages of server-side applications is their ability to communicate with databases.

For example:

```text
             User
              │
              ▼
          Web Browser
              │
              │ Request
              ▼
         PHP Application
              │
              │ Query
              ▼
          Database
              │
              │ Data
              ▼
         PHP Application
              │
              │ Response
              ▼
          Web Browser
```

### Example Scenario

A user opens their profile page.

PHP can:

1. Identify the logged-in user.
2. Query the database.
3. Retrieve the user's information.
4. Generate HTML.
5. Send the result to the browser.

---

# 🔐 Why Server-Side Processing is Important

Server-side code is useful because sensitive application logic does not need to be exposed to the browser.

For example:

```text
Database credentials
Business logic
Authentication logic
Authorization checks
Server configuration
Database queries
```

These operations can remain on the server.

> ⚠️ **Important:** Server-side execution does not automatically make an application secure. Proper authentication, authorization, validation, output escaping, secure database access, and other security practices are still required.

---

# ⚖️ Client-Side vs Server-Side Scripting

| Feature           | Client-Side                         | Server-Side                           |
| ----------------- | ----------------------------------- | ------------------------------------- |
| Execution         | Browser                             | Web server                            |
| Common languages  | JavaScript, HTML, CSS               | PHP, Python, Ruby, Java, C#           |
| Database access   | Usually through APIs/backend        | Direct application-side access        |
| Source visibility | Client code is delivered to browser | Server code is normally not sent      |
| Main purpose      | UI and browser interaction          | Application logic and data processing |
| Example           | Form validation, animations         | Login, database operations            |

---

# 🌍 Real-World Examples

Server-side scripting is commonly used for:

### 🔐 Authentication

```text
Login Form
    ↓
Server
    ↓
Verify Credentials
    ↓
Database
    ↓
Login Success / Failure
```

---

### 🛒 E-Commerce

```text
Product Page
    ↓
PHP Application
    ↓
Database
    ↓
Product Information
    ↓
Browser
```

---

### 👤 User Profiles

```text
User Request
    ↓
Server
    ↓
Find User
    ↓
Database
    ↓
Generate Profile
    ↓
Browser
```

---

### 📝 Form Processing

```text
HTML Form
    ↓
User Submits
    ↓
PHP
    ↓
Validate Input
    ↓
Process Data
    ↓
Database
```

---

# 🧩 PHP Server-Side Example

Consider a simple greeting application:

```php
<?php

$name = "Nikhil";
$currentHour = date("H");

if ($currentHour < 12) {
    $greeting = "Good Morning";
} elseif ($currentHour < 18) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Greeting</title>
</head>

<body>

    <h1>
        <?= $greeting ?>, <?= $name ?>!
    </h1>

</body>

</html>
```

### What's happening?

PHP:

1. Gets the user's name.
2. Gets the current hour.
3. Determines the appropriate greeting.
4. Generates HTML.
5. Sends the resulting page to the browser.

---

# 🔄 Server-Side Processing Example

Suppose the PHP code is:

```php
<?php

$name = "Nikhil";

echo "<h1>Hello, $name!</h1>";

?>
```

The browser ultimately receives something similar to:

```html
<h1>Hello, Nikhil!</h1>
```

### Important

The browser does **not** normally receive:

```php
$name = "Nikhil";
echo "<h1>Hello, $name!</h1>";
```

Instead, it receives the generated response.

---

# ⭐ Advantages of Server-Side Scripting

### 1. 🔄 Dynamic Content

Generate different content based on users and data.

### 2. 🗄️ Database Integration

Applications can communicate with databases.

### 3. 🔐 Centralized Application Logic

Important business logic can be handled on the server.

### 4. 👤 User Authentication

Server-side applications can manage:

* Login
* Sessions
* User accounts
* Permissions

### 5. 📦 Code Reusability

Server-side applications can use reusable:

* Functions
* Templates
* Classes
* Components

### 6. 🌐 API Integration

Server-side applications can communicate with external services and APIs.

---

# 🧠 Key Terms

| Term                 | Meaning                               |
| -------------------- | ------------------------------------- |
| **Client**           | Device/browser making a request       |
| **Server**           | System that processes requests        |
| **HTTP**             | Protocol used for web communication   |
| **Request**          | Message sent by client to server      |
| **Response**         | Message sent by server to client      |
| **Server-Side Code** | Code executed on the server           |
| **Dynamic Content**  | Content generated based on data/logic |
| **PHP**              | Server-side scripting language        |
| **Database**         | System used to store application data |

---

# 📝 Important Points

> ### Remember

* Server-side code runs on the **server**.
* PHP is a **server-side scripting language**.
* The browser sends an **HTTP request**.
* The server processes the request.
* PHP can interact with databases and application logic.
* The server sends the generated response back to the browser.
* The browser displays the final response.
* Server-side scripting is essential for building **dynamic web applications**.

---

# 🎯 Exam Questions

### Short Answer

1. What is server-side scripting?
2. What is PHP?
3. Where does server-side code execute?
4. What is the difference between client-side and server-side scripting?
5. What is a dynamic website?
6. Why is server-side scripting used?
7. Explain the request-response cycle.
8. Give examples of server-side scripting languages.

### Long Answer

> **Explain server-side scripting with a suitable diagram and example.**

### Expected Structure

```text
Definition
    ↓
Working
    ↓
Request-Response Cycle
    ↓
Diagram
    ↓
PHP Example
    ↓
Advantages
    ↓
Conclusion
```

---

# ⚡ Quick Revision

```text
SERVER-SIDE SCRIPTING
        │
        ▼
Code executes on SERVER
        │
        ▼
Process Request
        │
        ├── Application Logic
        ├── Database
        ├── Authentication
        └── Data Processing
        │
        ▼
Generate Response
        │
        ▼
Send to Browser
        │
        ▼
Display Result
```

---

## 💡 One-Line Definition

> **Server-side scripting is the execution of web application code on a server to process client requests and generate dynamic responses.**

---

<div align="center">

### 🐘 PHP → Server → Process → Response → Browser

**Next Topic → PHP Templates**

</div>
