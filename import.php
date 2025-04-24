<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// MySQL connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "user_registration";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// If form submitted
if (isset($_POST['import']) && isset($_FILES['excel']['tmp_name'])) {
  $file = $_FILES['excel']['tmp_name'];
  $spreadsheet = IOFactory::load($file);
  $sheet = $spreadsheet->getActiveSheet();
  $rows = $sheet->toArray();

  // Skip header row (index 0)
  for ($i = 1; $i < count($rows); $i++) {
    $type = $rows[$i][0];
    $productname = $rows[$i][1];
    $quantity = $rows[$i][2];

    if (!empty($type) && !empty($productname) && is_numeric($quantity)) {
      $stmt = $conn->prepare("INSERT INTO product (type, productname, quantity) VALUES (?, ?, ?)");
      $stmt->bind_param("ssi", $type, $productname, $quantity);
      $stmt->execute();
      $stmt->close();
    }
  }

  echo "<script>alert('✅ Import successful!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Import Excel</title>
  <style>
    body {
      background-color: #0d0d0d;
      color: #f1c40f;
      font-family: Arial, sans-serif;
      padding: 50px;
      text-align: center;
    }
    form {
      background: #1a1a1a;
      padding: 30px;
      border-radius: 12px;
      display: inline-block;
      box-shadow: 0 0 10px rgba(241, 196, 15, 0.3);
    }
    input[type="file"] {
      padding: 10px;
      margin-bottom: 20px;
      background: #000;
      color: #f1c40f;
      border: 1px solid #f1c40f;
      border-radius: 5px;
    }
    button {
      background: #f1c40f;
      color: #000;
      border: none;
      padding: 12px 20px;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
    }
    button:hover {
      background: #d4ac0d;
    }
  </style>
</head>
<body>

  <h2>🧾 Import Products from Excel</h2>

  <form method="POST" enctype="multipart/form-data">
    <input type="file" name="excel" accept=".xls,.xlsx" required><br>
    <button type="submit" name="import">Import</button>
  </form>

</body>
</html>
