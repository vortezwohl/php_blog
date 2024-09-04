<?php
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
        <form method="POST">
            <h2>Create Your Account</h2>
            <div class="input-group">
                <label for="name">Username:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="input-group">
                <label for="pwd1">Password:</label>
                <input type="password" id="pwd1" name="pwd1" required>
            </div>
            <div class="input-group">
                <label for="pwd2">Confirm Password:</label>
                <input type="password" id="pwd2" name="pwd2" required>
            </div>
            <button type="submit">Create Account</button>
        </form>
    </div>
    </body>
    </html>

<?php
$htmlContent = ob_get_clean();
echo $htmlContent;
if(isset($_POST['name']) && isset($_POST['pwd1'] ) && isset($_POST['pwd2'])){
    if($_POST['pwd1'] == $_POST['pwd2'] && isset($pdo)){
        $stmt = $pdo->prepare('INSERT INTO user (name, pwd) VALUES (?, ?)');
        try {
            $stmt->execute([$_POST['name'], hash('sha256', $_POST['pwd1'])]);
            echo '<script>alert("Account created.");window.location.replace("http://www.test.com/login.php")</script>';
        } catch (PDOException $e) {
            echo '<script>alert("User '.$_POST['name'].' exists")</script>';
        }
    }
    else{
        echo '<script>alert("Conform your password again")</script>';
    }
}
?>