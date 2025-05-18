<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "user_registration";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$type = $_POST['type'];
$productname = $_POST['productname'];
$quantity = (int) $_POST['quantity'];

$sql = "INSERT INTO menu_items (category, name, quantity)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("❌ SQL error: " . $conn->error);
}

$stmt->bind_param("ssi", $type, $productname, $quantity);

if ($stmt->execute()) {
    header("Location: addsupply.php");
    exit;
} else {
    echo "❌ Execution error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
