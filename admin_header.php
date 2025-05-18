<?php

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Admin Panel</title>
<style>
    body {
        background-color: #000;
        color: #FFD700;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0; padding: 0;
    }
    header, nav {
        background-color: #111;
        padding: 15px 20px;
        border-bottom: 2px solid #FFD700;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    nav a {
        color: #FFD700;
        margin-left: 15px;
        text-decoration: none;
        font-weight: bold;
    }
    nav a:hover {
        color: #e6c200;
    }
    main {
        padding: 30px;
    }
    h1 {
        margin-bottom: 20px;
        border-bottom: 2px solid #FFD700;
        padding-bottom: 10px;
    }
</style>
</head>
<body>
<header>
    <div><strong>Admin Panel</strong></div>
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>
<main>
