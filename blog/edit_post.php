<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$post_id = intval($_GET['id']);
$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Post not found.";
    exit();
}

$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Post</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                padding-top: 70px;
                background-color: #f4f4f4;
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
            .form-container {
                background: #fff;
                padding: 30px;
                border-radius: 10px;
                width: 500px;
                margin: 0 auto;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            textarea, input[type="text"] {
                resize: none;
            }
        </style>
    </head>
    <body>

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <span class="navbar-text text-white fw-bold">TextQuarry</span>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white">Hello <?php echo htmlspecialchars($_SESSION['username']); ?>!</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="postDropdown" role="button" data-bs-toggle="dropdown">Posts</a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="create_post.php">Create Post</a></li>
                                <li><a class="dropdown-item" href="read_posts.php">View All Posts</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Edit Form -->
        <div class="form-container mt-5">
            <h3 class="text-center mb-4">Edit Post</h3>
            <form method="POST" action="update_post.php">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="form-control" rows="6" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Update Post</button>
            </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
