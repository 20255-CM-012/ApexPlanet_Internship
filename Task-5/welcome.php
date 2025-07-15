<?php
session_start();

// Check login
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.html");
    exit();
}

$username = htmlspecialchars($_SESSION['username']);
$role = htmlspecialchars($_SESSION['role']);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Welcome Page</title>
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
            img{
                padding-top: 10px;;
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
            .success-message {
                margin: 20px auto;
                width: 80%;
                padding: 15px;
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
                border-radius: 5px;
                text-align: center;
            }
        </style>
    </head>
    <body>

        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <span class="navbar-text text-white fw-bold">TextQuarry</span>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" 
                        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link disabled text-white" href="#">Hello <?= $username ?> (<?= ucfirst($role) ?>)</a>
                        </li>
            
                        <?php if (in_array($role, ['admin', 'editor'])): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="postDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Posts
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="postDropdown">
                                    <?php if ($role === 'admin' || $role === 'editor'): ?>
                                        <li><a class="dropdown-item" href="create_post.php">Create Post</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item" href="read_posts.php">View All Posts</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                                    
                        <?php if ($role === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin_panel.php">Admin Panel</a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Success Message -->
    <?php
    if (isset($_SESSION['post_success'])) {
        echo "<div id='successMessage' class='success-message'>" . $_SESSION['post_success'] . "</div>";
        unset($_SESSION['post_success']);
    }
    ?>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Welcome to TextQuarry</h1>
        <p>
            <?php if ($role === 'admin'): ?>
                You have full access to manage the site.
            <?php elseif ($role === 'editor'): ?>
                You can create and manage posts.<br>
                <img src="post_image.jpg" width="900px" height="420px">
            <?php else: ?>
                Feel free to browse and enjoy the content.
            <?php endif; ?>
        </p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto-hide success message -->
    <script>
        setTimeout(() => {
            const msg = document.getElementById("successMessage");
            if (msg) msg.style.display = "none";
        }, 3000);
    </script>

    </body>
</html>