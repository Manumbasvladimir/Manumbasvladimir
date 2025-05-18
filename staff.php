<?php
session_start();

// Redirect if not logged in or not staff
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Staff') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Orchard Staff Dashboard</title>
<style>
    body {
        background-color: #000;
        color: #FFD700;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        display: flex;
        height: 100vh;
        overflow: hidden;
    }
    .sidebar {
        width: 220px;
        background-color: #111;
        padding: 20px;
        border-right: 2px solid #FFD700;
    }
    .sidebar h2 {
        text-align: center;
        margin-bottom: 30px;
    }
    .sidebar a {
        display: block;
        color: #FFD700;
        padding: 12px 15px;
        text-decoration: none;
        margin-bottom: 12px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }
    .sidebar a:hover {
        background-color: #FFD700;
        color: #000;
    }
    .main-content {
        flex: 1;
        padding: 30px;
        background-color: #000;
        overflow-y: auto;
    }
    .main-content h1 {
        margin-bottom: 20px;
        border-bottom: 2px solid #FFD700;
        padding-bottom: 10px;
    }
    .logout {
        margin-top: 30px;
        text-align: center;
    }
    .logout a {
        color: red;
        font-weight: bold;
        text-decoration: none;
    }
    .logout a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="sidebar">
    <h2>Orchard Staff</h2>
    <a href="view_order_staff.php">View Orders</a>
    <a href="payment_staff.php">Record Payments</a>
    <a href="issue_receipts.php">Issue Receipts</a>

    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="main-content">
    <h1>Welcome, Staff!</h1>
    <p>Select an action from the menu.</p>
</div>

</body>
</html>
