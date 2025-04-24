<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "user_registration";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Get form data
$type = $_POST['type'];
$productname = $_POST['productname'];
$quantity = (int) $_POST['quantity'];

// Insert or update (add quantity to existing)
$sql = "INSERT INTO product (type, productname, quantity)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("❌ SQL error: " . $conn->error);
}

$stmt->bind_param("ssi", $type, $productname, $quantity);

if ($stmt->execute()) {
    echo "✅ Product quantity updated successfully!";
} else {
    echo "❌ Execution error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
