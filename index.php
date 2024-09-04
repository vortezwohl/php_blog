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
function findAuthorById($id){
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}
function findCommentsByPostId($id){
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM comment WHERE pid = ? ORDER BY created_at ASC');
    $stmt->execute([$id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./static/css/ShowPost.css">
    <link rel="icon" href="./static/favicon.ico" type="image/x-icon">
    <title>Wohl's Blog</title>
</head>
<body>
<button style="position: fixed; top: 0" onclick="window.location.assign('http://www.test.com/editPost.php')">Create New Post</button>
<div class="main">
<?php
if (isset($pdo)) {
    $stmt = $pdo->query('SELECT * FROM post ORDER BY updated_at DESC');
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($posts as $post) {
        if($post['is_deleted'] === 0 && $post['is_published'] === 1) {
            echo '
            <div class="post">
            <div>
            <span style="font-size: 20px">' . $post['title'] . '</span>
            <span> by ' . findAuthorById($post['author_id'])['name'] . '</span>
            <span style="font-size: 15px"> (last updated at ' . $post['updated_at'] . ')</span>
            </div>
            <div>' . $post['content'] . '</div>
            '?>
            <?php
            $comments = findCommentsByPostId($post['id']);
            if(count($comments) > 0) {
                echo '<div>Comments:</div>';
                foreach ($comments as $comment) {
                    $commentingUser = findAuthorById($comment['uid']);
                    echo "<div>".$commentingUser['name'].": ".$comment['comment']."</div>";
                }
            }
            ?>
            <?php
            echo '<div>
                <form action="http://www.test.com/makeComment.php" method="POST">
                    <input style="height: 30px" type="text" name="comment"">
                    <input type="hidden" name="post_id" value="' . $post['id'] . '">
                    <button style="width: 10%;margin: 10px;" type="submit">Comment</button>
                </form>
            </div>
            '?><?php
            if($_SESSION['uid'] === $post['author_id'])
                echo '<button style="width: 10%;margin-top: 10px;" onclick="window.location.assign(\'http://www.test.com/editPost.php?id='.$post['id'].'\')">Edit</button>';
            echo '</div>';
        }
    }
}
?>
</div>
<button style="position: fixed; bottom: 0" onclick="window.location.replace('http://www.test.com/logout.php')">Logout</button>
</body>
</html>
<?php
$htmlContent = ob_get_clean();
echo $htmlContent;
?>
