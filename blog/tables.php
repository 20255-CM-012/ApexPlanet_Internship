<?php
include 'db_connect.php';

// SQL to create users table with role support
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor', 'user') DEFAULT 'user'
)";

// SQL to create posts table
$sql_posts = "CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Execute both queries
if ($conn->query($sql_users) === TRUE && $conn->query($sql_posts) === TRUE) {
    echo "";
} else {
    echo "Error creating tables: " . $conn->error;
}
?>