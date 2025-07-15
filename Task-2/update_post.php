<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $post_id = intval($_POST["post_id"]);
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    if (!empty($title) && !empty($content)) {
        $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $post_id);
        if ($stmt->execute()) {
            $_SESSION['post_success'] = "Post updated successfully!";
        } else {
            $_SESSION['post_success'] = "Failed to update post.";
        }
    } else {
        $_SESSION['post_success'] = "Title and content cannot be empty.";
    }

    header("Location: read_posts.php");
    exit();
}
?>
