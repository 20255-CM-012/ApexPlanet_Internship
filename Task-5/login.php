<?php
session_start();
require 'db_connect.php'; // Contains $conn

// Redirect with error
function redirectWithError($key) {
    header("Location: login.html?error=$key");
    exit();
}

// Handle only POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirectWithError("invalid_request");
}

// Sanitize input
$email = trim($_POST["email"] ?? '');
$password = trim($_POST["password"] ?? '');

if (empty($email) || empty($password)) {
    redirectWithError("empty_fields");
}

// Fetch user
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Database error: " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    // Redirect based on role
    switch ($user['role']) {
        case 'admin':
            header("Location: admin_dashboard.php");
            break;
        case 'editor':
            header("Location: editor_dashboard.php");
            break;
        case 'user':
        default:
            header("Location: user_dashboard.php");
            break;
    }
    exit();
} else {
    redirectWithError("invalid_credentials");
}
?>