<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// If logged in, redirect to farmer dashboard
header("Location: farmerdashboard.php");
exit();
?>
