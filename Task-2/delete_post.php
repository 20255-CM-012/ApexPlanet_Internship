<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = intval($_POST['post_id'] ?? 0);

    if ($post_id <= 0) {
        $_SESSION['post_success'] = "Invalid post ID.";
        header("Location: read_posts.php");
        exit;
    }

    $sql = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);

    if ($stmt->execute()) {
        $_SESSION['post_success'] = "Post deleted successfully!";
    } else {
        $_SESSION['post_success'] = "Failed to delete post.";
    }

    header("Location: read_posts.php");
    exit;
   } else {
        $_SESSION['post_success'] = "Invalid request method.";
        header("Location: read_posts.php");
        exit;
    }
?>