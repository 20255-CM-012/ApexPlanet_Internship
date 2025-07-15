<?php
session_start();
include 'db_connect.php';
// Removed 'tables.php' to prevent automatic connection close issues

$error = '';

// Form submission logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $role = $_POST["role"] ?? 'user'; // Default role is user

    // Optional: Restrict role selection (prevent user from choosing 'admin')
    $valid_roles = ['user', 'editor']; // Allow only 'user' and 'editor'
    if (!in_array($role, $valid_roles)) {
        $role = 'user';
    }

    // Validations
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check if email already exists
        $checkSql = "SELECT id FROM users WHERE email = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $error = "Email is already registered.";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                header("Location: login.html?success=registered");
                exit();
            } else {
                $error = "Registration failed: " . $stmt->error;
            }
            $stmt->close();
        }
        $checkStmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Sign Up</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                padding-top: 70px;
                background-color: #f9f9f9;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }
            .nav-item.dropdown:hover .dropdown-menu {
                display: block;
            }
            .navbar a {
                padding: 5px 12px;
                border-radius: 4px;
                background-color: #008CBA;
                color: white;
                text-decoration: none;
                margin-right: 5px;
            }
            .navbar a:hover {
                background-color: #005f73;
            }
            .hero {
                text-align: center;
                padding: 60px;
                background-color: #f8f9fa;
            }
            .hero h1 {
                font-size: 3rem;
            }
            .hero p {
                font-size: 1.2rem;
                color: #555;
            }
            .signup_form {
                background-color: #fff;
                width: 350px;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 8px rgba(0,0,0,0.1);
            }
            input, select {
                width: 100%;
                padding: 12px;
                margin: 8px 0 20px;
                border: 1px solid #ccc;
                border-radius: 6px;
            }
            input[type="submit"] {
                background-color: #007BFF;
                color: white;
                border: none;
                cursor: pointer;
            }
            input[type="submit"]:hover {
                background-color: #0056b3;
            }
            .switch-link {
                text-align: center;
                margin-top: 10px;
            }
            .switch-link a {
                color: #007BFF;
                text-decoration: none;
            }
            .switch-link a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>

        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <span class="navbar-text text-white fw-bold">TextQuarry</span>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav ms-auto"> <!-- ms-auto pushes items to the right -->
                        <li class="nav-item">
                            <a class="nav-link" href="login.html">Login</a>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="signup_form">
            <form method="POST" action="signup.php">
                <h2 class="text-center">Sign Up</h2>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <input type="password" name="password" placeholder="Password (min 6 chars)" required>
                
                <!-- Role Dropdown -->
                <select name="role" required>
                    <option value="editor" <?= ($_POST['role'] ?? '') === 'editor' ? 'selected' : '' ?>>Editor</option>
                    <option value="admin" <?= ($_POST['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin (restricted)</option>
                </select>
                
                <input type="submit" value="Sign Up">
            </form>
                
            <div class="switch-link">
                Already have an account? <a href="login.html">Login</a>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>