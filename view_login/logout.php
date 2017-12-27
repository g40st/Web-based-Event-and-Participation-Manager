<?php
session_start();
require_once('../conf/path.php');
if (!isset($_SESSION['user'])) {
    header("Location: " . BASE_URL . "view_login/login.php");
} else if (isset($_SESSION['user']) != "") {
    header("Location: " . BASE_URL . "home.php");
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("Location: " . BASE_URL . "view_login/login.php");
    exit;
}
