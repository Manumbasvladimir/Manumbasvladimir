<?php
$mysqli = new mysqli("localhost", "root", "", "user_registration");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT * FROM menu_items ORDER BY quantity DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Inventory</title>
  <style>
    body {
      background-color: #0d0d0d;
      color: #f1c40f;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 30px;
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      position: relative;
      margin-bottom: 30px;
    }

    h1 {
      text-align: center;
      margin: 0;
      font-size: 32px;
    }

    .dashboard-link {
      position: absolute;
      top: 0;
      right: 0;
    }

    .dashboard-link a {
      background-color: #f1c40f;
      color: #000;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .dashboard-link a:hover {
      background-color: #d4ac0d;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #1a1a1a;
    }

    th, td {
      padding: 15px;
      text-align: center;
      border: 1px solid #f1c40f;
    }

    th {
      background-color: #f1c40f;
      color: #000;
    }

    tr:hover {
      background-color: #2e2e2e;
    }

    img {
      width: 80px;
      border-radius: 8px;
    }

    a.button {
      background-color: #f1c40f;
      color: #000;
      padding: 8px 15px;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    a.button:hover {
      background-color: #d4ac0d;
    }
  </style>
</head>
<body>

<header>
  <h1>Manage Inventory</h1>
  <div class="dashboard-link">
    <a href="admin_dashboard.php">Dashboard</a>
  </div>
</header>

<table>
  <tr>
    <th>Image</th>
    <th>Name</th>
    <th>Category</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Add Supply</th>
    <th>Edit Price</th>
  </tr>

  <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>"></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['category']) ?></td>
      <td>₱<?= htmlspecialchars(number_format($row['price'], 2)) ?></td>
      <td><?= htmlspecialchars($row['quantity']) ?></td>
      <td>
        <a class="button" href="addsupply.php?product=<?= urlencode($row['name']) ?>&category=<?= urlencode($row['category']) ?>">Add Supply</a>
      </td>
      <td>
        <a class="button" href="edit_price.php?id=<?= $row['id'] ?>">Edit Price</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

</body>
</html>
