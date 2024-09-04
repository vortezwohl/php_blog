<?php
session_start();
unset($_SESSION['uid']);
$_SESSION['logged_in'] = false;
header('Location: index.php');