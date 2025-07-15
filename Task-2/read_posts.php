<?php
session_start();
include 'db_connect.php';

// Get query & page
$query = trim($_GET['query'] ?? '');
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Count total posts
if (!empty($query)) {
    $countSql = "SELECT COUNT(*) AS total FROM posts WHERE title LIKE ? OR content LIKE ?";
    $stmt = $conn->prepare($countSql);
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} else {
    $countSql = "SELECT COUNT(*) AS total FROM posts";
    $stmt = $conn->prepare($countSql);
}
$stmt->execute();
$countResult = $stmt->get_result();
$totalPosts = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalPosts / $limit);

// Fetch posts for this page
if (!empty($query)) {
    $sql = "SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $searchTerm, $searchTerm, $offset, $limit);
} else {
    $sql = "SELECT * FROM posts ORDER BY created_at DESC LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $offset, $limit);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>All Posts</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { 
                padding-top: 70px; 
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
            .post {
                background: #fff;
                border-radius: 10px;
                padding: 20px;
                margin: 20px auto;
                width: 80%;
                box-shadow: 0 0 5px rgba(0,0,0,0.1);
            }
            .title {
                font-size: 22px;
                font-weight: bold;
            }
            .content {
                margin-top: 10px;
            }   
            .search-bar {
                text-align: center;
                margin-top: 20px;
            }
            .search-bar input[type="text"] {
                padding: 8px 30px 8px 8px;
                width: 300px;
                border-radius: 4px;
                border: 1px solid #ccc;
            }
            .search-bar button {
                padding: 6px 12px;
                border-radius: 4px;
                background-color: #008CBA;
                color: white;
                border: none;
                margin-left: 10px;
            }
            .search-bar button:hover {
                background-color: #005f73;
            }
            .clear-btn {
                position: absolute;
                right: 8px;
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
                color: #999;
                font-size: 18px;
                display: none;
                background: white;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                text-align: center;
                line-height: 18px;
                font-weight: bold;
            }   
            .clear-btn:hover {
                color: #000;
            }
        </style>
    </head>
    <body>

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <span class="navbar-text text-white fw-bold">TextQuarry</span>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white">Hello <?= htmlspecialchars($_SESSION['username']) ?>!</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Posts</a>
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

        <!-- Flash Message -->
        <?php if (isset($_SESSION['post_success'])): ?>
        <div class="alert alert-success text-center mt-3" id="flash-message">
            <?= htmlspecialchars($_SESSION['post_success']); unset($_SESSION['post_success']); ?>
        </div>
        <script>
            setTimeout(() => {
                const flash = document.getElementById('flash-message');
                if (flash) flash.style.display = 'none';
            }, 3000);
        </script>
        
        <?php endif; ?>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="GET" action="read_posts.php" id="searchForm" style="display: inline-flex; align-items: center; gap: 10px;">
                <div style="position: relative;">
                    <input type="text" name="query" id="searchInput" placeholder="Search posts..." value="<?= htmlspecialchars($query) ?>" required>
                    <span id="clearBtn" class="clear-btn" title="Clear">&times;</span>
                </div>
                <button type="submit">Search</button>
            </form>
        </div>
                
        <h2 class="text-center mt-4">
            <?= !empty($query) ? 'Search Results for: "' . htmlspecialchars($query) . '"' : 'All Posts'; ?>
        </h2>
                
        <!-- Posts List -->
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="post">
                    <div class="title"><?= htmlspecialchars($row['title']) ?></div>
                    <div class="content"><?= nl2br(htmlspecialchars($row['content'])) ?></div>
                    <a href="edit_post.php?id=<?= $row['id'] ?>" class="btn btn-primary me-2 mt-2">Edit</a>
                    <form method="POST" action="delete_post.php" onsubmit="return confirm('Are you sure you want to delete this post?');" style="display:inline-block;">
                        <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-danger mt-2">Delete</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center mt-4">No posts found.</p>
        <?php endif; ?>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="d-flex justify-content-center mb-5">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?query=<?= urlencode($query) ?>&page=<?= $page - 1 ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?query=<?= urlencode($query) ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?query=<?= urlencode($query) ?>&page=<?= $page + 1 ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
                    
        <!-- JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            const input = document.getElementById("searchInput");
            const clearBtn = document.getElementById("clearBtn");
            const form = document.getElementById("searchForm");
                    
            function toggleClear() {
                clearBtn.style.display = input.value ? "block" : "none";
            }
        
            clearBtn.addEventListener("click", () => {
                input.value = "";
                toggleClear();
                form.submit();
            });
        
            input.addEventListener("input", toggleClear);
            window.addEventListener("DOMContentLoaded", toggleClear);
        </script>

    </body>
</html>