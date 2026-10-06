-- ==========================================================
-- Library Management System Database Schema
-- Database: library_db
-- Compatible with XAMPP MySQL and phpMyAdmin
-- ==========================================================

-- 1. Create and select the database
CREATE DATABASE IF NOT EXISTS library_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE library_db;

-- 2. Drop existing tables if needed (in proper foreign key dependency order)
DROP TABLE IF EXISTS issued_books;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS users;

-- 3. Users Table (Stores user credentials with role: student, teacher, admin)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Books Table (Stores book catalog and inventory)
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(150) NOT NULL,
    category VARCHAR(100),
    isbn VARCHAR(50) UNIQUE,
    quantity INT NOT NULL DEFAULT 1,
    available_quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Issued Books Table (Tracks borrowed books with foreign key relationships)
CREATE TABLE issued_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    issue_date DATE NOT NULL,
    return_date DATE NULL,
    status ENUM('issued', 'returned') NOT NULL DEFAULT 'issued',
    CONSTRAINT fk_issued_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    CONSTRAINT fk_issued_book FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Insert Sample Books (10 books for testing)
INSERT INTO books (title, author, category, isbn, quantity, available_quantity) VALUES
('Introduction to Algorithms', 'Thomas H. Cormen', 'Computer Science', '978-0262033848', 5, 5),
('Clean Code: A Handbook of Agile Software Craftsmanship', 'Robert C. Martin', 'Software Engineering', '978-0132350884', 4, 4),
('The C Programming Language', 'Brian W. Kernighan, Dennis M. Ritchie', 'Programming', '978-0131103627', 6, 6),
('Database System Concepts', 'Abraham Silberschatz', 'Database', '978-0073523323', 5, 5),
('Operating System Concepts', 'Abraham Silberschatz, Peter B. Galvin', 'Operating Systems', '978-1118063330', 4, 4),
('Computer Networks', 'Andrew S. Tanenbaum', 'Networking', '978-0132126953', 3, 3),
('Head First Java', 'Kathy Sierra, Bert Bates', 'Programming', '978-0596009205', 5, 5),
('PHP and MySQL Web Development', 'Luke Welling, Laura Thomson', 'Web Development', '978-0672329166', 4, 4),
('Discrete Mathematics and Its Applications', 'Kenneth H. Rosen', 'Mathematics', '978-0073383095', 3, 3),
('Python Crash Course', 'Eric Matthes', 'Programming', '978-1593279288', 6, 6);

-- 7. Insert Sample Users (Admin, Teacher, Student)
-- Passwords:
-- admin@library.com   => admin123
-- teacher@library.com => teacher123
-- student@library.com => student123
INSERT INTO users (name, email, password, role) VALUES
('System Administrator', 'admin@library.com', '$2y$10$.vbEb/uVjLDTDlhnOgfFlO1aHiHcwmEfgi4575NJYkQSYZhvBP0iK', 'admin'),
('Prof. Sharma', 'teacher@library.com', '$2y$10$G77VVcsSCXh7NTA.inViZeUGIHow4SYBOYXWJiX2mPbj2jZIe92XO', 'teacher'),
('Rahul Verma', 'student@library.com', '$2y$10$a8gDaifEHE3u52SeMdjUUusuBdV6GyT8LyPv1kQoQvP2JB/KIUnOi', 'student');

