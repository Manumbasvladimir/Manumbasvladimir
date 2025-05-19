<?php
session_start();

// Redirect if not logged in or not an admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orchard Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            display: flex;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #000;
            color: #FFD700;
        }

        .sidebar {
            width: 240px;
            background-color: #111;
            padding: 20px;
            border-right: 2px solid #FFD700;
            flex-shrink: 0;
        }

        .sidebar h2 {
            color: #FFD700;
            margin-bottom: 30px;
            text-align: center;
            font-size: 24px;
        }

        .sidebar a {
            display: block;
            color: #FFD700;
            padding: 10px 15px;
            text-decoration: none;
            margin-bottom: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #FFD700;
            color: #000;
        }

        .content-wrapper {
            display: flex;
            flex-direction: column;
            flex: 1;
            height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .main-content h1 {
            margin-bottom: 25px;
            font-size: 28px;
            border-bottom: 2px solid #FFD700;
            padding-bottom: 10px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: #111;
            border: 1px solid #FFD700;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 10px #FFD700;
        }

        .card h3 {
            margin-bottom: 10px;
            font-size: 20px;
        }

        .logout {
            margin-top: 30px;
            text-align: center;
        }

        .logout a {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .logout a:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #111;
            color: #FFD700;
            text-align: center;
            padding: 15px;
            border-top: 1px solid #FFD700;
        }
        
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Orchard</h2>
    <a href="approve_employees.php">Approve Employees</a>
    <a href="inventory.php">Manage Inventory</a>
    <a href="view_orders.php">View Orders</a>
    <a href="sales_reports.php">Sales Reports</a>
     <a href="addnewproduct.php">Add New Product</a>
    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="content-wrapper">
    <div class="main-content">
        <h1>Welcome, Admin!</h1>

        <div class="cards">
            <div class="card">
                <h3>Employee Approvals</h3>
                <p>View and approve new staff registrations.</p>
            </div>

            <div class="card">
                <h3>Inventory Management</h3>
                <p>Update menu items, stock, and prices.</p>
            </div>

            <div class="card">
                <h3>Orders</h3>
                <p>View all customer orders and status.</p>
            </div>

            <div class="card">
                <h3>Sales Reports</h3>
                <p>Analyze total and daily sales statistics.</p>
            </div>
              <div class="card">
                <h3>Add New Product</h3>
                <p>Can add new product to offer</p>
            </div>
        </div>
    </div>

    <footer>
        &copy; <?= date("Y") ?> Orchard Corporation. All rights reserved.
    </footer>
</div>

</body>
</html>
