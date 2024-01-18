<?php
session_start();

if (!isset($_SESSION['userid'])) {
    // Redirect to login page if not logged in
    header("Location: /auth/login.php");
    exit();
}

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: /auth/login.php");
    exit();
}
