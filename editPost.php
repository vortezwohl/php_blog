<?php
require_once 'config/database.php';
session_start();
if (!isset($_SESSION['logged_in']))
    $_SESSION['logged_in'] = false;
else {
    if (!$_SESSION['logged_in']) {
        header("Location: login.php");
    }
}
$post = null;
if(isset($_GET['id'])){
    if (isset($pdo)) {
        $stmt = $pdo->prepare('SELECT * FROM post WHERE id = ?');
        $stmt->execute([$_GET['id']]);
        $post = $stmt->fetch();
    }
}
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="static/css/Post.css">
    <link rel="icon" href="./static/favicon.ico" type="image/x-icon">
    <title>Wohl's Blog</title>
</head>
<body>
    <?php
    if(isset($_GET['id']))
        echo "<h2>Edit Post #".$post['id']."</h2>";
    else
        echo "<h2>Write Your Post</h2>";
    ?>

    <form method="post">
        <input type="hidden" name="id" id="id" value="<?php
        if(isset($_GET['id']))
            echo $post['id'];
        ?>">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required <?php
        if(isset($_GET['id'])){
            echo 'value='.$post['title'];
        }
        ?>><br><br>
        <label for="content">Content:</label>
        <textarea name="content" id="content" required><?php
            if(isset($_GET['id'])){
                echo $post['content'];
            }
            ?></textarea><br><br>
        <button type="submit" name="submit">Save / Publish</button>
    </form>
    <?php
    if(isset($_GET['id'])){
        echo "<div><button 
                    style='background-color: rgba(255,38,0,0.91)'
                    onclick='window.location.assign(\"http://www.test.com/deletePost.php?id=".$_GET['id']."\")'>
                    Delete Post
                    </button></div>";
    }
    ?>
</body>
</html>
<?php
$htmlContent = ob_get_clean();
echo $htmlContent;
if (isset($pdo)) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // update
        if (!empty($_POST['id'])) {
            $stmt = $pdo->prepare("UPDATE post SET title = ?, content = ? WHERE id = ?");
            $stmt->execute([$_POST['title'], $_POST['content'], $_POST['id']]);
            if ($stmt->rowCount() > 0) {
                echo '<script>alert("Post saved");window.location.replace("http://www.test.com")</script>';
            }
        }
        // insert
        else {
            $stmt = $pdo->prepare("INSERT INTO post (title, content, author_id, is_published) VALUES (?, ?, ?, ?)");
            try {
                $stmt->execute([$_POST['title'], $_POST['content'], $_SESSION['uid'], 1]);
                echo '<script>alert("Post published");window.location.replace("http://www.test.com")</script>';
            } catch (PDOException $e) {
                echo '<script>alert("Post failed");</script>';
            }
        }
    }
}
?>