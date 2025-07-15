<html>
    <head>
        <title>Search Posts</title>
        <style>
            body { 
                font-family: Arial, sans-serif; margin: 30px; background: #f9f9f9; 
            }
            .post {
                background: #fff;
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 15px;
                box-shadow: 0 0 5px rgba(0,0,0,0.1);
            }
            .title { font-size: 20px; font-weight: bold; }
            .meta { color: #777; font-size: 13px; }
            .content { margin-top: 10px; }
        </style>
    </head>
    <body>
        <?php
        session_start();
        include 'db_connect.php'; // make sure this file connects $conn to DB

        $query = trim($_GET['query'] ?? '');

        if (empty($query)) {
            echo "No search query provided.";
            exit;
        }

        // Search in both title and content
        $sql = "SELECT * FROM posts WHERE title LIKE ? OR content LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = '%' . $query . '%';
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();

        $result = $stmt->get_result();
        ?>

        <h2>Search Results for: "<?php echo htmlspecialchars($query); ?>"</h2>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='post'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
                echo "</div><hr>";
            }
        } else {
            echo "No results found.";
        }
        ?>
    </body>
</html>