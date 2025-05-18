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
    /* Reset margin and height to fill viewport */
    html, body {
        height: 100%;
        margin: 0;
        background-color: #000;
        color: #FFD700;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        flex-direction: column;
    }

    body {
        /* To allow footer at bottom */
        min-height: 100vh;
    }

    .content-wrapper {
        flex: 1; /* Take all vertical space except footer */
        display: flex;
        overflow: hidden; /* prevent body scroll */
    }

    .sidebar {
        width: 220px;
        background-color: #111;
        padding: 20px;
        border-right: 2px solid #FFD700;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
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

    /* Footer styling */
    footer {
        background-color: #111;
        color: #FFD700;
        text-align: center;
        padding: 15px;
        border-top: 1px solid #FFD700;
        font-size: 14px;
        user-select: none;
    }
</style>
</head>
<body>

<div class="content-wrapper">
    <div class="sidebar">
        <div>
            <h2>Orchard Staff</h2>
            <a href="view_order_staff.php">View Orders</a>
            <a href="payment_staff.php">Record Payments</a>
            <a href="issue_receipts.php">Issue Receipts</a>
        </div>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="main-content">
        <h1>Welcome, Staff!</h1>
        <p>Select an action from the menu.</p>
    </div>
</div>

<footer>
    &copy; <?= date("Y") ?> Orchard Corporation. All rights reserved.
</footer>

</body>
</html>
