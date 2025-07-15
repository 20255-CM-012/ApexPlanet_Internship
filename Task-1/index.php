<!DOCTYPE html>
<html>
    <head>
        <title>First PHP Program</title>
        <style>
            body, html {
                height: 100%;
                margin: 0;
                font-family: Arial, sans-serif;
            }

            .main {
                width: 900px;
                height: auto;
                margin: auto;
                padding: 20px;
                background-color: #BDDDFC;
                border: 4px solid #6A89A7;
                border-radius: 8px;
                box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            }

            h1 {
                text-align: center;
                margin-bottom: 20px;
            }

            h2 {
                margin-left: 35px;
            }

            ul {
                margin-left: 60px;
            }

            li {
                font-size: 20px;
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <?php
        echo '
        <div class="main">
            <h1>My Internship Task-1</h1>
            <h2>My task deliverables:</h2>
            <ul>
                <li>Downloading and installing XAMPP server.</li>
                <li>Verifying the installation by running Apache and MySQL services and accessing http://localhost in Google Chrome.</li>
                <li>Choosing a code editor like Visual Studio Code and executing the code in the browser.</li>
                <li>A working local server environment.</li>
                <li>A Git repository with an initial commit.</li>
            </ul>
        </div>
        ';
        ?>
    </body>
</html>