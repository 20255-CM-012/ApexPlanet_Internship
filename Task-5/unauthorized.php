<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Unauthorized Access</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8f9fa;
                padding-top: 80px;
                text-align: center;
            }
            .unauth-box {
                margin: 0 auto;
                padding: 40px;
                background: #fff;
                max-width: 500px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .unauth-box h3 {
                color: #dc3545;
            }
            .unauth-box a {
                text-decoration: none;
            }
        </style>
    </head>
    <body>

        <div class="unauth-box">
            <h3>Access Denied</h3>
            <p>You are not authorized to view this page.</p>
            <a href="welcome.php" class="btn btn-primary mt-3">Back to Dashboard</a>
        </div>
            
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>