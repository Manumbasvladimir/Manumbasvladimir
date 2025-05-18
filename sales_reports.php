<?php
// sales_reports.php

$mysqli = new mysqli("localhost", "root", "", "user_registration");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Use order_time instead of order_date
$query = "SELECT DATE(order_time) AS order_day, SUM(total_price) AS total_sales 
          FROM orders 
          GROUP BY order_day 
          ORDER BY order_day DESC";

$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sales Reports</title>
  <style>
    body {
      background-color: #0d0d0d;
      color: #f1c40f;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background-color: #111;
      color: #f1c40f;
      padding: 20px;
      text-align: center;
      border-bottom: 2px solid #f1c40f;
      position: relative;
    }

    header h1 {
      margin: 0;
      font-size: 28px;
    }

    .dashboard-btn {
      position: absolute;
      top: 20px;
      right: 20px;
    }

    .dashboard-btn a {
      background-color: #f1c40f;
      color: #000;
      padding: 10px 20px;
      text-decoration: none;
      font-weight: bold;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .dashboard-btn a:hover {
      background-color: #d4ac0d;
    }

    main {
      flex: 1;
      padding: 40px 20px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }

    table {
      width: 60%;
      border-collapse: collapse;
      background-color: #1a1a1a;
      box-shadow: 0 0 10px rgba(241, 196, 15, 0.3);
    }

    th, td {
      border: 1px solid #f1c40f;
      padding: 12px;
      text-align: center;
    }

    th {
      background-color: #f1c40f;
      color: #000;
    }

    tr:hover {
      background-color: #333;
    }

    footer {
      background-color: #111;
      color: #f1c40f;
      text-align: center;
      padding: 15px;
      border-top: 1px solid #f1c40f;
    }
  </style>
</head>
<body>

<header>
  <h1>Sales Reports</h1>
  <div class="dashboard-btn">
    <a href="admin_dashboard.php">← Back to Admin Dashboard</a>
  </div>
</header>

<main>
  <table>
    <tr>
      <th>Date</th>
      <th>Total Sales (₱)</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['order_day']) ?></td>
          <td>₱<?= number_format($row['total_sales'], 2) ?></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr>
        <td colspan="2">No sales data available.</td>
      </tr>
    <?php endif; ?>
  </table>
</main>

<footer>
  &copy; <?= date("Y") ?> Orchard Corporation. All rights reserved.
</footer>

</body>
</html>
