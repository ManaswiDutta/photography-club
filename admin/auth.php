<?php
session_start();

function checkLogin() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}
?>
