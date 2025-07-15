<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head><title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                padding-top: 70px;
                background-color: #f9f9f9;
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
                                <li><a class="dropdown-item" href="read_posts.php">View All Posts</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <h1>Welcome User, <?= htmlspecialchars($_SESSION['username']) ?></h1>
        <img src="post_image.jpg" width="900px" height="420px">
    </body>
</html>