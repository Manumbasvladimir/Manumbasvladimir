<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "user_registration");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT * FROM orders ORDER BY order_time DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>All Orders</title>
  <style>
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
      position: relative;
    }

    header {
      background-color: #111;
      color: #FFD700;
      text-align: center;
      padding: 20px;
      border-bottom: 2px solid #FFD700;
      position: relative;
      flex-shrink: 0;
    }

    h1 {
      margin: 0;
      font-size: 28px;
    }

    .dashboard-btn {
      position: absolute;
      top: 20px;
      right: 20px;
    }

    .dashboard-btn a {
      background-color: #FFD700;
      color: #000;
      padding: 10px 20px;
      text-decoration: none;
      font-weight: bold;
      border-radius: 5px;
      transition: background 0.3s;
    }

    .dashboard-btn a:hover {
      background-color: #e6c200;
    }

    main {
      padding: 30px;
      flex: 1;
      overflow-y: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #1a1a1a;
      margin-top: 20px;
    }

    th, td {
      padding: 12px;
      border: 1px solid #FFD700;
      text-align: center;
    }

    th {
      background-color: #FFD700;
      color: #000;
    }

    tr:hover {
      background-color: #2e2e2e;
    }

    footer {
      background-color: #111;
      color: #FFD700;
      text-align: center;
      padding: 15px;
      border-top: 1px solid #FFD700;
      flex-shrink: 0;
    }
  </style>
</head>
<body>

<header>
  <h1>All Orders</h1>
  <div class="dashboard-btn">
    <a href="admin_dashboard.php">← Back to Dashboard</a>
  </div>
</header>

<main>
  <table>
    <tr>
      <th>Order ID</th>
      <th>Product ID</th>
      <th>Product Name</th>
      <th>Quantity</th>
      <th>Price (Each)</th>
      <th>Total Price</th>
      <th>Order Time</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['product_id'] ?></td>
      <td><?= htmlspecialchars($row['product_name']) ?></td>
      <td><?= $row['quantity'] ?></td>
      <td>₱<?= number_format($row['price'], 2) ?></td>
      <td>₱<?= number_format($row['total_price'], 2) ?></td>
      <td><?= $row['order_time'] ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</main>

<footer>
  &copy; <?= date("Y") ?> Orchard Corporation. All rights reserved.
</footer>

</body>
</html>
