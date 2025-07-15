<?php
session_start();
include 'db_connect.php';

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? '');
    $content = trim($_POST["content"] ?? '');

    if (empty($title) || empty($content)) {
        $_SESSION['post_success'] = "All fields are required.";
        header("Location: welcome.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("ss", $title, $content);
        if ($stmt->execute()) {
            $_SESSION['post_success'] = "Post submitted successfully!";
        } else {
            $_SESSION['post_success'] = "Error: Could not submit post.";
        }
        $stmt->close();
    } else {
        $_SESSION['post_success'] = "Error: Database error.";
    }

    $conn->close();
    header("Location: welcome.php");
    exit();
}
?>

<!-- Only visible when method is GET -->
<!DOCTYPE html>
<html>
    <head>
        <title>Create Post</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f4f4f4;
                padding-top: 80px;
                display: flex;
                justify-content: center;
                align-items: flex-start;
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
            .nav-item.dropdown:hover .dropdown-menu {
                display: block;
            }
            .main {
                background-color: #fff;
                width: 350px;
                padding: 30px;
                border-radius: 10px;     
                box-shadow: 0px 4px 8px rgba(0,0,0,0.2); 
                margin-top: 50px;
            }
            .main h1 {
                text-align: center;
            }
            input[name="title"],
            textarea[name="content"] {
                width: 100%;
                padding: 12px;
                margin: 8px 0 20px;
                border: 1px solid #ccc;
                border-radius: 6px;
            }
            input[type="submit"] {
                width: 100%;
                padding: 12px;
                background-color: #007BFF;
                color: white;
                border: none;
                border-radius: 6px;
                cursor: pointer;
            }
            input[type="submit"]:hover {
                background-color: #0056b3;
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
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#">Hello <?php echo htmlspecialchars($_SESSION['username']); ?>!</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="postDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Posts</a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="postDropdown">
                                <li><a class="dropdown-item" href="create_post.php">Create Post</a></li>
                                <li><a class="dropdown-item" href="welcome.php">View All Posts</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Post Form -->
        <div class="main">
            <h1>Create Post</h1>
            <form action="create_post.php" method="POST">
                <label>Title:</label>
                <input type="text" name="title" required>
                <label>Content:</label>
                <textarea name="content" rows="5" required></textarea>
                <input type="submit" value="Submit">
            </form>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>