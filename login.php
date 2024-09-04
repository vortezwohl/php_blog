<?php
session_start();
require_once 'config/database.php';
ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./static/css/Login.css">
        <link rel="icon" href="./static/favicon.ico" type="image/x-icon">
        <title>Wohl's Blog</title>
    </head>
    <body>
        <div>
            <form method="GET">
                <h2>Welcome to My Blog</h2>
                <div class="input-group">
                    <label for="name">Username:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="input-group">
                    <label for="pwd">Password:</label>
                    <input type="password" id="pwd" name="pwd" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <button onclick="toRegister()">Register</button>
        </div>
    </body>
    <script>
        const toRegister = () => {
            window.location.assign('http://www.test.com/register.php')
        }
    </script>
    </html>
<?php
$htmlContent = ob_get_clean();
echo $htmlContent;
if(isset($_GET['name'])){
    if (isset($pdo)) {
        $stmt = $pdo->prepare('SELECT * FROM user WHERE name = ?');
        $stmt->execute([$_GET['name']]);
        $user = $stmt->fetch();
        if (isset($user['pwd']) && $user['pwd'] === hash('sha256', $_GET['pwd'])) {
            $_SESSION['uid'] = $user['id'];
            $_SESSION['logged_in'] = true;
            header('Location: http://www.test.com');
            exit();
        } else {
            echo '<script>alert("Login failed")</script>';
        }
    }
}
?>