<?php
$mysqli = new mysqli("localhost", "root", "", "user_registration");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_price = $_POST['price'];

    $stmt = $mysqli->prepare("UPDATE menu_items SET price = ? WHERE id = ?");
    $stmt->bind_param("di", $new_price, $id);
    $stmt->execute();

    header("Location: inventory.php");
    exit();
}

$result = $mysqli->query("SELECT * FROM menu_items WHERE id = $id");
$row = $result->fetch_assoc();

if (!$row) {
    die("Product not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Price</title>
  <style>
    body {
      background-color: #0d0d0d;
      color: #f1c40f;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 40px;
    }

    .header {
      position: relative;
      text-align: center;
      margin-bottom: 30px;
      font-size: 24px;
      font-weight: bold;
    }

    .inventory-link {
      position: absolute;
      top: 0;
      right: 0;
      background-color: #f1c40f;
      color: #000;
      padding: 8px 16px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      margin: 10px;
      transition: background-color 0.3s ease;
    }

    .inventory-link:hover {
      background-color: #d4ac0d;
    }

    form {
      max-width: 400px;
      margin: auto;
      background-color: #1a1a1a;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(241, 196, 15, 0.3);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
    }

    input[type="number"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #f1c40f;
      border-radius: 6px;
      background-color: #000;
      color: #f1c40f;
      margin-bottom: 20px;
    }

    button {
      background-color: #f1c40f;
      color: #000;
      border: none;
      padding: 12px;
      width: 100%;
      font-size: 16px;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
    }

    button:hover {
      background-color: #d4ac0d;
    }
  </style>
</head>
<body>

<div class="header">
  Edit Product Price
  <a href="inventory.php" class="inventory-link">Inventory</a>
</div>

<form method="POST">
  <h2>Edit Price for <?= htmlspecialchars($row['name']) ?></h2>

  <label for="price">New Price (₱)</label>
  <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($row['price']) ?>" required>

  <button type="submit">Update Price</button>
</form>

</body>
</html>
