
<!DOCTYPE html>
<html>
    <head>
        <title>First PHP program</title>
        <style>
            body, html{
                height: 100%;
                margin: 0;
            }
            .main{
                width: 900px;
                height: 400px;
                margin: auto;
                padding: 10px;
                background-color: #BDDDFC;
                border: 4px solid #6A89A7;
            }
            h2{
                margin: 0px 0px 0px 35px;
            }
            li{
                font-size: 25px;
            }

        </style>
    </head>
    <body>
        <?php
        echo '
            <div class="main">
            <h1 align="center">My Internship Task-1</h1>
            <h2>My task delivarables:</h2>
            <ul>
                <li>Downloading and installing XAMPP server.</li>
                <li>Verifying the installation by running Apache and MySQL services and accessing http://localhost in the Google Chrome.</li>
                <li>Choosing a code editor like Visual Studio Code and executing the code in the browser.</li>
                <li>A working local server environment.</li>
                <li>A Git repository with an initial commit.</li>
            </ul>
            </div>
            ';
        ?>
        
    </body>

</html>
