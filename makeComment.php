<?php
require_once 'config/database.php';
session_start();
if(isset($_POST['comment'])){
    $comment = $_POST['comment'];
    if(isset($pdo)) {
        $stmt = $pdo->prepare("INSERT INTO comment (comment, uid, pid) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$_POST['comment'], $_SESSION['uid'], $_POST['post_id']]);
            echo '<script>window.location.replace("http://www.test.com")</script>';
        } catch (PDOException $e) {
            echo '<script>alert("Comment failed");</script>';
        }
    }
}