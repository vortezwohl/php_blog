<?php
require_once 'config/database.php';
if(isset($_GET['id'])){
    $id = $_GET['id'];
    if (isset($pdo)) {
        $stmt = $pdo->prepare("UPDATE post SET is_deleted = 1 WHERE id = ?");
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0) {
            echo '<script>alert("Post deleted");window.location.assign("http://www.test.com")</script>';
        }
    }
}